@extends('layouts.frontend')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-white">
    <!-- Page Header -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="mt-2 text-gray-600">Review your selected items before checkout</p>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @livewire('shopping-cart')
    </div>
</div>
@endsection