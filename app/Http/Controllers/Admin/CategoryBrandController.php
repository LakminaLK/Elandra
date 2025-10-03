<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CategoryBrandController extends Controller
{
    public function index()
    {
        return view('admin.category-brand.index');
    }
}
