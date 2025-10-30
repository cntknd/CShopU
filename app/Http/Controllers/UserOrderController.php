<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.orders', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load the order with its items and products
        $order->load('items.product', 'user');

        return view('users.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow editing pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('user.orders.index')
                ->with('error', 'Only pending orders can be edited.');
        }

        $order->load('items.product');
        return view('users.orders.edit', compact('order'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow updating pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('user.orders.index')
                ->with('error', 'Only pending orders can be updated.');
        }

        $quantities = $request->input('quantities', []);
        $total = 0;

        foreach ($order->items as $item) {
            if (isset($quantities[$item->id])) {
                $newQty = $quantities[$item->id];
                $productStock = $item->product->stock;

                // Check stock limit
                if ($newQty > $productStock) {
                    return redirect()->back()->with('error', 
                        "Cannot set quantity for {$item->product->name} higher than available stock ({$productStock}).");
                }

                $item->quantity = $newQty;
                $item->save();

                $total += $item->price * $item->quantity;
            }
        }

        $order->total_price = $total;
        $order->save();

        return redirect()->route('user.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow canceling pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('user.orders.index')
                ->with('error', 'Only pending orders can be canceled.');
        }

        $order->cancelOrder();

        return redirect()->route('user.orders.index')
            ->with('success', 'Order canceled successfully.');
    }

    /**
     * Print payslip for the specified order.
     */
    public function printPayslip(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow printing payslips for confirmed or completed orders
        if (!in_array($order->status, ['confirmed', 'completed'])) {
            abort(403, 'Payslip is only available for confirmed or completed orders.');
        }

        $order->load('items.product', 'user');
        $generatedAt = now()->setTimezone(config('app.timezone'));

        return view('users.orders.payslip', [
            'order' => $order,
            'download' => true,
            'generated_at' => $generatedAt,
        ]);
    }

    /**
     * Download payslip for the specified order.
     */
    public function downloadPayslip(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow downloading payslips for confirmed or completed orders
        if (!in_array($order->status, ['confirmed', 'completed'])) {
            abort(403, 'Payslip is only available for confirmed or completed orders.');
        }

        $order->load('items.product', 'user');
        $generatedAt = now()->setTimezone(config('app.timezone'));

        return view('users.orders.payslip', [
            'order' => $order,
            'download' => true,
            'generated_at' => $generatedAt,
        ]);
    }
}
