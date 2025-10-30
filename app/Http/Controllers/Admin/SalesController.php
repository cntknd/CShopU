<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class SalesController extends Controller
{
    public function index()
    {
        return $this->overview();
    }

    public function overview()
    {
        // ✅ Total Sales (Confirmed and Completed Orders)
        $totalSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['confirmed', 'completed'])
            ->sum(DB::raw('order_items.quantity * order_items.price'));

        // ✅ Total Orders
        $totalOrders = Order::count();

        // ✅ Order Status Counts
        $orderStatusCount = [
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'rejected'  => Order::where('status', 'rejected')->count(),
        ];

        // ✅ Top-Selling Products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereIn('orders.status', ['confirmed', 'completed'])
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ✅ Monthly Sales Chart (based on order_items + order dates)
        $monthlySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['confirmed', 'completed'])
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ✅ Sales by Category
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('products.category', DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
            ->whereIn('orders.status', ['confirmed', 'completed'])
            ->groupBy('products.category')
            ->orderByDesc('total_sales')
            ->get();

            $productSales = \DB::table('order_items')
    ->join('products', 'order_items.product_id', '=', 'products.id')
    ->select('products.name', \DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
    ->groupBy('products.name')
    ->orderByDesc('total_sales')
    ->get();


        return view('admin.sales.overview', compact(
            'totalSales',
            'totalOrders',
            'orderStatusCount',
            'topProducts',
            'monthlySales',
            'salesByCategory'
        ));
    }

    public function salesReport(Request $request)
    {
        // Get date range from request (default to current month)
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // Get all products
        $products = DB::table('products')
            ->select('id', 'name', 'price', 'supplier_price', 'stock', 'has_size')
            ->orderBy('name')
            ->get();

        $reportData = [];

        foreach ($products as $product) {
            // Get sales data for this product in the date range
            $salesData = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.product_id', $product->id)
                ->whereIn('orders.status', ['confirmed', 'completed'])
                ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->select(
                    DB::raw('SUM(order_items.quantity) as total_qty'),
                    DB::raw('AVG(order_items.price) as avg_price'),
                    DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
                )
                ->first();

            // Calculate beginning balance (ending + sales)
            $salesQty = $salesData->total_qty ?? 0;
            $endingBalance = $product->stock;
            $beginningBalance = $endingBalance + $salesQty;

            // Get size information if product has sizes
            if ($product->has_size) {
                $sizes = DB::table('product_sizes')
                    ->where('product_id', $product->id)
                    ->get();

                foreach ($sizes as $size) {
                    // Get sales for this specific size
                    $sizeSalesData = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('order_items.product_id', $product->id)
                        ->where('order_items.size', $size->size_name)
                        ->whereIn('orders.status', ['confirmed', 'completed'])
                        ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                        ->select(
                            DB::raw('SUM(order_items.quantity) as total_qty'),
                            DB::raw('AVG(order_items.price) as avg_price'),
                            DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
                        )
                        ->first();

                    $sizeSalesQty = $sizeSalesData->total_qty ?? 0;
                    $sizeEnding = $size->stock;
                    $sizeBeginning = $sizeEnding + $sizeSalesQty;

                    $reportData[] = [
                        'item' => $product->name . ' (Size: ' . $size->size_name . ')',
                        'beginning_qty' => $sizeBeginning,
                        'unit_cost' => $product->supplier_price ?? 0,
                        'beginning_total' => $sizeBeginning * ($product->supplier_price ?? 0),
                        'purchases_qty' => 0,
                        'purchases_unit_cost' => $product->supplier_price ?? 0,
                        'purchases_total' => 0,
                        'sales_qty' => $sizeSalesQty,
                        'sales_unit_cost' => $product->supplier_price ?? 0,
                        'sales_total' => $sizeSalesQty * ($product->supplier_price ?? 0),
                        'ending_qty' => $sizeEnding,
                        'ending_unit_cost' => $product->supplier_price ?? 0,
                        'ending_total' => $sizeEnding * ($product->supplier_price ?? 0),
                    ];
                }
            } else {
                // Product without size
                $reportData[] = [
                    'item' => $product->name,
                    'beginning_qty' => $beginningBalance,
                    'unit_cost' => $product->supplier_price ?? 0,
                    'beginning_total' => $beginningBalance * ($product->supplier_price ?? 0),
                    'purchases_qty' => 0,
                    'purchases_unit_cost' => $product->supplier_price ?? 0,
                    'purchases_total' => 0,
                    'sales_qty' => $salesQty,
                    'sales_unit_cost' => $product->supplier_price ?? 0,
                    'sales_total' => $salesQty * ($product->supplier_price ?? 0),
                    'ending_qty' => $endingBalance,
                    'ending_unit_cost' => $product->supplier_price ?? 0,
                    'ending_total' => $endingBalance * ($product->supplier_price ?? 0),
                ];
            }
        }

        return view('admin.sales.report', compact('reportData', 'startDate', 'endDate'));
    }
}
