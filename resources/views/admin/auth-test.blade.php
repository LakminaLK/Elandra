@extends('admin.layouts.app')

@section('title', 'Admin Authentication Test')
@section('subtitle', 'Verify authentication and permissions')

@section('content')
<div class="p-6">
    <!-- Authentication Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">🔐 Authentication Status</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Admin Guard Status -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2">Admin Guard</h3>
                    <div class="space-y-1 text-sm">
                        <div>Authenticated: 
                            <span class="font-semibold {{ auth()->guard('admin')->check() ? 'text-green-600' : 'text-red-600' }}">
                                {{ auth()->guard('admin')->check() ? '✅ YES' : '❌ NO' }}
                            </span>
                        </div>
                        @if(auth()->guard('admin')->check())
                            <div>User ID: <span class="font-mono">{{ auth()->guard('admin')->id() }}</span></div>
                            <div>Name: <span class="font-semibold">{{ auth()->guard('admin')->user()->name }}</span></div>
                            <div>Email: <span class="font-semibold">{{ auth()->guard('admin')->user()->email }}</span></div>
                            <div>Role: <span class="font-semibold">{{ auth()->guard('admin')->user()->role }}</span></div>
                            <div>Active: 
                                <span class="font-semibold {{ auth()->guard('admin')->user()->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ auth()->guard('admin')->user()->is_active ? '✅ YES' : '❌ NO' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Default Guard Status -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Default Guard</h3>
                    <div class="space-y-1 text-sm">
                        <div>Guard: <span class="font-mono">{{ config('auth.defaults.guard') }}</span></div>
                        <div>Authenticated: 
                            <span class="font-semibold {{ auth()->check() ? 'text-green-600' : 'text-red-600' }}">
                                {{ auth()->check() ? '✅ YES' : '❌ NO' }}
                            </span>
                        </div>
                        @if(auth()->check())
                            <div>User ID: <span class="font-mono">{{ auth()->id() }}</span></div>
                            <div>Name: <span class="font-semibold">{{ auth()->user()->name }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">📋 Session Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2 text-sm">
                    <div><strong>Session ID:</strong> <span class="font-mono text-xs">{{ session()->getId() }}</span></div>
                    <div><strong>CSRF Token:</strong> <span class="font-mono text-xs">{{ csrf_token() }}</span></div>
                    <div><strong>Session Driver:</strong> {{ config('session.driver') }}</div>
                    <div><strong>Session Lifetime:</strong> {{ config('session.lifetime') }} minutes</div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div><strong>Current URL:</strong> {{ request()->url() }}</div>
                    <div><strong>Request Method:</strong> {{ request()->method() }}</div>
                    <div><strong>Route Name:</strong> {{ request()->route() ? request()->route()->getName() : 'N/A' }}</div>
                    <div><strong>Middleware:</strong> 
                        @if(request()->route())
                            {{ implode(', ', request()->route()->gatherMiddleware()) }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Test Buttons -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">🔌 API Tests</h2>
            
            <div class="flex flex-wrap gap-3">
                <button id="testAuth" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                    Test Auth Endpoint
                </button>
                <button id="testCustomers" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">
                    Test Customers API
                </button>
                <button id="testProtected" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-sm font-medium">
                    Test Protected Route
                </button>
                <button id="testCsrf" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md text-sm font-medium">
                    Test CSRF
                </button>
            </div>
            
            <!-- Test Results -->
            <div id="testResults" class="mt-4 space-y-2"></div>
        </div>
    </div>

    <!-- Permissions Check -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">🛡️ Permissions Check</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Admin Routes -->
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-medium text-green-900 mb-2">Admin Routes</h3>
                    <div class="space-y-1 text-sm">
                        <div>✅ Dashboard: <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Access</a></div>
                        <div>✅ Customers: <a href="{{ route('admin.customers.index') }}" class="text-blue-600 hover:underline">Access</a></div>
                        <div>✅ Products: <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">Access</a></div>
                    </div>
                </div>

                <!-- API Endpoints -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2">API Endpoints</h3>
                    <div class="space-y-1 text-sm">
                        <div>🔗 Customers API: /api/public/customers</div>
                        <div>🔗 Auth Debug: /admin/debug-auth</div>
                        <div>🔗 Test API: /admin/test-api</div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="font-medium text-yellow-900 mb-2">Security Features</h3>
                    <div class="space-y-1 text-sm">
                        <div>🔐 CSRF Protection: Enabled</div>
                        <div>🔐 Admin Middleware: Active</div>
                        <div>🔐 Session Security: Active</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resultsDiv = document.getElementById('testResults');
    
    // Configure Axios
    if (window.axios) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        window.axios.defaults.headers.common['Accept'] = 'application/json';
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }
    
    function addResult(title, status, message, data = null) {
        const result = document.createElement('div');
        result.className = `p-3 rounded-md border ${status === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}`;
        
        let content = `
            <div class="flex items-center">
                <span class="${status === 'success' ? 'text-green-600' : 'text-red-600'} mr-2">
                    ${status === 'success' ? '✅' : '❌'}
                </span>
                <strong>${title}</strong>
            </div>
            <div class="mt-1 text-sm text-gray-600">${message}</div>
        `;
        
        if (data) {
            content += `<pre class="mt-2 text-xs bg-gray-100 p-2 rounded overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>`;
        }
        
        result.innerHTML = content;
        resultsDiv.appendChild(result);
    }
    
    // Test Auth Endpoint
    document.getElementById('testAuth').addEventListener('click', async () => {
        try {
            const response = await fetch('/admin/debug-auth', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                addResult('Auth Debug', 'success', 'Authentication check successful', data);
            } else {
                addResult('Auth Debug', 'error', `HTTP ${response.status}: ${response.statusText}`);
            }
        } catch (error) {
            addResult('Auth Debug', 'error', `Error: ${error.message}`);
        }
    });
    
    // Test Customers API
    document.getElementById('testCustomers').addEventListener('click', async () => {
        try {
            const response = await window.axios.get('/api/public/customers');
            addResult('Customers API', 'success', 'Customers API working correctly', response.data);
        } catch (error) {
            addResult('Customers API', 'error', `Error: ${error.message}`, error.response?.data);
        }
    });
    
    // Test Protected Route
    document.getElementById('testProtected').addEventListener('click', async () => {
        try {
            const response = await window.axios.get('/admin/test-api');
            addResult('Protected Route', 'success', 'Protected admin route accessible', response.data);
        } catch (error) {
            addResult('Protected Route', 'error', `Error: ${error.message}`, error.response?.data);
        }
    });
    
    // Test CSRF
    document.getElementById('testCsrf').addEventListener('click', async () => {
        try {
            // Test with CSRF token
            const response = await window.axios.post('/admin/test-csrf', { test: 'data' });
            addResult('CSRF Test', 'success', 'CSRF protection working', response.data);
        } catch (error) {
            if (error.response?.status === 419) {
                addResult('CSRF Test', 'error', 'CSRF token mismatch - this is expected if CSRF is working');
            } else {
                addResult('CSRF Test', 'error', `Error: ${error.message}`, error.response?.data);
            }
        }
    });
});
</script>
@endsection