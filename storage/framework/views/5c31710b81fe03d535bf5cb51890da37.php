

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-gray-600 mt-1">Monitor platform performance and revenue analytics</p>
        </div>
    </div>

    <div class="space-y-6">
    <!-- Dashboard Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
                $cards = [
                    [
                        'label' => 'Total Products',
                        'count' => \App\Models\MongoProduct::count() ?? 8,
                        'bg' => 'bg-blue-500',
                        'hover' => 'hover:bg-blue-600'
                    ],
                    [
                        'label' => 'Total Orders',
                        'count' => 1247,
                        'bg' => 'bg-green-500',
                        'hover' => 'hover:bg-green-600'
                    ],
                    [
                        'label' => 'Total Categories',
                        'count' => \App\Models\MongoCategory::count() ?? 6,
                        'bg' => 'bg-purple-500',
                        'hover' => 'hover:bg-purple-600'
                    ],
                    [
                        'label' => 'Total Users',
                        'count' => \App\Models\User::count() ?? 24,
                        'bg' => 'bg-red-500',
                        'hover' => 'hover:bg-red-600'
                    ],
                    [
                        'label' => 'Total Customers',
                        'count' => \App\Models\User::count() ?? 24,
                        'bg' => 'bg-indigo-500',
                        'hover' => 'hover:bg-indigo-600'
                    ],
                    [
                        'label' => 'Revenue (Last 30 Days)',
                        'count' => '$24,580.00',
                        'bg' => 'bg-orange-500',
                        'hover' => 'hover:bg-orange-600',
                        'isRevenue' => true
                    ]
                ];
            ?>

            <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="<?php echo e($card['bg']); ?> p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 <?php echo e($card['hover']); ?>">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-25 rounded-lg flex items-center justify-center mr-4">
                            <?php if($card['label'] == 'Total Products'): ?>
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            <?php elseif($card['label'] == 'Total Orders'): ?>
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                                </svg>
                            <?php elseif($card['label'] == 'Total Categories'): ?>
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            <?php elseif($card['label'] == 'Total Users' || $card['label'] == 'Total Customers'): ?>
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <?php if(isset($card['isRevenue'])): ?>
                                <div class="text-2xl font-bold text-white mb-1"><?php echo e($card['count']); ?></div>
                            <?php else: ?>
                                <div class="text-2xl font-bold text-white mb-1"><?php echo e(number_format($card['count'])); ?></div>
                            <?php endif; ?>
                            <div class="text-sm font-medium text-white text-opacity-90"><?php echo e($card['label']); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <p class="text-gray-600 text-sm">Daily sales earnings from platform orders</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <select id="periodFilter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm font-medium focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="last_7_days">Last 7 Days</option>
                            <option value="last_month" selected>Last Month</option>
                            <option value="last_3_months">Last 3 Months</option>
                            <option value="last_year">Last Year</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="h-80 relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Sample data for the chart - similar to TripMate style
    const chartData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue ($)',
            data: [12500, 18750, 15300, 22100, 19800, 25600, 28900, 32100, 29500, 35200, 38700, 42300],
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    };

    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return '$' + (value / 1000) + 'K';
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                    hoverBorderColor: '#ffffff',
                    hoverBorderWidth: 3
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    };

    const revenueChart = new Chart(ctx, config);

    // Period filter functionality
    document.getElementById('periodFilter').addEventListener('change', function() {
        const period = this.value;
        let newData = [];
        let newLabels = [];

        switch(period) {
            case 'last_7_days':
                newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                newData = [1200, 1800, 1500, 2200, 1980, 2560, 2100];
                break;
            case 'last_month':
                newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                newData = [8500, 12750, 10300, 15100];
                break;
            case 'last_3_months':
                newLabels = ['3 months ago', '2 months ago', 'Last month'];
                newData = [45200, 52100, 48900];
                break;
            case 'last_year':
                newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                newData = [12500, 18750, 15300, 22100, 19800, 25600, 28900, 32100, 29500, 35200, 38700, 42300];
                break;
        }

        revenueChart.data.labels = newLabels;
        revenueChart.data.datasets[0].data = newData;
        revenueChart.update('active');
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Elandra\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>