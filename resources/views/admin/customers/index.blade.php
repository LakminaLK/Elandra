@extends('admin.layouts.app')

@section('title', 'Customer Management')
@section('subtitle', 'Manage your customers')

@section('content')
<div class="p-6">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customer Management</h1>
            <p class="text-gray-600 mt-1">Manage your customer accounts</p>
        </div>
        <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus text-lg"></i>
            <span>Add Customer</span>
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search by name, email, or phone..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>
    </div>



    <!-- API Status Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <div class="text-sm">
                <p class="font-medium text-blue-800">API-Driven Customer Management</p>
                <p class="text-blue-600">All data is loaded dynamically from the backend API (/api/admin/customers)</p>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading customers from Backend API...
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" class="bg-white rounded-xl shadow-sm border border-red-200 p-8 text-center" style="display: none;">
        <div class="text-red-500 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p id="errorMessage" class="text-gray-600 mb-4">Failed to load customers</p>
        <button onclick="loadCustomersNow()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Retry
        </button>
    </div>

    <!-- Customers Table -->
    <div id="customersTable" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Customer List</h3>
                <span id="customerCount" class="text-sm text-gray-600">0 customers</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="customersTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Customer Modal -->
<div id="customerModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="customerForm">
                <div class="bg-white px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900">Create Customer</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="customerName" name="name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="nameError" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="customerEmail" name="email" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="emailError" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="customerPhone" name="phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="phoneError" class="text-red-600 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Password (only for create) -->
                        <div id="passwordField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="customerPassword" name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div id="passwordError" class="text-red-600 text-sm mt-1 hidden"></div>
                            <p class="text-sm text-gray-500 mt-1">Leave empty to keep current password (edit only)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="submitButton" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                        Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Delete Customer</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Are you sure you want to delete <strong id="deleteCustomerName"></strong>? 
                            This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteButton" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                    Delete Customer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let customersData = [];
let currentCustomerId = null;
let isEditMode = false;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeAxios();
    loadCustomers();
    setupEventListeners();
});

// Configure Axios
function initializeAxios() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken && typeof window.axios !== 'undefined') {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        window.axios.defaults.headers.common['Accept'] = 'application/json';
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }
}

// Setup event listeners
function setupEventListeners() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterCustomers(searchTerm);
    });

    // Form submission
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (isEditMode) {
            updateCustomer();
        } else {
            createCustomer();
        }
    });
}

// Load customers from API
async function loadCustomers() {
    showLoading();
    
    try {
        const response = await window.axios.get('/api/admin/customers');
        
        if (response.data.success) {
            customersData = response.data.data;
            displayCustomers();
        } else {
            throw new Error(response.data.message || 'Failed to load customers');
        }
        
    } catch (error) {
        console.error('Load customers error:', error);
        showError(error.response?.data?.message || error.message || 'Failed to load customers');
    }
}

