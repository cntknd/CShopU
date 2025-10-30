<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderConfirmed;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    
    // Auto-cancel confirmed orders that are overdue (not paid within 24 hours)
    $overdueOrders = Order::where('status', 'confirmed')
        ->whereNull('paid_at')
        ->where('confirmed_at', '<', Carbon::now()->subHours(24))
        ->get();

    foreach ($overdueOrders as $ov) {
        // Use the model helper which restores stock and marks as cancelled
        try {
            $ov->cancelOrder();
        } catch (\Exception $e) {
            // Log and continue
            \Log::error('Failed to auto-cancel overdue order: '.$ov->id.' - '.$e->getMessage());
        }
    }
    
    $orders = Order::with('items.product', 'user')
        ->when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'LIKE', "%{$search}%");
                      })
                      // Search orders by product name inside items relationship
                      ->orWhereHas('items.product', function($q3) use ($search) {
                          $q3->where('name', 'LIKE', "%{$search}%");
                      });
                });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.orders.index', compact('orders', 'search'));
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // If status was changed to confirmed, send notification
        if ($order->status === 'confirmed') {
            // Use the notification directly in case other logic isn't desired
            $order->user->notify(new OrderConfirmed($order));
        }

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function edit($id)
{
    $order = Order::with(['items.product'])->findOrFail($id);

    if ($order->status !== 'pending') {
        return redirect()->route('orders.index')->with('error', 'Only pending orders can be edited.');
    }

    return view('orders.edit', compact('order'));
}

public function confirm($id)
{
    $order = Order::findOrFail($id);

    // Use the model helper which sets status and sends the notification
    $order->confirmOrder();

    // After confirming, redirect to the receipt (payslip) so admin can print it
    return redirect()->route('admin.orders.receipt', $order->id)->with('success', 'Order confirmed successfully.');
}


public function complete($id)
{
    $order = Order::findOrFail($id);
    $order->status = 'completed';
    $order->save();

    return redirect()->route('admin.orders.index')->with('success', 'Order marked as completed.');
}

public function markAsPaid($id)
{
    $order = Order::findOrFail($id);
    $order->markAsPaid();

    return redirect()->route('admin.orders.index')->with('success', 'Order marked as paid.');
}


public function reject(Order $order)
{
    $order->update(['status' => 'rejected']);
    return back()->with('error', 'Order rejected.');
    
}

public function generateReceipt($orderId)
{
    $order = Order::with('orderItems.product', 'user')->findOrFail($orderId);

    if (!in_array($order->status, ['confirmed', 'completed'])) {
        return redirect()->back()->with('error', 'Receipt is available only for confirmed or completed orders.');
    }

    return view('admin.orders.receipt', compact('order'));
}

public function getNewOrderCount()
{
    $count = \App\Models\Order::where('status', 'pending')->count();

    return response()->json(['count' => $count]);
}





}
