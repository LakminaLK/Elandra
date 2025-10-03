<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductImageController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload product images
     */
    public function upload(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:5',
            'images.*' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $uploadResults = [];
            $currentImages = $product->images ? json_decode($product->images, true) : [];

            foreach ($request->file('images') as $file) {
                $result = $this->fileUploadService->uploadFile(
                    $file,
                    'products/' . $product->id,
                    'images'
                );

                if ($result['success']) {
                    $currentImages[] = [
                        'path' => $result['path'],
                        'url' => $result['url'],
                        'original_name' => $result['original_name'],
                        'size' => $result['size'],
                        'uploaded_at' => now()->toISOString()
                    ];
                    $uploadResults[] = $result;
                } else {
                    throw new \Exception($result['error']);
                }
            }

            // Update product with new images
            $product->update([
                'images' => json_encode($currentImages)
            ]);

            DB::commit();

            Log::info('Product images uploaded successfully', [
                'product_id' => $product->id,
                'images_count' => count($uploadResults)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully',
                'images' => $currentImages,
                'upload_results' => $uploadResults
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Product image upload failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product image
     */
    public function delete(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image_path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $imagePath = $request->input('image_path');
            $currentImages = $product->images ? json_decode($product->images, true) : [];

            // Find and remove the image from the array
            $updatedImages = array_filter($currentImages, function ($image) use ($imagePath) {
                return $image['path'] !== $imagePath;
            });

            if (count($updatedImages) === count($currentImages)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404);
            }

            // Delete the physical file
            $this->fileUploadService->deleteFile($imagePath);

            // Update product
            $product->update([
                'images' => json_encode(array_values($updatedImages))
            ]);

            Log::info('Product image deleted successfully', [
                'product_id' => $product->id,
                'image_path' => $imagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
                'images' => array_values($updatedImages)
            ]);

        } catch (\Exception $e) {
            Log::error('Product image deletion failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set main product image
     */
    public function setMain(Request $request, Product $product): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image_path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mainImagePath = $request->input('image_path');
            $currentImages = $product->images ? json_decode($product->images, true) : [];

            // Find the image and mark it as main
            $imageFound = false;
            foreach ($currentImages as &$image) {
                if ($image['path'] === $mainImagePath) {
                    $image['is_main'] = true;
                    $imageFound = true;
                } else {
                    $image['is_main'] = false;
                }
            }

            if (!$imageFound) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image not found'
                ], 404);
            }

            // Update product
            $product->update([
                'images' => json_encode($currentImages),
                'image' => $mainImagePath // Update the main image field
            ]);

            Log::info('Product main image set successfully', [
                'product_id' => $product->id,
                'main_image' => $mainImagePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Main image set successfully',
                'images' => $currentImages
            ]);

        } catch (\Exception $e) {
            Log::error('Setting main product image failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set main image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product images
     */
    public function index(Product $product): JsonResponse
    {
        $images = $product->images ? json_decode($product->images, true) : [];

        return response()->json([
            'success' => true,
            'images' => $images,
            'main_image' => $product->image
        ]);
    }
}
