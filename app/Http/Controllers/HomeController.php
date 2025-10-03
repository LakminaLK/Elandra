<?php

namespace App\Http\Controllers;

use App\Models\MongoProduct;
use App\Models\MongoCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = MongoProduct::where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $categories = MongoCategory::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $latestProducts = MongoProduct::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts', 'categories', 'latestProducts'));
    }
}
