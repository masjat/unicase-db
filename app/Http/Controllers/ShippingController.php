<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
         return Shipping::with(['product','user'])->get();
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required|exists:products,id',
            'total'=> 'required|numberic',
        ]);

        $shipping = Shipping::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'total' =>$validate['total']
        ]);        
        return response()->json([
            'message' => 'Wishlist item added',
            'data' => $wishlist,
        ], 201);    }

    public function show($id)
    {
        $shipping = Shipping::with(['product', 'user'])->findOrFail($id);

        if (!$shipping) {
            return response()->json(['message' => 'Shipping item not found'], 404);
        }

        return response()->json($wishlist);    }

    public function update(Request $request, $id)
    {
        $shipping = Shipping::with(['product', 'user'])->findOrFail($id);
        $validate = $request->validate([
            'total' => 'sometimes|numeric',

        ]);
        $shipping->update($validate);
        if (auth()->id() !== $shipping->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($shipping);
    }

    public function destroy($id)
    {
        $shipping = Shipping::with(['product', 'user'])->findOrFail($id);
        $shipping ->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
