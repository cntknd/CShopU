<x-app-layout>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Your Orders</h1>

        <table class="w-full border">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="border-t">
                    <td>{{ $order->id }}</td>
                    <td>
                        @if ($order->items->count())
                            <ul class="list-disc ml-4">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        @else
                            No items found
                        @endif
                    </td>
                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->created_at->format('F d, Y') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td class="space-x-2">
                        @if($order->status === 'pending')
                            <a href="{{ route('orders.edit', $order->id) }}" class="text-blue-600 hover:underline">Edit</a>

                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-600 hover:underline">Cancel</button>
                            </form>
                        @else
                            <span class="text-gray-500">N/A</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
