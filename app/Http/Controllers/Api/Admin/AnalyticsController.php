<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MongoProduct;
use App\Models\Order;
use App\Models\MongoCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Admin Analytics API Controller
 * 
 * This controller provides comprehensive analytics endpoints for the admin dashboard
 * with advanced Sanctum authentication and role-based access control.
 * 
 * Features:
 * - Real-time analytics data
 * - Advanced filtering and date ranges
 * - Performance metrics
 * - Business intelligence insights
 */
class AnalyticsController extends Controller
{
    public function __construct()
    {
        // Ensure only authenticated admin users can access these endpoints
        $this->middleware(['auth:sanctum', 'admin']);
    }

    /**
     * Get comprehensive dashboard analytics
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getDashboardStats(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'nullable|integer|in:7,30,90,365',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $period = $request->get('period', 30);
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date'))
            : Carbon::now()->subDays($period);
        $endDate = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date'))
            : Carbon::now();

        $analytics = [
            'overview' => $this->getOverviewStats($startDate, $endDate),
            'revenue_trend' => $this->getRevenueTrend($startDate, $endDate),
            'orders_trend' => $this->getOrdersTrend($startDate, $endDate),
            'users_trend' => $this->getUsersTrend($startDate, $endDate),
            'top_products' => $this->getTopProducts($startDate, $endDate),
            'top_categories' => $this->getTopCategories($startDate, $endDate),
            'customer_insights' => $this->getCustomerInsights($startDate, $endDate),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $analytics,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'days' => $startDate->diffInDays($endDate) + 1
            ],
            'generated_at' => now()->toISOString()
        ]);
    }

    /**
     * Get real-time overview statistics
     */
    private function getOverviewStats(Carbon $startDate, Carbon $endDate): array
    {
        // Current period stats
        $currentRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $currentOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $currentUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeProducts = MongoProduct::where('is_active', true)->count();

        // Previous period for comparison
        $daysDiff = $startDate->diffInDays($endDate) + 1;
        $prevStartDate = $startDate->copy()->subDays($daysDiff);
        $prevEndDate = $startDate->copy()->subDay();

        $prevRevenue = Order::whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $prevOrders = Order::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();
        $prevUsers = User::whereBetween('created_at', [$prevStartDate, $prevEndDate])->count();

        return [
            'revenue' => [
                'current' => (float) $currentRevenue,
                'previous' => (float) $prevRevenue,
                'growth_percentage' => $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 0,
                'formatted_current' => '$' . number_format($currentRevenue, 2)
            ],
            'orders' => [
                'current' => $currentOrders,
                'previous' => $prevOrders,
                'growth_percentage' => $prevOrders > 0 ? (($currentOrders - $prevOrders) / $prevOrders) * 100 : 0,
                'average_order_value' => $currentOrders > 0 ? $currentRevenue / $currentOrders : 0
            ],
            'users' => [
                'current' => $currentUsers,
                'previous' => $prevUsers,
                'growth_percentage' => $prevUsers > 0 ? (($currentUsers - $prevUsers) / $prevUsers) * 100 : 0,
                'total_active' => User::where('is_active', true)->count()
            ],
            'products' => [
                'active' => $activeProducts,
                'out_of_stock' => MongoProduct::where('is_active', true)->where('stock_quantity', 0)->count(),
                'low_stock' => MongoProduct::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count()
            ]
        ];
    }

    /**
     * Get daily revenue trend
     */
    private function getRevenueTrend(Carbon $startDate, Carbon $endDate): array
    {
        $revenue = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $revenue->map(function ($item) {
            return [
                'date' => $item->date,
                'formatted_date' => Carbon::parse($item->date)->format('M j'),
                'total' => (float) $item->total,
                'formatted_total' => '$' . number_format($item->total, 2)
            ];
        })->toArray();
    }

    /**
     * Get daily orders trend
     */
    private function getOrdersTrend(Carbon $startDate, Carbon $endDate): array
    {
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $orders->map(function ($item) {
            return [
                'date' => $item->date,
                'formatted_date' => Carbon::parse($item->date)->format('M j'),
                'total' => $item->total
            ];
        })->toArray();
    }

    /**
     * Get daily users registration trend
     */
    private function getUsersTrend(Carbon $startDate, Carbon $endDate): array
    {
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $users->map(function ($item) {
            return [
                'date' => $item->date,
                'formatted_date' => Carbon::parse($item->date)->format('M j'),
                'total' => $item->total
            ];
        })->toArray();
    }

