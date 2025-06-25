<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function index()
    {
        return response()->json(OrderDetail::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'custom_case_id' => 'nullable|exists:custom_cases,id',
            'variant' => 'required|string|max:50',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $detail = OrderDetail::create($request->all());

        return response()->json(['message' => 'Order detail created', 'data' => $detail], 201);
    }

    public function show($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $detail->update($request->all());
        return response()->json(['message' => 'Updated', 'data' => $detail]);
    }

    public function destroy($id)
    {
        $detail = OrderDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $detail->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
