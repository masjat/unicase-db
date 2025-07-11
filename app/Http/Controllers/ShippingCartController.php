<?php

namespace App\Http\Controllers;

use App\Models\ShippingCart;
use Illuminate\Http\Request;

class ShippingCartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = ShippingCart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return response()->json([
            'status' => true,
            'cart_items' => $cartItems,
            'total_price' => $totalPrice
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = ShippingCart::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $validated['quantity']]);
        } else {
            $cartItem = ShippingCart::create([
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_item' => $cartItem
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = ShippingCart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'status' => true,
            'message' => 'Jumlah produk berhasil diperbarui',
            'cart_item' => $cartItem
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $cartItem = ShippingCart::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus dari keranjang'
        ]);
    }
}
