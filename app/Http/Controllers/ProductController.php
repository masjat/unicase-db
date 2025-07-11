<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|url',
            'color' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'required|integer',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // show
public function show($id)
{
    $product = Product::with('category', 'reviews')->findOrFail($id);
    return response()->json($product);
}

    // update
public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);       
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'price' => 'sometimes|numeric',
        'image' => 'nullable|url',
        'color' => 'nullable|string',
        'category_id' => 'sometimes|exists:categories,id',
        'weight' => 'sometimes|integer',
    ]);

    $product->update($request->all());

    return response()->json($product);
}


    public function destroy(Product $product)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
