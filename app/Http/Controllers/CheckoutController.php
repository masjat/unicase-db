<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'shipping_address_id' => 'required|exists:shipping_addresses,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'shipping_option_id' => 'required|exists:shipping_options,id',
    ]);

    $user = Auth::user();
    $carts = \App\Models\ShippingCart::where('user_id', $user->id)->get();

    if ($carts->isEmpty()) {
        return response()->json(['message' => 'Keranjang kosong'], 400);
    }

    $totalProductPrice = $carts->sum(fn($item) => $item->price * $item->quantity);
    $shippingCost = 10000;
    $serviceFee = 2000;
    $applicationFee = 1000;

    $checkout = Checkout::create([
        'user_id' => $user->id,
        'shipping_address_id' => $request->shipping_address_id,
        'payment_method_id' => $request->payment_method_id,
        'shipping_option_id' => $request->shipping_option_id,
        'total_product_price' => $totalProductPrice,
        'total_shipping_cost' => $shippingCost,
        'service_fee' => $serviceFee,
        'application_fee' => $applicationFee,
        'total_purchase' => $totalProductPrice + $shippingCost + $serviceFee + $applicationFee,
        'status' => 'pending'
    ]);

    foreach ($carts as $item) {
        CheckoutItem::create([
            'checkout_id' => $checkout->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'total' => $item->price * $item->quantity,
        ]);
    }

    \App\Models\ShippingCart::where('user_id', $user->id)->delete();

    return response()->json([
        'message' => 'Checkout berhasil',
        'data' => $checkout->load('items')
    ]);
}
}
