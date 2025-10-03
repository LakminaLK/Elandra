<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the product management page.
     * All CRUD operations are handled by Livewire components.
     */
    public function index()
    {
        return view('admin.products.index');
    }
}