// Display customers in table
function displayCustomers(customers = customersData) {
    const tbody = document.getElementById('customersTableBody');
    const customerCount = document.getElementById('customerCount');
    
    // Clear loading/error states
    document.getElementById('loadingState').style.display = 'none';
    document.getElementById('errorState').style.display = 'none';
    document.getElementById('customersTable').style.display = 'block';
    
    // Update count
    customerCount.textContent = `${customers.length} customers`;
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    if (customers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <div class="text-gray-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p class="text-lg font-medium">No customers found</p>
                        <p class="text-sm">Add your first customer to get started</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    // Add customer rows
    customers.forEach(customer => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${customer.id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.email}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.phone || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button onclick="toggleStatus(${customer.id})" 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-all duration-200 ${customer.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'}">
                    ${customer.status}
                </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${customer.created_at}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                    <button onclick="openEditModal(${customer.id})" 
                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                            title="Edit Customer">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <button onclick="openDeleteModal(${customer.id}, '${customer.name}')" 
                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                            title="Delete Customer">
                        <i class="fas fa-trash mr-1"></i>
                        Delete
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}

// Filter customers based on search
function filterCustomers(searchTerm) {
    if (!searchTerm) {
        displayCustomers();
        return;
    }
    
    const filtered = customersData.filter(customer => 
        customer.name.toLowerCase().includes(searchTerm) ||
        customer.email.toLowerCase().includes(searchTerm) ||
        (customer.phone && customer.phone.toLowerCase().includes(searchTerm))
    );
    
    displayCustomers(filtered);
}

// Show loading state
function showLoading() {
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('customersTable').style.display = 'none';
    document.getElementById('errorState').style.display = 'none';
}

// Show error state
function showError(message) {
    document.getElementById('loadingState').style.display = 'none';
    document.getElementById('customersTable').style.display = 'none';
    document.getElementById('errorState').style.display = 'block';
    document.getElementById('errorMessage').textContent = message;
}

// Modal functions
function openCreateModal() {
    isEditMode = false;
    currentCustomerId = null;
    document.getElementById('modalTitle').textContent = 'Create Customer';
    document.getElementById('submitButton').textContent = 'Create Customer';
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('customerPassword').required = true;
    resetForm();
    clearErrors();
    document.getElementById('customerModal').classList.remove('hidden');
}

function openEditModal(customerId) {
    const customer = customersData.find(c => c.id === customerId);
    if (!customer) return;
    
    isEditMode = true;
    currentCustomerId = customerId;
    document.getElementById('modalTitle').textContent = 'Edit Customer';
    document.getElementById('submitButton').textContent = 'Update Customer';
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('customerPassword').required = false;
    
    // Populate form
    document.getElementById('customerName').value = customer.name;
    document.getElementById('customerEmail').value = customer.email;
    document.getElementById('customerPhone').value = customer.phone || '';
    document.getElementById('customerPassword').value = '';
    
    clearErrors();
    document.getElementById('customerModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('customerModal').classList.add('hidden');
    resetForm();
}

function resetForm() {
    document.getElementById('customerForm').reset();
    clearErrors();
}

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
}

function showFieldError(fieldName, message) {
    const errorElement = document.getElementById(fieldName + 'Error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
}

// Create customer
async function createCustomer() {
    clearErrors();
    
    const formData = {
        name: document.getElementById('customerName').value,
        email: document.getElementById('customerEmail').value,
        phone: document.getElementById('customerPhone').value,
        password: document.getElementById('customerPassword').value
    };
    
    try {
        const response = await window.axios.post('/api/admin/customers', formData);
        
        if (response.data.success) {
            showNotification('Customer created successfully!', 'success');
            closeModal();
            loadCustomers(); // Reload the list
        } else {
            throw new Error(response.data.message || 'Failed to create customer');
        }
        
    } catch (error) {
        console.error('Create customer error:', error);
        
        if (error.response?.status === 422 && error.response.data.errors) {
            // Validation errors
            Object.keys(error.response.data.errors).forEach(field => {
                showFieldError(field, error.response.data.errors[field][0]);
            });
        } else {
            showNotification(error.response?.data?.message || error.message || 'Failed to create customer', 'error');
        }
    }
}

// Update customer
async function updateCustomer() {
    clearErrors();
    
    const formData = {
        name: document.getElementById('customerName').value,
        email: document.getElementById('customerEmail').value,
        phone: document.getElementById('customerPhone').value
    };
    
    // Only include password if it's provided
    const password = document.getElementById('customerPassword').value;
    if (password) {
        formData.password = password;
    }
    
    try {
        const response = await window.axios.put(`/api/admin/customers/${currentCustomerId}`, formData);
        
        if (response.data.success) {
            showNotification('Customer updated successfully!', 'success');
            closeModal();
            loadCustomers(); // Reload the list
        } else {
            throw new Error(response.data.message || 'Failed to update customer');
        }
        
    } catch (error) {
        console.error('Update customer error:', error);
        
        if (error.response?.status === 422 && error.response.data.errors) {
            // Validation errors
            Object.keys(error.response.data.errors).forEach(field => {
                showFieldError(field, error.response.data.errors[field][0]);
            });
        } else {
            showNotification(error.response?.data?.message || error.message || 'Failed to update customer', 'error');
        }
    }
}

// Delete modal functions
function openDeleteModal(customerId, customerName) {
    currentCustomerId = customerId;
    document.getElementById('deleteCustomerName').textContent = customerName;
    document.getElementById('deleteModal').classList.remove('hidden');
    
    // Setup delete confirmation
    document.getElementById('confirmDeleteButton').onclick = function() {
        deleteCustomer();
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentCustomerId = null;
}

// Delete customer
async function deleteCustomer() {
    try {
        const response = await window.axios.delete(`/api/admin/customers/${currentCustomerId}`);
        
        if (response.data.success) {
            showNotification('Customer deleted successfully!', 'success');
            closeDeleteModal();
            loadCustomers(); // Reload the list
        } else {
            throw new Error(response.data.message || 'Failed to delete customer');
        }
        
    } catch (error) {
        console.error('Delete customer error:', error);
        showNotification(error.response?.data?.message || error.message || 'Failed to delete customer', 'error');
    }
}

// Toggle customer status
async function toggleStatus(customerId) {
    try {
        const response = await window.axios.patch(`/api/admin/customers/${customerId}/toggle-status`);
        
        if (response.data.success) {
            showNotification(response.data.message, 'success');
            loadCustomers(); // Reload the list
        } else {
            throw new Error(response.data.message || 'Failed to toggle customer status');
        }
        
    } catch (error) {
        console.error('Toggle status error:', error);
        showNotification(error.response?.data?.message || error.message || 'Failed to toggle customer status', 'error');
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'success') {
        notification.className += ' bg-green-100 border border-green-400 text-green-700';
    } else if (type === 'error') {
        notification.className += ' bg-red-100 border border-red-400 text-red-700';
    } else {
        notification.className += ' bg-blue-100 border border-blue-400 text-blue-700';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>
@endsection
