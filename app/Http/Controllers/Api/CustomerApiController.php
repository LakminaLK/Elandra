<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerApiController extends Controller
{
    /**
     * Get all customers with pagination and search
     */
    public function index(Request $request)
    {
        try {
            Log::info('CustomerApiController::index called', [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'page' => $request->get('page', 1)
            ]);

            // Base query for customers
            $query = User::where('role', 'customer');

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('mobile', 'LIKE', "%{$search}%");
                });
            }

            // Apply status filter
            if ($request->filled('status') && $request->status !== '') {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } else {
                    $query->where('is_active', false);
                }
            }

            // Apply sorting
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Get paginated results
            $customers = $query->paginate(20)->withQueryString();

            // Transform customer data for frontend
            $transformedCustomers = $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->mobile ?? 'N/A',
                    'is_active' => $customer->is_active,
                    'status' => $customer->is_active ? 'Active' : 'Inactive',
                    'orders_count' => 0, // You can implement this later if needed
                    'created_at' => $customer->created_at->format('Y-m-d'),
                    'updated_at' => $customer->updated_at->format('Y-m-d H:i:s')
                ];
            });

            Log::info('CustomerApiController::index success', [
                'count' => $customers->count(),
                'total' => $customers->total()
            ]);

            return response()->json([
                'success' => true,
                'data' => $transformedCustomers,
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'per_page' => $customers->perPage(),
                    'total' => $customers->total(),
                    'from' => $customers->firstItem(),
                    'to' => $customers->lastItem()
                ],
                'message' => 'Customers retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerApiController::index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new customer
     */
    public function store(Request $request)
    {
        try {
            Log::info('CustomerApiController::store called', $request->except('password'));

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
            
            // Map phone to mobile field
            if (isset($validated['phone'])) {
                $validated['mobile'] = $validated['phone'];
                unset($validated['phone']);
            }

            $customer = User::create($validated);

            Log::info('CustomerApiController::store success', [
                'customer_id' => $customer->id,
                'email' => $customer->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->mobile ?? 'N/A',
                    'is_active' => $customer->is_active,
                    'status' => $customer->is_active ? 'Active' : 'Inactive',
                    'created_at' => $customer->created_at->format('Y-m-d')
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('CustomerApiController::store error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific customer
     */
    public function show($id)
    {
        try {
            $customer = User::where('role', 'customer')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->mobile ?? 'N/A',
                    'is_active' => $customer->is_active,
                    'status' => $customer->is_active ? 'Active' : 'Inactive',
                    'created_at' => $customer->created_at->format('Y-m-d'),
                    'updated_at' => $customer->updated_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerApiController::show error', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }
    }

    /**
     * Update a customer
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = User::where('role', 'customer')->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8',
            ]);

            // Only hash password if provided
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            // Map phone to mobile field
            if (isset($validated['phone'])) {
                $validated['mobile'] = $validated['phone'];
                unset($validated['phone']);
            }

            $customer->update($validated);

            Log::info('CustomerApiController::update success', [
                'customer_id' => $customer->id,
                'email' => $customer->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->mobile ?? 'N/A',
                    'is_active' => $customer->is_active,
                    'status' => $customer->is_active ? 'Active' : 'Inactive'
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('CustomerApiController::update error', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a customer
     */
    public function destroy($id)
    {
        try {
            $customer = User::where('role', 'customer')->findOrFail($id);
            
            // Check if customer has orders (if you have orders relationship)
            // if ($customer->orders()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot delete customer with existing orders!'
            //     ], 422);
            // }

            $customer->delete();

            Log::info('CustomerApiController::destroy success', [
                'customer_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerApiController::destroy error', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle customer status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $customer = User::where('role', 'customer')->findOrFail($id);
            
            $customer->is_active = !$customer->is_active;
            $customer->save();

            $status = $customer->is_active ? 'activated' : 'deactivated';

            Log::info('CustomerApiController::toggleStatus success', [
                'customer_id' => $id,
                'new_status' => $customer->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => "Customer {$status} successfully!",
                'data' => [
                    'id' => $customer->id,
                    'is_active' => $customer->is_active,
                    'status' => $customer->is_active ? 'Active' : 'Inactive'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CustomerApiController::toggleStatus error', [
                'customer_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API endpoint
     */
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Customer API is working correctly!',
            'timestamp' => now()->toISOString(),
            'customers_count' => User::where('role', 'customer')->count()
        ]);
    }
}