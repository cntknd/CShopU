<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function data()
    {
        $today = Carbon::today();

        $dailyOrders = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalSales = Order::where('status', 'completed')->sum('total_amount');

        $ordersByProduct = OrderItem::select('product_name as product', DB::raw('COUNT(*) as count'))
            ->groupBy('product_name')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'daily_orders' => $dailyOrders,
            'total_sales' => $totalSales,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'orders_by_product' => $ordersByProduct,
        ]);
    }
}
