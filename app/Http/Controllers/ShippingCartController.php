<?php

namespace App\Http\Controllers;

use App\Models\ShippingCart;
use Illuminate\Http\Request;

class ShippingCardController extends Controller
{
    public function index()
    {
         return ShippingCart::with(['product','user'])->get();
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required|exists:products,id',
            'total'=> 'required|numberic',
        ]);

        $shippingcart = ShippingCart::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'total' =>$validate['total']
        ]);        
        return response()->json([
            'message' => 'Product item added',
            'data' => $shippingcart,
        ], 201);    }

    public function show($id)
    {
        $shippingcart = ShippingCart::with(['product', 'user'])->findOrFail($id);

        if (!$shippingcart) {
            return response()->json(['message' => 'Shipping item not found'], 404);
        }

        return response()->json($wishlist);    }

    public function update(Request $request, $id)
    {
        $shippingcart = ShippingCart::with(['product', 'user'])->findOrFail($id);
        $validate = $request->validate([
            'total' => 'sometimes|numeric',

        ]);
        $shippingcart->update($validate);
        if (auth()->id() !== $shippingcart->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($shippingcart);
    }

    public function destroy($id)
    {
        $shippingcart = ShippingCart::with(['product', 'user'])->findOrFail($id);
        $shippingcart ->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
