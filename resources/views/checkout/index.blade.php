<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Checkout - Elandra</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Critical CSS for immediate rendering */
        body { 
            font-family: 'Figtree', sans-serif; 
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            min-height: 100vh;
        }
        .card-input { 
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.2s;
        }
        .card-input:focus { 
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
            border-color: #3b82f6; 
        }
        .payment-step { 
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            transition: all 0.3s ease; 
        }
        .payment-step.active { 
            transform: scale(1.02); 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        }
        .btn-primary {
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.125rem;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #1d4ed8 0%, #4338ca 100%);
            transform: translateY(-1px);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .animate-pulse-soft {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .error-msg {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .hidden { display: none !important; }
        .flex { display: flex !important; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .text-gray-700 { color: #374151; }
        .text-gray-900 { color: #111827; }
        .text-blue-600 { color: #2563eb; }
        .border-gray-300 { border-color: #d1d5db; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .pt-6 { padding-top: 1.5rem; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .header { background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-bottom: 1px solid #e5e7eb; }
        .header-content { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; }
        .logo { height: 2rem; width: auto; }
        .security-indicator { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #6b7280; }
        .main-grid { display: grid; gap: 2rem; padding: 2rem 0; }
        .order-summary { background: white; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 1.5rem; position: sticky; top: 2rem; }
        .loading-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center; }
        .loading-content { background: white; border-radius: 0.75rem; padding: 2rem; text-align: center; max-width: 20rem; margin: 0 1rem; }
        
        @media (min-width: 768px) {
            .md\\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .md\\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .main-grid { grid-template-columns: 2fr 1fr; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ asset('elandra-logo.webp') }}" alt="Elandra" class="logo">
                    <div>
                        <h1 style="font-size: 1.25rem; font-weight: 600; color: #111827; margin: 0;">Secure Checkout</h1>
                    </div>
                </div>
                <div class="security-indicator">
                    <i class="fas fa-lock" style="color: #10b981;"></i>
                    <span>SSL Secured</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="main-grid">
            <!-- Payment Form -->
            <div>
                <div class="payment-step active">
                    <form id="checkout-form" class="space-y-6">
                        @csrf
                        
                        <!-- Selected Cart Items -->
                        @php
                            $selectedCartItems = session('selected_cart_items', []);
                        @endphp
                        @foreach($selectedCartItems as $itemId)
                            <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                        @endforeach
                        
                        <!-- Progress Steps -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    1
                                </div>
                                <span class="text-sm font-medium text-blue-600">Payment Details</span>
                            </div>
                            <div class="flex-1 mx-4 h-0.5 bg-gray-200"></div>
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">
                                    2
                                </div>
                                <span class="text-sm text-gray-500">Confirmation</span>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="billing_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                           placeholder="John Doe">
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="billing_email" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                           placeholder="john@example.com">
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="billing_phone" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                           placeholder="(555) 123-4567">
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                                    <input type="text" name="billing_address" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                           placeholder="123 Main Street">
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                        <input type="text" name="billing_city" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                               placeholder="New York">
                                        <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                        <input type="text" name="billing_state" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                               placeholder="NY">
                                        <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                                        <input type="text" name="billing_zip" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                               placeholder="10001">
                                        <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                                Payment Information
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                                    <div class="relative">
                                        <input type="text" name="card_number" required maxlength="19" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none pr-12"
                                               placeholder="1234 5678 9012 3456">
                                        <div class="absolute inset-y-0 right-3 flex items-center">
                                            <i class="fas fa-credit-card text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name *</label>
                                    <input type="text" name="card_name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                           placeholder="John Doe">
                                    <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                        <input type="text" name="card_expiry" required maxlength="5" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                               placeholder="MM/YY">
                                        <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                                        <input type="text" name="card_cvv" required maxlength="4" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg card-input focus:outline-none"
                                               placeholder="123">
                                        <div class="error-msg text-red-600 text-sm mt-1 hidden"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" id="submit-btn" 
                                    class="btn-primary">
                                <span id="submit-text" class="flex items-center justify-center">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Complete Secure Payment
                                </span>
                                <span id="submit-loading" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Processing Payment...
                                </span>
                            </button>
                        </div>

                        <!-- Security Notice -->
                        <div class="bg-gray-50 rounded-lg p-4 mt-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-lock text-green-600 mr-2"></i>
                                Your payment information is encrypted and secure. We never store your card details.
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="order-summary">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <!-- Items -->
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                @php
                                    // Try to get the MongoDB product for image
                                    $mongoProduct = null;
                                    try {
                                        if ($item->product_id) {
                                            $mongoProduct = \App\Models\MongoProduct::where('_id', $item->product_id)->first();
                                        }
                                    } catch (\Exception $e) {
                                        // Ignore if product not found
                                    }
                                @endphp
                                
                                @if($mongoProduct && isset($mongoProduct->images[0]))
                                    <img src="{{ asset('storage/' . $mongoProduct->images[0]) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</h4>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                ${{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-900">
                                @if($shipping == 0)
                                    <span class="text-green-600">FREE</span>
                                @else
                                    ${{ number_format($shipping, 2) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="text-gray-900">${{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-gray-900">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-6 grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <i class="fas fa-truck text-blue-600 text-lg mb-1"></i>
                            <p class="text-xs text-gray-600">Free Shipping<br>Over $200</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <i class="fas fa-undo text-green-600 text-lg mb-1"></i>
                            <p class="text-xs text-gray-600">30-Day<br>Returns</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="loading-content">
            <div class="animate-pulse-soft mb-4">
                <i class="fas fa-credit-card text-blue-600 text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Processing Your Payment</h3>
            <p class="text-gray-600 mb-4">Please don't close or refresh this page...</p>
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>

    <script>
        // Modern Form Validation and Processing
        class CheckoutForm {
            constructor() {
                this.form = document.getElementById('checkout-form');
                this.submitBtn = document.getElementById('submit-btn');
                this.loadingOverlay = document.getElementById('loading-overlay');
                this.isProcessing = false;
                
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.setupCardFormatting();
            }

            setupEventListeners() {
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                
                // Real-time validation
                const inputs = this.form.querySelectorAll('input[required]');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => this.validateField(input));
                    input.addEventListener('input', () => this.clearError(input));
                });
            }

            setupCardFormatting() {
                const cardNumber = document.querySelector('input[name="card_number"]');
                const cardExpiry = document.querySelector('input[name="card_expiry"]');
                const cardCvv = document.querySelector('input[name="card_cvv"]');

                // Format card number
                cardNumber.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                    e.target.value = value.slice(0, 19);
                });

                // Format expiry
                cardExpiry.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });

                // Format CVV
                cardCvv.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
                });
            }

            validateField(input) {
                const value = input.value.trim();
                const name = input.name;
                let isValid = true;
                let message = '';

                // Required check
                if (!value) {
                    isValid = false;
                    message = 'This field is required';
                }
                // Email validation
                else if (name === 'billing_email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        message = 'Please enter a valid email address';
                    }
                }
                // Card number validation
                else if (name === 'card_number') {
                    const cleanNumber = value.replace(/\s/g, '');
                    if (cleanNumber.length < 13 || cleanNumber.length > 19) {
                        isValid = false;
                        message = 'Please enter a valid card number';
                    }
                }
                // Expiry validation
                else if (name === 'card_expiry') {
                    const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
                    if (!expiryRegex.test(value)) {
                        isValid = false;
                        message = 'Please enter a valid expiry date (MM/YY)';
                    } else {
                        const [month, year] = value.split('/');
                        const expiry = new Date(2000 + parseInt(year), parseInt(month) - 1);
                        const now = new Date();
                        if (expiry <= now) {
                            isValid = false;
                            message = 'Card has expired';
                        }
                    }
                }
                // CVV validation
                else if (name === 'card_cvv') {
                    if (value.length < 3 || value.length > 4) {
                        isValid = false;
                        message = 'Please enter a valid CVV';
                    }
                }

                this.showFieldError(input, isValid ? '' : message);
                return isValid;
            }

            showFieldError(input, message) {
                // Try to find error div in parent element or next sibling
                let errorDiv = input.parentElement.querySelector('.error-msg');
                if (!errorDiv) {
                    errorDiv = input.nextElementSibling?.classList.contains('error-msg') ? input.nextElementSibling : null;
                }
                
                if (message) {
                    if (errorDiv) {
                        errorDiv.textContent = message;
                        errorDiv.classList.remove('hidden');
                    } else {
                        console.warn('Error div not found for input:', input.name);
                    }
                    input.classList.add('border-red-500');
                } else {
                    if (errorDiv) {
                        errorDiv.classList.add('hidden');
                        errorDiv.textContent = '';
                    }
                    input.classList.remove('border-red-500');
                }
            }

            clearError(input) {
                this.showFieldError(input, '');
            }

            validateForm() {
                const inputs = this.form.querySelectorAll('input[required]');
                let isValid = true;
                
                console.log('Validating form with', inputs.length, 'required inputs');

                inputs.forEach(input => {
                    console.log('Validating input:', input.name, 'value:', input.value);
                    const fieldValid = this.validateField(input);
                    if (!fieldValid) {
                        isValid = false;
                        console.log('Field validation failed for:', input.name);
                    }
                });

                console.log('Form validation result:', isValid);
                return isValid;
            }

            showLoading() {
                this.isProcessing = true;
                this.submitBtn.disabled = true;
                document.getElementById('submit-text').classList.add('hidden');
                const loadingSpan = document.getElementById('submit-loading');
                loadingSpan.classList.remove('hidden');
                loadingSpan.classList.add('flex', 'items-center', 'justify-center');
                this.loadingOverlay.classList.remove('hidden');
                this.loadingOverlay.classList.add('flex');
            }

            hideLoading() {
                this.isProcessing = false;
                this.submitBtn.disabled = false;
                document.getElementById('submit-text').classList.remove('hidden');
                const loadingSpan = document.getElementById('submit-loading');
                loadingSpan.classList.add('hidden');
                loadingSpan.classList.remove('flex', 'items-center', 'justify-center');
                this.loadingOverlay.classList.add('hidden');
                this.loadingOverlay.classList.remove('flex');
            }

            async handleSubmit(e) {
                e.preventDefault();

                if (this.isProcessing) return;

                // Validate form
                if (!this.validateForm()) {
                    return;
                }

                this.showLoading();

                try {
                    const formData = new FormData(this.form);
                    
                    const response = await fetch('{{ route("checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        let errorData;
                        try {
                            errorData = JSON.parse(errorText);
                        } catch (e) {
                            errorData = { message: 'Server error occurred' };
                        }
                        throw new Error(errorData.message || 'Payment processing failed');
                    }

                    const data = await response.json();

                    if (data.success) {
                        // Show success animation
                        this.showSuccess();
                        
                        // Redirect to success page
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Payment failed');
                    }

                } catch (error) {
                    console.error('Payment error:', error);
                    this.hideLoading();
                    this.showError(error.message);
                }
            }

            showSuccess() {
                const overlay = this.loadingOverlay.querySelector('div');
                overlay.innerHTML = `
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-check-circle text-6xl animate-bounce"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Successful!</h3>
                    <p class="text-gray-600 mb-4">Your order has been confirmed.</p>
                    <p class="text-sm text-gray-500">Redirecting to confirmation page...</p>
                `;
            }

            showError(message) {
                alert(`Payment Error: ${message}\n\nPlease check your information and try again.`);
            }
        }

        // Initialize checkout form
        document.addEventListener('DOMContentLoaded', () => {
            new CheckoutForm();
        });

        // Prevent navigation during processing
        window.addEventListener('beforeunload', (e) => {
            if (window.checkoutForm && window.checkoutForm.isProcessing) {
                e.preventDefault();
                e.returnValue = 'Payment is being processed. Are you sure you want to leave?';
            }
        });
    </script>
</body>
</html>