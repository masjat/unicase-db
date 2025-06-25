<?php

namespace App\Http\Controllers;

use App\Models\OrderTracking;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index()
    {
        return response()->json(OrderTracking::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|max:80',
        ]);

        $orderTracking = OrderTracking::create($request->all());
        return response()->json($orderTracking, 201);
    }

    public function show($id)
    {
        return response()->json(OrderTracking::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $orderTracking = OrderTracking::findOrFail($id);
        $orderTracking->update($request->all());
        return response()->json($orderTracking);
    }

    public function destroy($id)
    {
        OrderTracking::destroy($id);
        return response()->json(null, 204);
    }
}
