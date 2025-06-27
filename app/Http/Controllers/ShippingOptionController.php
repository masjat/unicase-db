<?php

namespace App\Http\Controllers;

use App\Models\ShippingOption;
use Illuminate\Http\Request;

class ShippingOptionController extends Controller
{
    public function index()
    {
        return ShippingOption::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'estimate_days' => 'required|integer',
            'estimate_label' => 'nullable|string',
        ]);

        $shipping = ShippingOption::create($validated);

        return response()->json([
            'message' => 'Shipping option created successfully',
            'data' => $shipping
        ], 201);
    }

    public function show($id)
    {
        $shipping = ShippingOption::findOrFail($id);

        return response()->json($shipping);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'price' => 'sometimes|required|integer',
            'estimate_days' => 'sometimes|required|integer',
            'estimate_label' => 'nullable|string',
        ]);

        $shipping = ShippingOption::findOrFail($id);
        $shipping->update($validated);

        return response()->json([
            'message' => 'Shipping option updated successfully',
            'data' => $shipping
        ]);
    }

    public function destroy($id)
    {
        $shipping = ShippingOption::findOrFail($id);
        $shipping->delete();

        return response()->json([
            'message' => 'Shipping option deleted successfully'
        ]);
    }
}

