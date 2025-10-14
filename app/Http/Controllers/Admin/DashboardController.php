<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MongoProduct;
use App\Models\Order;
use App\Models\MongoCategory;
use App\Models\MongoBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get dashboard statistics with real data
        $stats = [
            'total_products' => MongoProduct::count(),
            'total_brands' => MongoBrand::count(),
            'total_categories' => MongoCategory::count(),
            'total_customers' => User::where('role', '!=', 'admin')->count(),
            'total_orders' => Order::count(),
            'revenue' => Order::whereNotIn('status', ['cancelled'])
                ->sum('total_amount'),
        ];

        // Get revenue chart data (last 12 months, excluding cancelled orders)
        $revenueChartData = $this->getRevenueChartData();

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
            'revenueChartData',
            'recentOrders',
            'recentProducts',
            'recentUsers'
        ));
    }

    /**
     * Get revenue chart data for the last 12 months (excluding cancelled orders)
     */
    private function getRevenueChartData()
    {
        $chartData = [];
        
        // Get last 12 months data
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $year = $date->format('Y');
            
            $revenue = Order::whereNotIn('status', ['cancelled'])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $date->format('m'))
                ->sum('total_amount');
                
            $chartData['labels'][] = $monthName;
            $chartData['data'][] = (float) $revenue;
        }
        
        // Get weekly data for last 7 days
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->format('D');
            
            $revenue = Order::whereNotIn('status', ['cancelled'])
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('total_amount');
                
            $weeklyData['labels'][] = $dayName;
            $weeklyData['data'][] = (float) $revenue;
        }
        
        // Get last 4 weeks data
        $monthlyWeeks = [];
        for ($i = 3; $i >= 0; $i--) {
            $startDate = now()->subWeeks($i + 1)->startOfWeek();
            $endDate = now()->subWeeks($i)->endOfWeek();
            
            $revenue = Order::whereNotIn('status', ['cancelled'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
                
            $monthlyWeeks['labels'][] = 'Week ' . (4 - $i);
            $monthlyWeeks['data'][] = (float) $revenue;
        }
        
        // Get last 3 months data
        $quarterData = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $revenue = Order::whereNotIn('status', ['cancelled'])
                ->whereYear('created_at', $date->format('Y'))
                ->whereMonth('created_at', $date->format('m'))
                ->sum('total_amount');
                
            $quarterData['labels'][] = $monthName;
            $quarterData['data'][] = (float) $revenue;
        }
        
        return [
            'last_year' => $chartData,
            'last_7_days' => $weeklyData,
            'last_month' => $monthlyWeeks,
            'last_3_months' => $quarterData
        ];
    }
}
