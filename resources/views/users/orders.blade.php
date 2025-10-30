@extends('layouts.Users.app')

@section('content')
<div class="max-w-5xl mx-auto p-4 sm:p-8 mt-10 mb-20">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">My Orders</h1>

    {{-- 📢 Payment Notice --}}
    @if($orders->where('status', 'confirmed')->whereNull('paid_at')->count() > 0)
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-yellow-800">⚠️ Payment Required</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        You have {{ $orders->where('status', 'confirmed')->whereNull('paid_at')->count() }} confirmed order(s) awaiting payment. 
                        <strong>Please pay within 24 hours of confirmation or your order will be automatically cancelled.</strong>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- 🔍 Search Bar --}}
    <div class="mb-6">
        <div class="relative">
            <input type="text" placeholder="Search by order number or product"
                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-gray-700 placeholder-gray-400 text-base shadow-sm"
                   id="orderSearch">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    {{-- 🧾 Orders Table --}}
    @if($orders->count())
        <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-100 p-2">
            <table class="w-full table-auto border-collapse" id="ordersTable">
                <thead>
                    <tr class="text-gray-600 text-sm uppercase tracking-wider border-b border-gray-200">
                        <th class="px-4 py-3 text-left">Order #</th>
                        <th class="px-4 py-3 text-left">Items</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Payment</th>
                        <th class="px-4 py-3 text-center">Payslip</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="order-row border-b border-gray-100 hover:bg-gray-50 transition duration-200">
                            {{-- Order Number --}}
                            <td class="px-4 py-4 font-mono text-gray-800 font-semibold text-sm">
                                {{ $order->id }}
                            </td>
                            {{-- Items --}}
                            <td class="px-4 py-4">
                                <ul class="list-disc list-inside space-y-1 text-sm text-gray-800">
                                    @forelse ($order->items as $item)
                                        <li>
                                            <span class="item-name">{{ $item->product->name ?? '❌ Not found' }}</span>
                                            <span class="text-xs text-gray-500">(Size: {{ $item->size }}, Qty: {{ $item->quantity }})</span>
                                        </li>
                                    @empty
                                        <li class="text-gray-400 italic">No items found.</li>
                                    @endforelse
                                </ul>
                            </td>
                            {{-- Total --}}
                            <td class="px-4 py-4 text-green-600 font-bold">
                                ₱{{ number_format((float) $order->total_price, 2) }}
                            </td>
                            {{-- Date --}}
                            <td class="px-4 py-4 text-gray-600 text-sm">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            {{-- Status --}}
                            <td class="px-4 py-4 text-center">
                                <div class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                    @if ($order->status === 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @elseif ($order->status === 'confirmed')
                                        bg-green-100 text-green-800
                                    @elseif ($order->status === 'cancelled' || $order->status === 'canceled')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords($order->status ?? 'Processing') }}
                                </div>
                            </td>
                            {{-- Payment Status --}}
                            <td class="px-4 py-4 text-center">
                                @if($order->status === 'confirmed')
                                    @php
                                        $timeRemaining = $order->getTimeRemainingToPay();
                                        $deadline = $order->confirmed_at ? $order->confirmed_at->copy()->addHours(24) : null;
                                        $isOverdue = $deadline && $deadline->isPast();
                                    @endphp
                                    @if($order->paid_at)
                                        <div class="inline-block">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Paid
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $order->paid_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    @elseif($isOverdue)
                                        <div class="inline-block">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                                ⚠️ OVERDUE
                                            </span>
                                            <div class="text-xs text-red-600 font-semibold mt-1">
                                                Order will be cancelled
                                            </div>
                                        </div>
                                    @else
                                        <div class="inline-block">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                ⏰ Unpaid
                                            </span>
                                            <div class="text-xs text-gray-600 font-semibold mt-1">
                                                {{ $timeRemaining ?? 'Check payment' }}
                                            </div>
                                        </div>
                                    @endif
                                @elseif($order->status === 'completed')
                                    <div class="inline-block">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Completed
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            {{-- Payslip Actions --}}
                            <td class="px-4 py-4 text-center">
                                @if($order->status === 'confirmed' || $order->status === 'completed')
                                    <div class="flex items-center justify-center space-x-2">
                                        {{-- Print Button --}}
                                        <button onclick="window.open('{{ route('user.orders.print-payslip', $order->id) }}', '_blank')" 
                                                class="p-2 text-gray-600 hover:text-gray-900 transition-colors duration-200"
                                                title="Print Payslip">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                        </button>
                                        {{-- Download Button --}}
                                        <a href="{{ route('user.orders.download-payslip', $order->id) }}" 
                                           class="p-2 text-gray-600 hover:text-gray-900 transition-colors duration-200"
                                           title="Download Payslip">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Not available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Empty Orders Message --}}
        <div class="text-center py-12 bg-white p-6 rounded-lg shadow-md border border-gray-100">
            <p class="text-gray-600 text-xl font-medium">🛒 You have no orders yet.</p>
            <a href="{{ route('user.products.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700 font-semibold transition">
                <span class="mr-1">←</span> Continue Shopping
            </a>
        </div>
    @endif
</div>

<style>
    /* Animation for overdue warning */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

{{-- 🔎 Search Functionality Only --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('orderSearch');
    const orderRows = document.querySelectorAll('.order-row'); 

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toUpperCase().trim();
            const isFilterNumeric = !isNaN(parseInt(filter)) && filter.length > 0;

            orderRows.forEach(row => {
                const orderNumberCell = row.cells[0];
                const itemNamesCell = row.cells[1];
                
                const orderIdText = orderNumberCell ? orderNumberCell.textContent.trim().toUpperCase() : '';
                let isMatch = false;

                if (filter.length === 0) {
                    isMatch = true;
                } else if (isFilterNumeric && orderIdText === filter) {
                    isMatch = true;
                }

                if (!isMatch) {
                    const itemNames = itemNamesCell.querySelectorAll('.item-name');
                    itemNames.forEach(itemNameElement => {
                        const productName = itemNameElement.textContent.trim().toUpperCase();
                        if (productName.indexOf(filter) > -1) {
                            isMatch = true;
                        }
                    });
                }

                row.style.display = isMatch ? "" : "none";
            });
        });
    }
});
</script>
@endsection
