<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethod::all();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:100',
            'bank_logo'      => 'nullable|url',
            'type'           => 'required|in:transfer',
            'is_active'      => 'boolean'
        ]);

        $paymentMethod = PaymentMethod::create($validated);

        return response()->json($paymentMethod, 201);
    }
    public function show($id)
    {
        return PaymentMethod::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'sometimes|string|max:100',
            'account_number' => 'sometimes|string|max:50',
            'account_name'   => 'sometimes|string|max:100',
            'bank_logo'      => 'nullable|url',
            'type'           => 'in:transfer',
            'is_active'      => 'boolean'
        ]);

        $paymentMethod->update($validated);

        return response()->json($paymentMethod);
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        return response()->json(['message' => 'Metode pembayaran dihapus']);
    }
}