    /**
     * Get top performing products
     */
    private function getTopProducts(Carbon $startDate, Carbon $endDate, int $limit = 10): array
    {
        $products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.stock_quantity',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'products.stock_quantity')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
                'price' => (float) $product->price,
                'stock_quantity' => $product->stock_quantity,
                'total_revenue' => (float) $product->total_revenue,
                'total_sold' => $product->total_sold,
                'formatted_revenue' => '$' . number_format($product->total_revenue, 2)
            ];
        })->toArray();
    }

    /**
     * Get top performing categories
     */
    private function getTopCategories(Carbon $startDate, Carbon $endDate, int $limit = 10): array
    {
        $categories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('COUNT(DISTINCT order_items.product_id) as unique_products')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();

        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'total_revenue' => (float) $category->total_revenue,
                'total_orders' => $category->total_orders,
                'unique_products' => $category->unique_products,
                'formatted_revenue' => '$' . number_format($category->total_revenue, 2)
            ];
        })->toArray();
    }

    /**
     * Get customer insights and segmentation
     */
    private function getCustomerInsights(Carbon $startDate, Carbon $endDate): array
    {
        // Customer lifetime value
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('MAX(orders.created_at) as last_order_date')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // New vs returning customers
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $returningCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('user', function($query) use ($startDate) {
                $query->where('created_at', '<', $startDate);
            })
            ->distinct('user_id')
            ->count('user_id');

        return [
            'top_customers' => $topCustomers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'total_orders' => $customer->total_orders,
                    'total_spent' => (float) $customer->total_spent,
                    'formatted_spent' => '$' . number_format($customer->total_spent, 2),
                    'last_order_date' => $customer->last_order_date,
                    'avg_order_value' => $customer->total_orders > 0 ? $customer->total_spent / $customer->total_orders : 0
                ];
            })->toArray(),
            'customer_acquisition' => [
                'new_customers' => $newCustomers,
                'returning_customers' => $returningCustomers,
                'total_active_customers' => $newCustomers + $returningCustomers
            ]
        ];
    }

    /**
     * Get real-time activity feed
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getActivityFeed(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $limit = $request->get('limit', 20);

        $activities = collect([
            // Recent orders
            ...Order::with('user')
                ->latest()
                ->take($limit / 2)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => 'order_' . $order->id,
                        'type' => 'order',
                        'title' => 'New Order Placed',
                        'description' => "Order #{$order->id} placed by {$order->user->name}",
                        'amount' => $order->total_amount,
                        'formatted_amount' => '$' . number_format($order->total_amount, 2),
                        'user' => [
                            'id' => $order->user->id,
                            'name' => $order->user->name,
                            'email' => $order->user->email
                        ],
                        'status' => $order->status,
                        'timestamp' => $order->created_at->toISOString(),
                        'formatted_time' => $order->created_at->diffForHumans()
                    ];
                }),

            // Recent user registrations
            ...User::latest()
                ->take($limit / 4)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => 'user_' . $user->id,
                        'type' => 'user_registration',
                        'title' => 'New User Registration',
                        'description' => "{$user->name} joined the platform",
                        'amount' => null,
                        'formatted_amount' => null,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ],
                        'status' => $user->is_active ? 'active' : 'inactive',
                        'timestamp' => $user->created_at->toISOString(),
                        'formatted_time' => $user->created_at->diffForHumans()
                    ];
                }),

            // Recent products added
            ...MongoProduct::latest()
                ->take($limit / 4)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => 'product_' . $product->id,
                        'type' => 'product_added',
                        'title' => 'New Product Added',
                        'description' => "Product '{$product->name}' was added to catalog",
                        'amount' => $product->price,
                        'formatted_amount' => '$' . number_format($product->price, 2),
                        'user' => null,
                        'status' => $product->is_active ? 'active' : 'inactive',
                        'timestamp' => $product->created_at->toISOString(),
                        'formatted_time' => $product->created_at->diffForHumans()
                    ];
                })
        ])->sortByDesc('timestamp')->take($limit)->values();

        return response()->json([
            'status' => 'success',
            'data' => $activities,
            'meta' => [
                'count' => $activities->count(),
                'generated_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Export analytics data
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function exportAnalytics(Request $request): JsonResponse
    {
        $request->validate([
            'format' => 'required|in:json,csv',
            'period' => 'nullable|integer|in:7,30,90,365',
            'sections' => 'nullable|array',
            'sections.*' => 'in:overview,revenue,orders,users,products,categories'
        ]);

        $format = $request->get('format');
        $period = $request->get('period', 30);
        $sections = $request->get('sections', ['overview', 'revenue', 'orders', 'users']);

        $startDate = Carbon::now()->subDays($period);
        $endDate = Carbon::now();

        $exportData = [];

        if (in_array('overview', $sections)) {
            $exportData['overview'] = $this->getOverviewStats($startDate, $endDate);
        }

        if (in_array('revenue', $sections)) {
            $exportData['revenue_trend'] = $this->getRevenueTrend($startDate, $endDate);
        }

        if (in_array('orders', $sections)) {
            $exportData['orders_trend'] = $this->getOrdersTrend($startDate, $endDate);
        }

        if (in_array('users', $sections)) {
            $exportData['users_trend'] = $this->getUsersTrend($startDate, $endDate);
        }

        if (in_array('products', $sections)) {
            $exportData['top_products'] = $this->getTopProducts($startDate, $endDate, 50);
        }

        if (in_array('categories', $sections)) {
            $exportData['top_categories'] = $this->getTopCategories($startDate, $endDate, 20);
        }

        return response()->json([
            'status' => 'success',
            'data' => $exportData,
            'export_info' => [
                'format' => $format,
                'period_days' => $period,
                'sections' => $sections,
                'generated_at' => now()->toISOString(),
                'date_range' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString()
                ]
            ]
        ]);
    }
}