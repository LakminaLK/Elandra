@extends('admin.layouts.app')

@section('title', 'System Monitoring')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">System Monitoring</h1>
            <p class="mt-2 text-gray-600">Monitor the health and performance of your application</p>
        </div>

        <!-- System Status Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">System Status</h3>
                        <p class="text-sm text-gray-600" id="system-status">Checking...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 1.79 4 4 4h8c2.21 0 4-1.79 4-4V7c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Database</h3>
                        <p class="text-sm text-gray-600" id="database-status">Checking...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Cache</h3>
                        <p class="text-sm text-gray-600" id="cache-status">Checking...</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Storage</h3>
                        <p class="text-sm text-gray-600" id="storage-status">Checking...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Health Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Database Details -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Database Status</h2>
                </div>
                <div class="p-6">
                    <div id="database-details" class="space-y-4">
                        <p class="text-gray-600">Loading database information...</p>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">System Information</h2>
                </div>
                <div class="p-6">
                    <div id="system-details" class="space-y-4">
                        <p class="text-gray-600">Loading system information...</p>
                    </div>
                </div>
            </div>

            <!-- Environment Details -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Environment</h2>
                </div>
                <div class="p-6">
                    <div id="environment-details" class="space-y-4">
                        <p class="text-gray-600">Loading environment information...</p>
                    </div>
                </div>
            </div>

            <!-- Cache and Storage -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Performance</h2>
                </div>
                <div class="p-6">
                    <div id="performance-details" class="space-y-4">
                        <p class="text-gray-600">Loading performance information...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="mt-8 text-center">
            <button onclick="refreshHealthCheck()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Refresh Health Check
            </button>
            <p class="mt-2 text-sm text-gray-600">Last updated: <span id="last-updated">Never</span></p>
        </div>
    </div>
</div>

<script>
function updateStatus(elementId, status, message = '') {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const statusColors = {
        'healthy': 'text-green-600',
        'working': 'text-green-600', 
        'connected': 'text-green-600',
        'accessible': 'text-green-600',
        'degraded': 'text-yellow-600',
        'error': 'text-red-600',
        'unhealthy': 'text-red-600'
    };
    
    element.className = `text-sm ${statusColors[status] || 'text-gray-600'}`;
    element.textContent = message || status.charAt(0).toUpperCase() + status.slice(1);
}

function formatDetails(data, container) {
    let html = '';
    
    for (const [key, value] of Object.entries(data)) {
        if (typeof value === 'object' && value !== null) {
            html += `<div class="mb-3">
                <h4 class="font-medium text-gray-900 capitalize">${key.replace(/_/g, ' ')}</h4>
                <div class="ml-4 space-y-1">`;
            for (const [subKey, subValue] of Object.entries(value)) {
                html += `<div class="flex justify-between">
                    <span class="text-gray-600">${subKey.replace(/_/g, ' ')}:</span>
                    <span class="font-medium">${subValue}</span>
                </div>`;
            }
            html += `</div></div>`;
        } else {
            html += `<div class="flex justify-between">
                <span class="text-gray-600">${key.replace(/_/g, ' ')}:</span>
                <span class="font-medium">${value}</span>
            </div>`;
        }
    }
    
    document.getElementById(container).innerHTML = html;
}

async function refreshHealthCheck() {
    try {
        const response = await fetch('/health/detailed');
        const data = await response.json();
        
        // Update status indicators
        updateStatus('system-status', data.status);
        
        if (data.databases?.mysql) {
            updateStatus('database-status', data.databases.mysql.status);
        }
        
        if (data.cache) {
            updateStatus('cache-status', data.cache.status);
        }
        
        if (data.storage) {
            updateStatus('storage-status', data.storage.status);
        }
        
        // Update detailed information
        if (data.databases) {
            formatDetails(data.databases, 'database-details');
        }
        
        if (data.app) {
            formatDetails(data.app, 'system-details');
        }
        
        if (data.environment) {
            formatDetails(data.environment, 'environment-details');
        }
        
        // Performance details
        const performance = {};
        if (data.cache) performance.cache = data.cache;
        if (data.storage) performance.storage = data.storage;
        formatDetails(performance, 'performance-details');
        
        // Update timestamp
        document.getElementById('last-updated').textContent = new Date().toLocaleString();
        
    } catch (error) {
        console.error('Health check failed:', error);
        updateStatus('system-status', 'error', 'Failed to check');
    }
}

// Auto-refresh every 30 seconds
setInterval(refreshHealthCheck, 30000);

// Initial load
document.addEventListener('DOMContentLoaded', refreshHealthCheck);
</script>
@endsection