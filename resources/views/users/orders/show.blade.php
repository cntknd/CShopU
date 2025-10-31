@extends('layouts.Users.app')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-8 mt-10 mb-20">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            ← Back to Orders
        </a>
    </div>

    <!-- Order Status Alert -->
    @if($order->status === 'confirmed' && !$order->paid_at)
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-yellow-800">⚠️ Payment Required</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        This order is confirmed and awaiting payment. 
                        <strong>Please pay within 24 hours or your order will be automatically cancelled.</strong>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Order Information Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Summary -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-semibold">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Date:</span>
                        <span class="font-semibold">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    @if($order->confirmed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Confirmed At:</span>
                        <span class="font-semibold">{{ $order->confirmed_at->format('M d, Y g:i A') }}</span>
                    </div>
                    @endif
                    @if($order->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paid At:</span>
                        <span class="font-semibold">{{ $order->paid_at->format('M d, Y g:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Information</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold">{{ $order->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold">{{ $order->user->email }}</span>
                    </div>
                    @if($order->user->student_id)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Student ID:</span>
                        <span class="font-semibold">{{ $order->user->student_id }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Order Items</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Size</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Quantity</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Price</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-cover rounded-lg mr-3">
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $item->product->name }}</div>
                                    @if($item->product->category)
                                        <div class="text-sm text-gray-500">{{ $item->product->category }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($item->size)
                                <span class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $item->size }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center font-semibold">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-center">₱{{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="4" class="px-4 py-3 text-right font-bold text-lg">Total Amount:</td>
                        <td class="px-4 py-3 text-right font-bold text-lg text-red-600">₱{{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4 justify-center">
        @if($order->status === 'pending')
            <a href="{{ route('user.orders.edit', $order->id) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                Edit Order
            </a>
            <form method="POST" action="{{ route('user.orders.cancel', $order->id) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                        onclick="return confirm('Are you sure you want to cancel this order?')">
                    Cancel Order
                </button>
            </form>
        @endif

        @if(in_array($order->status, ['confirmed', 'completed']))
            <a href="{{ route('user.orders.print-payslip', $order->id) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                Print Payslip
            </a>
            <a href="{{ route('user.orders.download-payslip', $order->id) }}" 
               class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition-colors">
                Download Payslip
            </a>
        @endif
    </div>
</div>
@endsection
