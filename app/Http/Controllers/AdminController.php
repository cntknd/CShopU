<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all products and sales summary
        $products = Product::all();
        // Add sales summary and transaction history logic here
        return view('admin.dashboard', compact('products'));
    }

    public function createProduct()
    {
        $departments = Department::all();
        return view('admin.create_product', compact('departments'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'caption' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'department_id' => 'required|exists:departments,id',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->caption = $request->caption;
        $product->price = $request->price;
        $product->department_id = $request->department_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();
        return redirect()->route('admin.dashboard');
    }

    // Add methods for edit and delete
}

