@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
    <h2 class="text-xl font-bold mb-4 text-center">🧾 Order Receipt</h2>

    <p><strong>Order #:</strong> {{ $order->id }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y - h:i A') }}</p>
    <p><strong>Customer:</strong> {{ $order->user->name ?? 'Guest' }}</p>
    <hr class="my-4">

    <table class="w-full text-sm text-left mb-4">
        <thead class="border-b">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Size</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr class="border-b">
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->size  }}</td>
                <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    
    <div class="text-center my-4">
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
        🖨️ Print Receipt
    </button>
</div>


</div>
@endsection
