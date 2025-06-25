<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return response()->json(Shipping::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:30',
            'price' => 'required|integer',
        ]);

        $shipping = Shipping::create($request->all());
        return response()->json($shipping, 201);
    }

    public function show($id)
    {
        return response()->json(Shipping::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->update($request->all());
        return response()->json($shipping);
    }

    public function destroy($id)
    {
        Shipping::destroy($id);
        return response()->json(null, 204);
    }
}
