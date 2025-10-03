@extends('layouts.frontend')

@section('title', $product->name . ' - Product Details')

@section('content')
@livewire('product-show', ['productId' => $product->_id])
@endsection