<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MongoProduct;
use App\Models\Order;
use App\Models\MongoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_products' => MongoProduct::count(),
            'total_orders' => Order::count(),
            'total_categories' => MongoCategory::count(),
        ];

        // Get recent orders
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent products
        $recentProducts = MongoProduct::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentProducts',
            'recentUsers'
        ));
    }
}
