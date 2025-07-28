<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
        ]);

        $brand = Brand::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Brand created', 'data' => $brand], 201);
    }

    public function show($id)
    {
        return Brand::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,' . $id,
        ]);

        $brand->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Brand updated', 'data' => $brand]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json(['message' => 'Brand deleted']);
    }
}

