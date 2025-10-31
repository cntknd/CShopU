@extends('layouts.Users.app')

@section('content')
{{-- The outer padding/container for the entire page --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-20">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if(count($cart) > 0)
        {{-- Main Cart Grid: Left (Items) and Right (Summary) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- COLUMN 1: Cart Items List (2/3 width on large screens) --}}
            <div class="lg:col-span-2 space-y-4">
                @php 
                    $grandTotal = 0; 
                    // Determine if any item has size to show the select fields
                    $hasAnySize = collect($cart)->contains('has_size', true); 
                @endphp

                @foreach($cart as $cartKey => $item)
                    @php 
                        $total = $item['price'] * $item['quantity']; 
                        $grandTotal += $total;
                        
                        // Extract product ID from cart key (format: "productId_size")
                        $productId = $item['product_id'];
                        $product = \App\Models\Product::find($productId);
                        if (isset($item['has_size']) && $item['has_size'] && isset($item['size'])) {
                            $stock = \App\Models\ProductSize::where('product_id', $productId)
                                ->where('size_name', $item['size'])
                                ->value('stock') ?? 0;
                        } else {
                            $stock = $product ? $product->stock : 0;
                        }
                        $isOutOfStock = $stock <= 0;
                    @endphp

                    {{-- Individual Cart Item Card (Matches Image Design) --}}
                    <div class="cart-item flex flex-col sm:flex-row p-4 bg-white rounded-lg shadow-md border border-gray-100" data-product-id="{{ $productId }}" data-cart-key="{{ $cartKey }}">
                        {{-- Product Image --}}
                        <img src="{{ asset('images/' . $item['image']) }}" 
                            alt="{{ $item['name'] }}" 
                            class="w-full sm:w-24 h-auto sm:h-24 object-cover rounded-md flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">

                        <div class="flex-grow flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            {{-- Item Details and Price --}}
                            <div class="flex flex-col">
                                <span class="text-lg font-medium text-gray-900">{{ $item['name'] }}</span>
                                <span class="text-base text-gray-700">₱{{ number_format($item['price'], 2) }}</span>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-4 mt-3 sm:mt-0">
                                {{-- Size Dropdown (Only if the product has size options) --}}
                                @if(isset($item['has_size']) && $item['has_size'])
                                    <div class="w-full sm:w-32">
                                        <select name="sizes[{{ $cartKey }}]" 
                                                    class="size-select text-sm border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm block w-full px-2 py-1 {{ $isOutOfStock ? 'border-red-500' : '' }}" 
                                                    data-product-id="{{ $productId }}"
                                                    data-cart-key="{{ $cartKey }}"
                                                    form="checkout-form" 
                                                    required>
                                                <option value="" disabled {{ !isset($item['size']) || empty($item['size']) ? 'selected' : '' }}>Size</option>
                                                @php
                                                    $availableSizes = \App\Models\ProductSize::where('product_id', $productId)->get();
                                                @endphp
                                                @foreach($availableSizes as $sizeOption)
                                                    <option value="{{ $sizeOption->size_name }}" 
                                                            {{ (isset($item['size']) && $item['size'] == $sizeOption->size_name) ? 'selected' : '' }}>
                                                        {{ $sizeOption->size_name }}
                                                    </option>
                                                @endforeach
                                        </select>
                                        <div class="stock-warning text-xs mt-1 hidden">
                                            @if(!isset($item['size']) || empty($item['size']))
                                                <span class="text-red-700 font-medium">Size required</span>
                                            @elseif($isOutOfStock)
                                                <span class="text-red-600 font-bold">Out of stock</span>
                                            @elseif($stock < 5)
                                                <span class="text-orange-600 font-medium">Only {{ $stock }} left</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full sm:w-32">
                                        <select class="text-sm border-gray-300 rounded-md shadow-sm block w-full px-2 py-1" disabled>
                                            <option>—</option>
                                        </select>
                                    </div>
                                @endif

                                {{-- Quantity Controls --}}
                                <div class="flex items-center space-x-2 border border-gray-300 rounded-md p-0.5 mt-3 sm:mt-0">
                                    <form action="{{ route('user.cart.updateQuantity', $cartKey) }}" method="POST" class="inline m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="decrement">
                                        <button type="submit" 
                                                    class="text-gray-500 hover:text-gray-700 p-1 rounded transition disabled:opacity-50"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                        </button>
                                    </form>

                                    <span class="text-base font-medium quantity-display w-6 text-center">{{ $item['quantity'] }}</span>

                                    <form action="{{ route('user.cart.updateQuantity', $cartKey) }}" method="POST" class="inline m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="increment">
                                        <button type="submit" 
                                                    class="text-gray-500 hover:text-gray-700 p-1 rounded transition increment-btn disabled:opacity-50"
                                                    {{ $isOutOfStock || $item['quantity'] >= $stock ? 'disabled' : '' }}>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- Remove Button --}}
                                <a href="{{ route('user.cart.removeItem', $cartKey) }}" 
                                    onclick="return confirm('Remove this item from cart?')"
                                    class="text-gray-400 hover:text-red-500 transition duration-150 mt-3 sm:mt-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- COLUMN 2: Order Summary Card (1/3 width on large screens) --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h3>

                    {{-- Subtotal Row (Kept as the only line in this section) --}}
                    <div class="space-y-2 border-b border-gray-200 pb-4 mb-4">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span class="font-medium">₱{{ number_format($grandTotal, 2) }}</span>
                        </div>
                        {{-- REMOVED SHIPPING AND ESTIMATED TAX ROWS --}}
                    </div>

                    {{-- Total Row (Now just the Grand Total / Subtotal) --}}
                    <div class="flex justify-between text-lg font-bold text-gray-900 mb-6">
                        <span>Total</span>
                        <span>₱{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    {{-- Checkout Button --}}
                    <form id="checkout-form" action="{{ route('user.cart.checkout') }}" method="POST" class="space-y-3">
                        @csrf
                        <button type="submit" 
                                id="checkoutBtn"
                                class="checkout-btn w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold text-base transition duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300">
                            Proceed to Checkout
                        </button>
                    </form>

                    {{-- Continue Shopping Button --}}
                    <a href="{{ route('user.products.index') }}" 
                        class="mt-3 w-full inline-block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-semibold text-base transition duration-300 ease-in-out">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        
    @else
        {{-- Empty Cart Message --}}
        <div class="text-center py-12 bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <p class="text-gray-600 text-xl font-medium">Your cart is empty. Ready to find something great?</p>
            <a href="{{ route('user.products.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700 font-semibold transition">
                <span class="mr-1">←</span> Continue Shopping
            </a>
        </div>
    @endif
</div>

{{-- Styles and Scripts: Preserved and Adapted --}}
<style>
    /* New styles for the specific design elements to match the image */
    
    /* Change button colors to match the blue in the image */
    .checkout-btn.bg-red-700 { background-color: #2563eb !important; }
    .checkout-btn.hover\:bg-red-800:hover { background-color: #1d4ed8 !important; }
    
    /* Custom style for the disabled checkout button, adapted for the new blue/gray look */
    .checkout-btn.soldout {
        background-color: #f7f7f7 !important; /* Light neutral background */
        color: #999 !important; /* Muted text */
        border: 1px solid #ddd !important; /* Subtle border */
        cursor: not-allowed !important;
        transform: none !important; /* Disable hover scale */
        box-shadow: none !important; /* Remove shadow */
    }
    
    .checkout-btn.soldout:hover {
        background-color: #f7f7f7 !important;
    }
    
    button:disabled {
        opacity: 0.6; /* Slightly less opaque for better visibility than 0.5 */
        cursor: not-allowed;
    }

    /* Style for the select element when out of stock */
    .size-select.border-red-500 {
        border-color: #f87171 !important;
        box-shadow: 0 0 0 1px #f87171;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Note: The script logic has been kept identical to your original code
    // to preserve all functionality (stock checks, size updates, button disable).
    
    function updateCheckoutButton() {
        const checkoutBtn = document.getElementById('checkoutBtn');
        // const checkoutText = document.getElementById('checkoutText'); // Removed from new HTML
        // const checkoutIcon = document.getElementById('checkoutIcon'); // Removed from new HTML
        
        let hasOutOfStock = false;
        let hasUnselectedSize = false;
        
        document.querySelectorAll('.size-select').forEach(select => {
            const selectedSize = select.value;
            const row = select.closest('.cart-item');
            const stockWarning = row.querySelector('.stock-warning');
            
            if (!selectedSize || selectedSize === '') {
                hasUnselectedSize = true;
            }
            
            // Check the current content of the stock warning for 'Out of stock'
            if (stockWarning && stockWarning.textContent.trim().includes('Out of stock')) {
                hasOutOfStock = true;
            }
        });
        
        if (hasOutOfStock || hasUnselectedSize) {
            checkoutBtn.classList.add('soldout');
            checkoutBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700'); // Updated to use the new blue classes
            checkoutBtn.disabled = true;
            // checkoutText.textContent = hasUnselectedSize ? 'Select Size' : 'Sold Out'; // Removed from new HTML
            // checkoutIcon.style.display = 'none'; // Removed from new HTML
            
        } else {
            checkoutBtn.classList.remove('soldout');
            checkoutBtn.classList.add('bg-blue-600', 'hover:bg-blue-700'); // Updated to use the new blue classes
            checkoutBtn.disabled = false;
            // checkoutText.textContent = 'Proceed to Checkout'; // Removed from new HTML
            // checkoutIcon.style.display = 'inline'; // Removed from new HTML
        }
    }
    
    document.querySelectorAll('.size-select').forEach(select => {
        select.addEventListener('change', function() {
            const productId = this.dataset.productId;
            const cartKey = this.dataset.cartKey;
            const newSize = this.value;
            const row = this.closest('.cart-item');
            
            if (!newSize) {
                // If a size is unselected, we need to call updateCheckoutButton immediately
                const stockWarning = row.querySelector('.stock-warning');
                stockWarning.innerHTML = '<span class="text-red-700 font-medium">Size required</span>';
                this.classList.remove('border-red-500');
                updateCheckoutButton();
                return;
            }
            
            fetch('{{ route("user.cart.updateSize") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    cart_key: cartKey,
                    size: newSize
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload to show the updated cart with separate items for different sizes
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Also attach listeners to your existing quantity forms to update the button
    document.querySelectorAll('form[action*="updateQuantity"]').forEach(form => {
        form.addEventListener('submit', () => {
             // A timeout is used here to allow the server logic to run before checking the button state again
             setTimeout(updateCheckoutButton, 100); 
        });
    });

    updateCheckoutButton();
});
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection