@extends('layouts.Users.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('build/bootstrap/bootstrap.v5.3.2.min.css') }}" />

<style>
    body {
        background-color: #f5f5f5;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    /* Fix navbar z-index to stay on top */
    header, nav, .navbar {
        position: relative;
        z-index: 1000 !important;
    }

    /* Custom Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination .page-item {
        display: inline-block;
    }

    .pagination .page-link {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        color: #374151;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
    }

    .pagination .page-link:hover {
        background-color: #f3f4f6;
    }

    .pagination .page-item.active .page-link {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
        font-weight: 500;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination .page-link[rel="prev"]:before {
        content: "‹";
        font-size: 1.25rem;
        font-weight: bold;
    }

    .pagination .page-link[rel="next"]:before {
        content: "›";
        font-size: 1.25rem;
        font-weight: bold;
    }

    .pagination .page-link[rel="prev"] svg,
    .pagination .page-link[rel="next"] svg {
        display: none;
    }
</style>

<!-- Modern & Clean Product View -->
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-gray-900 text-center mb-8">
        Explore Our <span class="text-blue-600">Uniforms & Items</span>
    </h1>

    <!-- Filter Section -->
    <div class="flex justify-center mb-10">
        <div class="bg-white rounded-lg px-8 py-4 shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('user.products.index') }}" class="flex flex-wrap justify-center gap-x-6 gap-y-3 text-sm font-medium text-gray-700">
                <button type="submit" name="category" value="" class="hover:text-blue-600 transition {{ !request('category') ? 'text-blue-600 font-semibold' : '' }}">All</button>
                @foreach($categories as $category)
                    <button type="submit" name="category" value="{{ $category->id }}" class="hover:text-blue-600 transition {{ request('category') == $category->id ? 'text-blue-600 font-semibold' : '' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </form>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-200">

            <!-- Product Image -->
            <div class="relative w-full bg-gray-50" style="padding-top: 100%;">
                <img 
                    src="{{ asset('images/'.$product->image) }}" 
                    alt="{{ $product->name }}" 
                    class="absolute inset-0 w-full h-full object-cover"
                    style="object-fit: cover;"
                />
                @php
                    $totalStock = $product->sizes->count() > 0 
                        ? $product->sizes->sum('stock')
                        : $product->stock;
                @endphp
                @if($totalStock <= 0)
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <span class="bg-white text-gray-800 text-xs px-3 py-1 rounded font-semibold">Out of Stock</span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h2 class="text-sm font-normal text-gray-900 mb-1 line-clamp-2" style="min-height: 2.5rem;">{{ $product->name }}</h2>
                
                <!-- Price -->
                <div class="mb-2">
                    <span class="text-base font-semibold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                </div>

                <!-- Stock Display -->
                <div class="mb-3 flex items-center text-sm">
                    @php
                        $totalStock = $product->sizes->count() > 0 
                            ? $product->sizes->sum('stock')
                            : $product->stock;
                    @endphp
                    <span class="mr-1 {{ $totalStock > 0 ? ($totalStock <= 5 ? 'text-orange-600' : 'text-green-600') : 'text-red-600' }}">
                        <i class="bi bi-circle-fill text-xs"></i>
                    </span>
                    <span class="text-gray-600">
                        @if($totalStock > 0)
                            {{ $totalStock }} pieces available
                        @else
                            Out of stock
                        @endif
                    </span>
                </div>

                <!-- Add to Cart Button -->
                @if($totalStock > 0)
                    <form action="{{ route('user.cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 text-sm"
                        >
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button 
                        class="w-full py-2.5 bg-gray-200 text-gray-500 font-medium rounded-md cursor-not-allowed text-sm"
                        disabled
                    >
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if(method_exists($products, 'links'))
        <div class="mt-12">
            {{ $products->appends(['category' => request('category')])->links() }}
        </div>
    @endif
</div>

<!-- Modern Footer -->
<footer class="mt-16 bg-white border-t border-gray-200 py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <p class="text-lg font-semibold text-gray-800 mb-4">
            STAY UPDATED <span class="text-blue-600">| CAGAYAN STATE UNIVERSITY</span>
        </p>

        <div class="flex justify-center flex-wrap gap-8 text-gray-700 font-medium mb-6 text-sm">
             <a href="{{ route('citizens-charter') }}">Citizens Charter</a> |
            <a href="{{ route('payment') }}" class="hover:text-blue-600 transition duration-200">Payment</a> |
            <a href="{{ route('contact-us') }}" class="hover:text-blue-600 transition duration-200">Contact Us</a>
        </div>

        <!-- Social Media Icons -->
        <div class="social my-2">
            <a href="https://www.facebook.com/CSUABAO" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="https://instagram.com/csukomyu/" target="_blank"><i class="bi bi-instagram"></i></a>
            <a href="mailto:cshopu@csu.edu.ph"><i class="bi bi-envelope"></i></a>
        </div>

        <p class="text-gray-700 font-medium mb-1 text-sm">Empowering Students Through Innovation and Technology.</p>
        <p class="text-xs text-gray-500">&copy; 2025 CShopU — Official Web Store of Cagayan State University Aparri Campus</p>
    </div>
</footer>

@endsection