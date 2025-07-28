<?php

namespace App\Http\Controllers;

use App\Models\BrandType;
use Illuminate\Http\Request;

class BrandTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('brand')) {
            return BrandType::where('brand_id', $request->brand)->get();
        }

        return BrandType::with('brand')->get(); // include relasi ke brand
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:100|unique:brand_types,name',
        ]);

        $brandType = BrandType::create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Brand type created', 'data' => $brandType], 201);
    }

    public function show($id)
    {
        return BrandType::with('brand')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $brandType = BrandType::findOrFail($id);

        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:100|unique:brand_types,name,' . $id,
        ]);

        $brandType->update([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Brand type updated', 'data' => $brandType]);
    }

    public function destroy($id)
    {
        $brandType = BrandType::findOrFail($id);
        $brandType->delete();

        return response()->json(['message' => 'Brand type deleted']);
    }
}

