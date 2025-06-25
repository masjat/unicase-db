<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::with(['order', 'method'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'id_method' => 'required|exists:method,id_method',
            'payment_date' => 'nullable|date',
            'total_payment' => 'required|numeric',
        ]);

        $payment = Payment::create($validated);
        return response()->json($payment, 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['order', 'method'])->findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $validated = $request->validate([
            'order_id' => 'sometimes|exists:orders,order_id',
            'id_method' => 'sometimes|exists:method,id_method',
            'payment_date' => 'nullable|date',
            'total_payment' => 'sometimes|numeric',
        ]);

        $payment->update($validated);
        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Payment deleted']);
    }
}
