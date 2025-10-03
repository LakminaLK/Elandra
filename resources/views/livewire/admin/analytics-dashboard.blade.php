<div class="space-y-6" x-data="analyticsChart()" x-init="initCharts()">
    <!-- Header with Refresh -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="text-gray-600">Real-time business analytics and insights</p>
        </div>
        <div class="flex items-center space-x-4">
            <select wire:model.live="period" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
            </select>
            <button wire:click="refreshData" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-sync-alt mr-2" wire:loading.class="animate-spin"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                    <div class="flex items-center mt-2">
                        @if($revenueGrowth >= 0)
                            <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                            <span class="text-sm text-green-600 font-medium">{{ number_format(abs($revenueGrowth), 1) }}%</span>
                        @else
                            <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                            <span class="text-sm text-red-600 font-medium">{{ number_format(abs($revenueGrowth), 1) }}%</span>
                        @endif
                        <span class="text-sm text-gray-500 ml-1">vs previous period</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-400 to-green-600 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                    <div class="flex items-center mt-2">
                        @if($ordersGrowth >= 0)
                            <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                            <span class="text-sm text-green-600 font-medium">{{ number_format(abs($ordersGrowth), 1) }}%</span>
                        @else
                            <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                            <span class="text-sm text-red-600 font-medium">{{ number_format(abs($ordersGrowth), 1) }}%</span>
                        @endif
                        <span class="text-sm text-gray-500 ml-1">vs previous period</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-3 rounded-full">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">New Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                    <div class="flex items-center mt-2">
                        @if($usersGrowth >= 0)
                            <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                            <span class="text-sm text-green-600 font-medium">{{ number_format(abs($usersGrowth), 1) }}%</span>
                        @else
                            <i class="fas fa-arrow-down text-red-600 mr-1"></i>
                            <span class="text-sm text-red-600 font-medium">{{ number_format(abs($usersGrowth), 1) }}%</span>
                        @endif
                        <span class="text-sm text-gray-500 ml-1">vs previous period</span>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-400 to-purple-600 p-3 rounded-full">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Products</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                    <p class="text-sm text-gray-500 mt-2">Total available products</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-400 to-orange-600 p-3 rounded-full">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Revenue Trend</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        <!-- Orders Chart -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Orders Trend</h3>
            <canvas id="ordersChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Performance Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Products</h3>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded object-cover mr-3">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($product->name, 25) }}</p>
                                <p class="text-sm text-gray-500">{{ $product->total_sold }} sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">${{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Categories</h3>
            <div class="space-y-4">
                @forelse($topCategories as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded flex items-center justify-center mr-3">
                                <span class="text-white font-medium text-sm">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-sm text-gray-500">{{ $category->total_orders }} orders</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">${{ number_format($category->total_revenue, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
        <div class="flow-root">
            <ul class="-mb-8">
                @forelse($recentActivity as $index => $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full {{ $activity['color'] }} bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                        <i class="fas fa-{{ $activity['icon'] }} text-sm"></i>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $activity['title'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm">
                                        @if($activity['amount'])
                                            <p class="text-gray-900 font-medium">{{ $activity['amount'] }}</p>
                                        @endif
                                        <p class="text-gray-500">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-gray-500 text-center py-4">No recent activity</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function analyticsChart() {
            return {
                revenueChart: null,
                ordersChart: null,
                
                initCharts() {
                    this.createRevenueChart();
                    this.createOrdersChart();
                    
                    // Listen for Livewire updates
                    Livewire.on('updateCharts', (data) => {
                        this.updateCharts(data[0]);
                    });
                },
                
                createRevenueChart() {
                    const ctx = document.getElementById('revenueChart').getContext('2d');
                    this.revenueChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json(collect($revenueData)->pluck('date')),
                            datasets: [{
                                label: 'Revenue ($)',
                                data: @json(collect($revenueData)->pluck('total')),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value.toLocaleString();
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                },
                
                createOrdersChart() {
                    const ctx = document.getElementById('ordersChart').getContext('2d');
                    this.ordersChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json(collect($ordersData)->pluck('date')),
                            datasets: [{
                                label: 'Orders',
                                data: @json(collect($ordersData)->pluck('total')),
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(34, 197, 94)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                },
                
                updateCharts(data) {
                    if (this.revenueChart && data.revenue) {
                        this.revenueChart.data.labels = data.revenue.map(item => item.date);
                        this.revenueChart.data.datasets[0].data = data.revenue.map(item => item.total);
                        this.revenueChart.update();
                    }
                    
                    if (this.ordersChart && data.orders) {
                        this.ordersChart.data.labels = data.orders.map(item => item.date);
                        this.ordersChart.data.datasets[0].data = data.orders.map(item => item.total);
                        this.ordersChart.update();
                    }
                }
            }
        }
    </script>
</div>