<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Category;

class ProdCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produktomo = Product::with(['sizes', 'category'])->get();
        $categories = Category::all();
        return view('admin.mngprod.index', compact('produktomo', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'pname' => 'required|string|max:255',
        'desc' => 'nullable|string',
        'price' => 'required|numeric',
        'supplier_price' => 'required|numeric',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'has_size' => 'required|boolean',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'nullable|integer|min:0',
        'sizes' => 'nullable|array',
        'sizes.*' => 'integer|min:0',
    ]);

    try {
        $path = $request->file('image')->store('images', 'public');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
    }
    $path = $request->file('image')->store('images', 'public');
    $filename = basename($path);

    $storagePath = storage_path('app/public/' . $path);
    $publicPath = public_path('images/' . basename($path));

    if (!file_exists(public_path('images'))) {
        mkdir(public_path('images'), 0755, true);
    }

    copy($storagePath, $publicPath);

    $newprod = new Product;
    $newprod->name = $request->input('pname');
    $newprod->description = $request->input('desc');
    $newprod->price = $request->input('price');
    $newprod->supplier_price = $request->input('supplier_price');
    $newprod->image = $filename;
    $newprod->has_size = $request->input('has_size', 0);
    $newprod->category_id = $request->input('category_id');

    // Verify category exists
    $category = Category::find($request->input('category_id'));
    if (!$category) {
        return redirect()->back()->with('error', 'Selected category not found. Please select a valid category.');
    }

    // ✅ UPDATED: Calculate stock based on has_size
    if ($request->has_size == 1) {
        // If has size, calculate total stock from sizes
        $totalStock = 0;
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeName => $stock) {
                if (!empty($sizeName) && $stock !== null) {
                    $totalStock += intval($stock);
                }
            }
        }
        $newprod->stock = $totalStock;
    } else {
        // If no size, use manual stock input
        $newprod->stock = $request->input('stock');
    }

    $newprod->save();

    // Save sizes if product has_size
    if ($request->has_size == 1 && $request->has('sizes')) {
        foreach ($request->sizes as $sizeName => $stock) {
            if (!empty($sizeName) && $stock !== null) {
                ProductSize::create([
                    'product_id' => $newprod->id,
                    'size_name' => $sizeName,
                    'stock' => $stock
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'Product created successfully!');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $selected_prod = Product::with(['sizes', 'category'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.mngprod.edit', compact('selected_prod', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'supplier_price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = Product::findOrFail($id);
    $product->name = $request->input('name');
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->has_size = $request->has_size;
    $product->category_id = $request->input('category_id');

    // ✅ UPDATED: Calculate stock based on has_size
    if ($request->has_size == 1) {
        // If has size, calculate total stock from sizes
        $totalStock = 0;
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeName => $stock) {
                if (!empty($sizeName) && $stock !== null) {
                    $totalStock += intval($stock);
                }
            }
        }
        $product->stock = $totalStock;
    } else {
        // If no size, use manual stock input
        $product->stock = $request->input('stock');
    }

    // Check if image is uploaded
    if ($request->hasFile('image')) {
        try {
            $path = $request->file('image')->store('images', 'public');
            $filename = basename($path);

            $storagePath = storage_path('app/public/' . $path);
            $publicPath = public_path('images/' . $filename);

            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }

            copy($storagePath, $publicPath);
            $product->image = $filename;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
        }
    }

    $product->save();

    // Update sizes if product has_size
    if ($request->has_size == 1 && $request->has('sizes')) {
        ProductSize::where('product_id', $product->id)->delete();

        foreach ($request->sizes as $sizeName => $stock) {
            if (!empty($sizeName) && $stock !== null) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size_name' => $sizeName,
                    'stock' => $stock
                ]);
            }
        }
    } elseif ($request->has_size == 0) {
        ProductSize::where('product_id', $product->id)->delete();
    }

    return redirect()->route('admin.manageproducts.index')->with('success', 'Product updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete associated sizes
        ProductSize::where('product_id', $id)->delete();

        $product->delete();

        return redirect()->route('admin.manageproducts.index')->with('success', 'Product Deleted successfully.');
    }
}
