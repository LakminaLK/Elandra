<?php

use Illuminate\Support\Facades\Route;
use App\Models\MongoProduct;

Route::get('/test-products', function () {
    try {
        $products = MongoProduct::take(5)->get();
        $totalProducts = MongoProduct::count();
        
        return response()->json([
            'status' => 'success',
            'total_products' => $totalProducts,
            'sample_products' => $products->map(function($product) {
                return [
                    'id' => $product->_id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'brand' => $product->brand,
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                ];
            })
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('test.products');