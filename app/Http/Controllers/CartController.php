<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $orders = $user->orders()
                       ->where('status', '!=', 'cancelled')
                       ->with('orderItems.product')
                       ->get();

        return view('users.orders.index', compact('orders'));
    }

    public function viewCart()
    {
        $cart = session('cart', []);
        return view('users.cart', compact('cart'));
    }

    public function removeItem($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item removed from cart.');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $size = $request->input('size'); // Get selected size

        // Use a unique key for product + size
        $cartKey = $id . '_' . ($size ?? 'default');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                'has_size' => $product->has_size,
                'size' => $size,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('user.cart.view')->with('success', 'Product added to cart!');
    }

    public function updateQuantity(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $item = $cart[$key];
        $product = Product::find($item['product_id']);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Check stock by size if applicable
        if (!empty($item['has_size']) && !empty($item['size'])) {
            $stock = ProductSize::where('product_id', $item['product_id'])
                ->where('size_name', $item['size'])
                ->value('stock') ?? 0;
        } else {
            $stock = $product->stock;
        }

        if ($request->action === 'increment') {
            if ($cart[$key]['quantity'] + 1 > $stock) {
                return redirect()->back()->with('error', 'Not enough stock!');
            }
            $cart[$key]['quantity']++;
        } elseif ($request->action === 'decrement') {
            if ($cart[$key]['quantity'] > 1) {
                $cart[$key]['quantity']--;
            }
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function checkStock($productId, $sizeName)
    {
        return ProductSize::where('product_id', $productId)
            ->where('size_name', $sizeName)
            ->value('stock') ?? 0;
    }

    public function updateSize(Request $request)
    {
        $oldCartKey = $request->cart_key;
        $productId = $request->product_id;
        $newSize = $request->size;
        
        $cart = session()->get('cart', []);

        if (!isset($cart[$oldCartKey])) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $item = $cart[$oldCartKey];
        $quantity = $item['quantity'];
        
        // Remove the old cart item
        unset($cart[$oldCartKey]);
        
        // Create new key for the new size
        $newCartKey = $productId . '_' . ($newSize ?? 'default');
        
        // Check if an item with this product+size combination already exists
        if (isset($cart[$newCartKey])) {
            // If it exists, increment the quantity by the old quantity
            $cart[$newCartKey]['quantity'] += $quantity;
        } else {
            // Create a new cart item with the new size
            $cart[$newCartKey] = [
                'product_id' => $productId,
                'name' => $item['name'],
                'quantity' => $quantity,
                'price' => $item['price'],
                'image' => $item['image'],
                'has_size' => $item['has_size'],
                'size' => $newSize,
            ];
        }

        session()->put('cart', $cart);
        
        $stock = $this->checkStock($productId, $newSize);

        return response()->json([
            'success' => true,
            'stock' => $stock,
            'quantity' => $cart[$newCartKey]['quantity'],
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $item) {
            if (!empty($item['has_size'])) {
                if (empty($item['size'])) {
                    return redirect()->back()->with('error', 'Please select a size for all items.');
                }
                $stock = $this->checkStock($item['product_id'], $item['size']);
                if ($item['quantity'] > $stock) {
                    return redirect()->back()->with('error', 'Not enough stock for ' . $item['name']);
                }
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
        ]);

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'supplier_price' => $product->supplier_price ?? $item['price'],
                'size' => $item['size'] ?? null,
            ]);

            if (!empty($item['has_size']) && !empty($item['size'])) {
                $productSize = ProductSize::where('product_id', $item['product_id'])
                    ->where('size_name', $item['size'])
                    ->first();

                if ($productSize) {
                    $productSize->stock -= $item['quantity'];
                    $productSize->save();
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }
        }

        session()->forget('cart');
        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }
}
