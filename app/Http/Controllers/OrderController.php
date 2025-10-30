<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->get();

        return view('users.orders', compact('orders'));
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // Optional: Check ownership
        if ($order->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->status = 'canceled';
        $order->save();

        return redirect()->back()->with('success', 'Order canceled successfully.');
    }

    public function confirmAndNotify($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $order->confirmOrder();
        return "Order confirmation email sent to: " . $order->user->email;
    }

    public function edit($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Only pending orders can be edited.');
        }

        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Only pending orders can be updated.');
        }

        $quantities = $request->input('quantities', []);
        $total = 0;

        foreach ($order->items as $item) {
            if (isset($quantities[$item->id])) {
                $newQty = $quantities[$item->id];
                $productStock = $item->product->stock;

                // Check stock limit
                if ($newQty > $productStock) {
                    return redirect()->back()->with('error', "Cannot set quantity for {$item->product->name} higher than available stock ({$productStock}).");
                }

                $item->quantity = $newQty;
                $item->save();

                $total += $item->price * $item->quantity;
            }
        }

        $order->total_price = $total;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function printPayslip($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Check if the order belongs to the authenticated user and is confirmed
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($order->status, ['confirmed', 'completed'])) {
            abort(403, 'Payslip is only available for confirmed orders');
        }

        // open a printable version (auto-print) when the print route is used
        $generatedAt = now()->setTimezone(config('app.timezone'));
        return view('users.orders.payslip', [
            'order' => $order,
            'download' => true,
            'generated_at' => $generatedAt,
        ]);
    }

    public function downloadPayslip($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Check if the order belongs to the authenticated user and is confirmed
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($order->status, ['confirmed', 'completed'])) {
            abort(403, 'Payslip is only available for confirmed orders');
        }

        // Return the payslip view with a special flag for download
        $generatedAt = now()->setTimezone(config('app.timezone'));
        return view('users.orders.payslip', [
            'order' => $order,
            'download' => true,
            'generated_at' => $generatedAt,
        ]);
    }
}