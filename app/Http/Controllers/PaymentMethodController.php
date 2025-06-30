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
        'name' => 'required|string',
        'type' => 'required|string',
    ]);

    $method = PaymentMethod::create($validated);

    return response()->json([
        'message' => 'Payment method created successfully.',
        'data'    => $method
    ], 201);
}

}