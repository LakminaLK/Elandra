<?php

namespace App\Http\Controllers;

use App\Models\MongoProduct;
use App\Models\MongoCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function show($slug)
    {
        // Try to find by slug first, then by ID if slug doesn't exist
        $product = MongoProduct::where('is_active', true)
            ->where(function($query) use ($slug) {
                $query->where('slug', $slug)
                      ->orWhere('_id', $slug);
            })
            ->firstOrFail();
            
        return view('products.show', compact('product'));
    }

    public function category($slug)
    {
        $category = MongoCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('products.category', compact('category'));
    }
}
