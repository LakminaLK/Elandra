<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerManagementController extends Controller
{
    /**
     * Display the customers page with API-driven interface
     */
    public function index(Request $request)
    {
        // If this is an AJAX request, return JSON data
        if ($request->ajax() || $request->expectsJson()) {
            return $this->getCustomersApi($request);
        }
        
        // Return ONLY the view - no server-side data
        // All data will be loaded via JavaScript API calls
        return view('admin.customers.index');
    }

    /**
     * API endpoint to get customers data
     */
    public function getCustomersApi(Request $request)
    {
        try {
            \Log::info('getCustomersApi called', [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'user' => auth()->guard('admin')->user() ? auth()->guard('admin')->user()->email : 'not logged in'
            ]);
            
            // Get customers with basic info first
            $query = User::where('role', 'customer');
            
            // Try to add order count - if this fails, we'll see it in the logs
            try {
                $query = $query->withCount(['orders']);
            } catch (\Exception $e) {
                \Log::warning('Could not load orders count: ' . $e->getMessage());
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status') && $request->status !== '') {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            }

            // Sorting
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            $customers = $query->paginate(20)->withQueryString();
            
            \Log::info('Customers API response', [
                'count' => $customers->count(),
                'total' => $customers->total()
            ]);

            return response()->json([
                'success' => true,
                'data' => $customers->items(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ]);
        } catch (\Exception $e) {
            \Log::error('getCustomersApi error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['role'] = 'customer';
        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();
        $validated['is_active'] = true;

        $user = User::create($validated);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer' => $user
            ]);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer
     */
    public function show(Request $request, User $customer)
    {
        $customer->loadCount(['orders', 'cartItems']);
        $customer->load(['orders' => function($query) {
            $query->latest()->take(5);
        }]);
        
        // If AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json($customer);
        }
        
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Only hash password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $customer->update($validated);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'customer' => $customer
            ]);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy(Request $request, User $customer)
    {
        // Check if customer has orders before deletion
        if ($customer->orders()->count() > 0) {
            // If AJAX request, return JSON error
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete customer with existing orders!'
                ], 422);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer with existing orders!');
        }

        $customer->delete();

        // If AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    /**
     * Toggle customer status (active/inactive)
     */
    public function toggleStatus(Request $request, User $customer)
    {
        $customer->is_active = !$customer->is_active;
        $customer->save();

        $status = $customer->is_active ? 'activated' : 'deactivated';

        // If AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Customer {$status} successfully!",
                'is_active' => $customer->is_active
            ]);
        }
        
        return redirect()->route('admin.customers.index')
            ->with('success', "Customer {$status} successfully!");
    }

    /**
     * Export customers to CSV
     */
    public function exportCsv()
    {
        $users = User::where('role', 'customer')
            ->with(['orders'])
            ->withCount(['orders'])
            ->get();

        $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Phone', 'Status', 
            'Total Orders', 'Registration Date', 'Last Login'
        ]);
        
        foreach ($users as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->phone ?? 'N/A',
                $user->is_active ? 'Active' : 'Inactive',
                $user->orders_count,
                $user->created_at->format('Y-m-d'),
                $user->updated_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($output);
        exit();
    }
}
