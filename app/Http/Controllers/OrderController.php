<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /api/orders
    public function index()
    {
        return response()->json(Order::all());
    }

    // POST /api/orders
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'address_id'  => 'required|exists:addresses,id',
            'id_shipping' => 'required|exists:shippings,id',
            'service_fee' => 'required|numeric',
            'total_price' => 'required|numeric',
            'id_status'   => 'required|exists:statuses,id',
        ]);

        $order = Order::create($request->all());

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    // GET /api/orders/{id}
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    // PUT /api/orders/{id}
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $request->validate([
            'address_id'  => 'sometimes|exists:addresses,id',
            'id_shipping' => 'sometimes|exists:shippings,id',
            'service_fee' => 'sometimes|numeric',
            'total_price' => 'sometimes|numeric',
            'id_status'   => 'sometimes|exists:statuses,id',
        ]);

        $order->update($request->all());

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order,
        ]);
    }

    // DELETE /api/orders/{id}
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
