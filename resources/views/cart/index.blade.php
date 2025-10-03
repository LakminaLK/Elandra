@extends('layouts.frontend')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    /* Cart Page Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-fade-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .animate-slide-left {
        animation: slideInLeft 0.6s ease-out;
    }
    
    .animate-slide-right {
        animation: slideInRight 0.6s ease-out;
    }
    
    .animate-scale-in {
        animation: scaleIn 0.5s ease-out;
    }
    
    .animate-bounce-in {
        animation: bounceIn 0.6s ease-out;
    }
    
    .animate-delay-200 {
        animation-delay: 0.2s;
        animation-fill-mode: both;
    }
    
    .animate-delay-400 {
        animation-delay: 0.4s;
        animation-fill-mode: both;
    }
    
    .animate-delay-600 {
        animation-delay: 0.6s;
        animation-fill-mode: both;
    }
    
    /* Hover effects for cart items */
    .cart-item {
        transition: all 0.3s ease;
    }
    
    .cart-item:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Enhanced remove button styling */
    .remove-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .remove-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .remove-btn:hover::before {
        left: 100%;
    }
    
    .remove-btn:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }
    
    /* Confirmation popup styles */
    .confirmation-popup {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .confirmation-popup.show {
        opacity: 1;
    }
    
    .confirmation-modal {
        background: white;
        border-radius: 16px;
        padding: 32px;
        max-width: 400px;
        margin: 20px;
        transform: scale(0.7);
        transition: transform 0.3s ease;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }
    
    .confirmation-popup.show .confirmation-modal {
        transform: scale(1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-up">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
            <nav class="text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900 font-medium">Cart</span>
            </nav>
        </div>

        @livewire('shopping-cart')
    </div>
</div>

@push('scripts')
<script>
// Listen for Livewire events
document.addEventListener('livewire:initialized', () => {
    // Listen for item removed event
    Livewire.on('item-removed', (event) => {
        // Refresh the page after a short delay to show updated cart
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });
    
    // Listen for cart updated event
    Livewire.on('cart-updated', (event) => {
        // Refresh cart totals component
        Livewire.dispatch('$refresh');
    });
});

// Alternative event listener for older Livewire versions
document.addEventListener('DOMContentLoaded', function() {
    window.livewire?.on('item-removed', () => {
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });
    
    window.livewire?.on('cart-updated', () => {
        // Force refresh of cart totals
        if (window.Livewire) {
            window.Livewire.emit('refreshComponent');
        }
    });
});
</script>
@endpush

@endsection