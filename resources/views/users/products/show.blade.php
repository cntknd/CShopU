@extends('layouts.Users.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="max-w-6xl mx-auto px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="w-full bg-gray-50 flex items-center justify-center h-64 md:h-auto">
                <img src="{{ asset('images/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
            </div>

            <div class="p-8">
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $product->name }}</h1>
                <div class="mt-4 text-xl text-red-700 font-bold">₱{{ number_format($product->price, 2) }}</div>
                <div class="mt-3 text-sm text-gray-600">Stock: <span class="font-semibold">{{ $product->stock }}</span></div>

                <p class="mt-6 text-gray-700">{!! nl2br(e($product->description ?? 'No description available.')) !!}</p>

                <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    @if($product->stock > 0)
                        {{-- use the correctly namespaced route for user cart add (POST) --}}
                        <form action="{{ route('user.cart.add', $product->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center gap-2 justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="w-full sm:w-auto bg-gray-200 text-gray-500 py-2 px-4 rounded" disabled>Out of Stock</button>
                    @endif

                    <a href="{{ route('user.products.index') }}" class="text-sm text-gray-600 hover:underline">← Back to products</a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
