@extends('admin.layouts.app')

@section('content')
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
            @php
                $cards = [
                    [
                        'label' => 'Total Products',
                        'count' => $stats['total_products'],
                        'bg' => 'bg-blue-500',
                        'hover' => 'hover:bg-blue-600'
                    ],
                    [
                        'label' => 'Total Brands',
                        'count' => $stats['total_brands'],
                        'bg' => 'bg-green-500',
                        'hover' => 'hover:bg-green-600'
                    ],
                    [
                        'label' => 'Total Categories',
                        'count' => $stats['total_categories'],
                        'bg' => 'bg-purple-500',
                        'hover' => 'hover:bg-purple-600'
                    ],
                    [
                        'label' => 'Total Customers',
                        'count' => $stats['total_customers'],
                        'bg' => 'bg-red-500',
                        'hover' => 'hover:bg-red-600'
                    ],
                    [
                        'label' => 'Total Orders',
                        'count' => $stats['total_orders'],
                        'bg' => 'bg-indigo-500',
                        'hover' => 'hover:bg-indigo-600'
                    ],
                    [
                        'label' => 'Total Revenue',
                        'count' => '$' . number_format($stats['revenue'], 2),
                        'bg' => 'bg-orange-500',
                        'hover' => 'hover:bg-orange-600',
                        'isRevenue' => true
                    ]
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="{{ $card['bg'] }} p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 {{ $card['hover'] }}">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white bg-opacity-25 rounded-lg flex items-center justify-center mr-4">
                            @if($card['label'] == 'Total Products')
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @elseif($card['label'] == 'Total Brands')
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            @elseif($card['label'] == 'Total Categories')
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            @elseif($card['label'] == 'Total Customers')
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            @elseif($card['label'] == 'Total Orders')
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            @if(isset($card['isRevenue']))
                                <div class="text-2xl font-bold text-white mb-1">{{ $card['count'] }}</div>
                            @else
                                <div class="text-2xl font-bold text-white mb-1">{{ number_format($card['count']) }}</div>
                            @endif
                            <div class="text-sm font-medium text-white text-opacity-90">{{ $card['label'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
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
    
    // Real data from the controller
    const revenueData = @json($revenueChartData);
    
    const chartData = {
        labels: revenueData.last_year.labels,
        datasets: [{
            label: 'Revenue ($)',
            data: revenueData.last_year.data,
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
                newLabels = revenueData.last_7_days.labels;
                newData = revenueData.last_7_days.data;
                break;
            case 'last_month':
                newLabels = revenueData.last_month.labels;
                newData = revenueData.last_month.data;
                break;
            case 'last_3_months':
                newLabels = revenueData.last_3_months.labels;
                newData = revenueData.last_3_months.data;
                break;
            case 'last_year':
                newLabels = revenueData.last_year.labels;
                newData = revenueData.last_year.data;
                break;
        }

        revenueChart.data.labels = newLabels;
        revenueChart.data.datasets[0].data = newData;
        revenueChart.update('active');
    });
});
</script>
@endsection