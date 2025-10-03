<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MongoProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MongoProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = MongoProduct::query();

            // Search functionality
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Category filter
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            // Status filters
            if ($request->filled('status')) {
                switch ($request->status) {
                    case 'active':
                        $query->active();
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                    case 'featured':
                        $query->featured();
                        break;
                    case 'low-stock':
                        $query->lowStock();
                        break;
                    case 'out-of-stock':
                        $query->outOfStock();
                        break;
                }
            }

            // Price range filter
            if ($request->filled('min_price')) {
                $query->where('price', '>=', (float)$request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', (float)$request->max_price);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $perPage = min($perPage, 100); // Limit max per page

            $products = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Products retrieved successfully',
                'data' => [
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'from' => $products->firstItem(),
                        'to' => $products->lastItem(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0|lt:price',
                'cost' => 'nullable|numeric|min:0',
                'sku' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'brand' => 'nullable|string|max:100',
                'stock_quantity' => 'required|integer|min:0',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'track_quantity' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Generate SKU if not provided
            if (empty($validated['sku'])) {
                $validated['sku'] = Str::upper(Str::slug($validated['name'])) . '-' . time();
            }

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Handle additional images
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $images[] = $image->store('products', 'public');
                }
                $validated['images'] = $images;
            }

            // Set default values
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['is_featured'] = $validated['is_featured'] ?? false;
            $validated['track_quantity'] = $validated['track_quantity'] ?? true;
            $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 5;

            $product = MongoProduct::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => ['product' => $product]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = MongoProduct::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Product retrieved successfully',
                'data' => ['product' => $product]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $product = MongoProduct::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0|lt:price',
                'cost' => 'nullable|numeric|min:0',
                'sku' => 'sometimes|required|string',
                'category' => 'nullable|string|max:100',
                'brand' => 'nullable|string|max:100',
                'stock_quantity' => 'sometimes|required|integer|min:0',
                'low_stock_threshold' => 'nullable|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'track_quantity' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old main image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $validated['main_image'] = $request->file('main_image')->store('products', 'public');
            }

            // Handle additional images
            if ($request->hasFile('images')) {
                $images = $product->images ?? [];
                foreach ($request->file('images') as $image) {
                    $images[] = $image->store('products', 'public');
                }
                $validated['images'] = $images;
            }

            $product->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => ['product' => $product->fresh()]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $product = MongoProduct::find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }

            // Delete associated images
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            if ($product->images) {
                foreach ($product->images as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = MongoProduct::distinct('category')->get()->filter()->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => ['categories' => $categories]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product brands
     */
    public function brands(): JsonResponse
    {
        try {
            $brands = MongoProduct::distinct('brand')->get()->filter()->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Brands retrieved successfully',
                'data' => ['brands' => $brands]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products by query
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2',
                'per_page' => 'nullable|integer|min:1|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->get('q');
            $perPage = $request->get('per_page', 15);

            $products = MongoProduct::search($query)
                ->active()
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'data' => [
                    'query' => $query,
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_products' => MongoProduct::count(),
                'active_products' => MongoProduct::active()->count(),
                'inactive_products' => MongoProduct::where('is_active', false)->count(),
                'featured_products' => MongoProduct::featured()->count(),
                'low_stock_products' => MongoProduct::lowStock()->count(),
                'out_of_stock_products' => MongoProduct::outOfStock()->count(),
                'total_categories' => MongoProduct::distinct('category')->count(),
                'total_brands' => MongoProduct::distinct('brand')->count(),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Statistics retrieved successfully',
                'data' => ['stats' => $stats]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
