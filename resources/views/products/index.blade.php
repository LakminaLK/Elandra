@extends('layouts.frontend')

@section('title', 'Products')

@section('content')
<div class="min-h-screen bg-gray-50">
    

    <!-- Products Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Product Catalog Component -->
        @livewire('product-catalog')
    </div>
</div>
@endsection