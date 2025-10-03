@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-1">Manage your admin account information and security settings</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Side - Username Update Section -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Update Profile Information
                </h3>
                <p class="text-sm text-gray-600 mt-1">Change your name and email for this admin account</p>
            </div>
            
            <form id="profileForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_name">Current Name</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="current_name"
                                value="{{ auth('admin')->user()->name ?? 'Administrator' }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed outline-none focus:outline-none"
                                readonly
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">New Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none focus:outline-none"
                            required
                            autocomplete="name"
                            placeholder="Enter new name"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_email">Current Email</label>
                        <div class="relative">
                            <input
                                type="email"
                                id="current_email"
                                value="{{ auth('admin')->user()->email ?? 'admin@elandra.com' }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed outline-none focus:outline-none"
                                readonly
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">New Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none focus:outline-none"
                            required
                            autocomplete="email"
                            placeholder="Enter new email"
                        >
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">Important Information</h4>
                            <p class="text-sm text-blue-700 mt-1">Name and email must be unique among admins and case-sensitive. You'll need to use the new credentials for future logins.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side - Password Change Section -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Change Password
                </h3>
                <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure</p>
            </div>
            
            <form id="passwordForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_password">Current Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none focus:outline-none pr-12"
                                required
                                placeholder="Enter current password"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('current_password', 'currentPasswordIcon')">
                                <svg id="currentPasswordIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">New Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none focus:outline-none pr-12"
                                required
                                placeholder="Enter new password"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password', 'passwordIcon')">
                                <svg id="passwordIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">Confirm Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none focus:outline-none pr-12"
                                required
                                placeholder="Confirm new password"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password_confirmation', 'confirmPasswordIcon')">
                                <svg id="confirmPasswordIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Password Requirements</h4>
                            <ul class="text-sm text-yellow-700 mt-1 space-y-1">
                                <li>• At least 8 characters long</li>
                                <li>• Contains uppercase and lowercase letters</li>
                                <li>• Includes numbers or special characters</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// Password visibility toggle function using SVG icons
function togglePasswordVisibility(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        // Change to "eye-slash" (hide) icon
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordField.type = 'password';
        // Change to "eye" (show) icon
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const passwordForm = document.getElementById('passwordForm');

    // Profile Form Submission
    profileForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        clearErrors();
        
        const formData = new FormData(profileForm);
        const data = Object.fromEntries(formData);
        
        // Basic validation
        if (!data.name.trim()) {
            showFieldError('name', 'Name is required');
            return;
        }
        
        if (!data.email.trim()) {
            showFieldError('email', 'Email address is required');
            return;
        }
        
        if (!isValidEmail(data.email)) {
            showFieldError('email', 'Please enter a valid email address');
            return;
        }
        
        try {
            const response = await fetch('/admin/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Profile updated successfully!', 'success');
                // Update the current values display
                document.getElementById('current_name').value = data.name;
                document.getElementById('current_email').value = data.email;
                // Clear the new input fields
                profileForm.reset();
            } else {
                showNotification(result.message || 'Failed to update profile', 'error');
            }
        } catch (error) {
            showNotification('An error occurred while updating profile', 'error');
        }
    });

    // Password Form Submission with proper validations
    passwordForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        clearPasswordErrors();
        
        const formData = new FormData(passwordForm);
        const data = Object.fromEntries(formData);
        
        // Validate current password
        if (!data.current_password.trim()) {
            showPasswordError('current_password', 'Current password is required');
            return;
        }
        
        // Validate new password
        if (!data.new_password.trim()) {
            showPasswordError('new_password', 'New password is required');
            return;
        }
        
        if (data.new_password.length < 8) {
            showPasswordError('new_password', 'Password must be at least 8 characters long');
            return;
        }
        
        // Validate password confirmation
        if (!data.new_password_confirmation.trim()) {
            showPasswordError('new_password_confirmation', 'Please confirm your new password');
            return;
        }
        
        if (data.new_password !== data.new_password_confirmation) {
            showPasswordError('new_password_confirmation', 'Passwords do not match');
            return;
        }
        
        try {
            const response = await fetch('/admin/profile/update-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Password updated successfully!', 'success');
                passwordForm.reset();
            } else {
                if (result.message.includes('Current password')) {
                    showPasswordError('current_password', result.message);
                } else {
                    showNotification(result.message || 'Failed to update password', 'error');
                }
            }
        } catch (error) {
            showNotification('An error occurred while updating password', 'error');
        }
    });

    // Helper functions
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function clearErrors() {
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
        document.querySelectorAll('.text-red-600:not(.invalid-feedback)').forEach(el => {
            el.remove();
        });
    }

    function showFieldError(fieldName, message) {
        const field = document.getElementById(fieldName);
        field.classList.remove('border-gray-300');
        field.classList.add('border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-600 text-sm mt-1';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function clearPasswordErrors() {
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('#passwordForm .border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
    }

    function showPasswordError(fieldName, message) {
        const field = document.getElementById(fieldName);
        const errorDiv = document.getElementById(fieldName + '_error');
        
        field.classList.remove('border-gray-300');
        field.classList.add('border-red-500');
        
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
});

// Simple notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 500);
    }, 3000);
}
</script>