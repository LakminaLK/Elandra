<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\MongoProduct;
use App\Models\Order;
use App\Models\MongoCategory;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsDashboard extends Component
{
    public $period = '30'; // days
    public $revenueData = [];
    public $ordersData = [];
    public $usersData = [];
    public $topProducts = [];
    public $topCategories = [];
    public $recentActivity = [];
    
    // Real-time stats
    public $totalRevenue = 0;
    public $totalOrders = 0;
    public $totalUsers = 0;
    public $totalProducts = 0;
    public $revenueGrowth = 0;
    public $ordersGrowth = 0;
    public $usersGrowth = 0;
    
    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function updatedPeriod()
    {
        $this->loadAnalytics();
        $this->dispatch('updateCharts', [
            'revenue' => $this->revenueData,
            'orders' => $this->ordersData,
            'users' => $this->usersData
        ]);
    }

    public function loadAnalytics()
    {
        $startDate = Carbon::now()->subDays($this->period);
        $previousPeriodStart = Carbon::now()->subDays($this->period * 2);
        
        // Calculate totals and growth
        $this->calculateTotalsAndGrowth($startDate, $previousPeriodStart);
        
        // Load chart data
        $this->loadChartData($startDate);
        
        // Load top performers
        $this->loadTopPerformers($startDate);
        
        // Load recent activity
        $this->loadRecentActivity();
    }

    private function calculateTotalsAndGrowth($startDate, $previousPeriodStart)
    {
        // Current period totals
        $this->totalRevenue = Order::where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $this->totalOrders = Order::where('created_at', '>=', $startDate)->count();
        
        $this->totalUsers = User::where('created_at', '>=', $startDate)->count();
        
        $this->totalProducts = MongoProduct::where('is_active', true)->count();

        // Previous period totals for growth calculation
        $prevRevenue = Order::where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $startDate)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $prevOrders = Order::where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $startDate)
            ->count();

        $prevUsers = User::where('created_at', '>=', $previousPeriodStart)
            ->where('created_at', '<', $startDate)
            ->count();

        // Calculate growth percentages
        $this->revenueGrowth = $prevRevenue > 0 ? (($this->totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $this->ordersGrowth = $prevOrders > 0 ? (($this->totalOrders - $prevOrders) / $prevOrders) * 100 : 0;
        $this->usersGrowth = $prevUsers > 0 ? (($this->totalUsers - $prevUsers) / $prevUsers) * 100 : 0;
    }

    private function loadChartData($startDate)
    {
        // Daily revenue data
        $revenueQuery = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->revenueData = $revenueQuery->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M j'),
                'total' => (float) $item->total
            ];
        })->toArray();

        // Daily orders data
        $ordersQuery = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->ordersData = $ordersQuery->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M j'),
                'total' => $item->total
            ];
        })->toArray();

        // Daily users data
        $usersQuery = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->usersData = $usersQuery->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M j'),
                'total' => $item->total
            ];
        })->toArray();
    }

    private function loadTopPerformers($startDate)
    {
        // Top products by revenue
        $this->topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->toArray();

        // Top categories by revenue
        $this->topCategories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $startDate)
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function loadRecentActivity()
    {
        $this->recentActivity = collect([
            // Recent orders
            ...Order::with('user')
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($order) {
                    return [
                        'type' => 'order',
                        'icon' => 'shopping-cart',
                        'color' => 'text-green-600',
                        'title' => 'New Order',
                        'description' => "Order #{$order->id} by {$order->user->name}",
                        'amount' => '$' . number_format($order->total_amount, 2),
                        'time' => $order->created_at->diffForHumans()
                    ];
                }),

            // Recent users
            ...User::latest()
                ->take(2)
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user',
                        'icon' => 'user-plus',
                        'color' => 'text-blue-600',
                        'title' => 'New User Registration',
                        'description' => "{$user->name} joined",
                        'amount' => null,
                        'time' => $user->created_at->diffForHumans()
                    ];
                }),
        ])->sortByDesc(function ($item) {
            return Carbon::parse($item['time']);
        })->take(5)->values()->toArray();
    }

    public function refreshData()
    {
        $this->loadAnalytics();
        session()->flash('message', 'Dashboard data refreshed!');
    }

    public function render()
    {
        return view('livewire.admin.analytics-dashboard');
    }
}