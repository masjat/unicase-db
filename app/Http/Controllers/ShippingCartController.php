<?php

namespace App\Http\Controllers;

use App\Models\ShippingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingCartController extends Controller
{
    public function index()
    {
        $items = ShippingCart::with('product')->where('user_id', Auth::id())->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'price'      => 'required|numeric',
            'color'      => 'nullable|string',
        ]);

        $shippingcart = ShippingCart::create([
            'user_id'    => auth()->id(),
            'product_id' => $validated['product_id'],
            'quantity'   => $validated['quantity'],
            'price'      => $validated['price'],
            'color'      => $validated['color'] ?? null,
        ]);

        return response()->json([
            'message' => 'Product item added',
            'data' => $shippingcart,
        ], 201);
    }

    public function show($id)
    {
        $shippingcart = ShippingCart::with(['product'])->find($id);

        if (! $shippingcart || $shippingcart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Shipping item not found or unauthorized'], 404);
        }

        return response()->json($shippingcart);
    }

    public function update(Request $request, $id)
    {
        $shippingcart = ShippingCart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'price'    => 'sometimes|numeric',
            'color'    => 'nullable|string',
        ]);

        $shippingcart->update($validated);

        return response()->json([
            'message' => 'Cart item updated',
            'data' => $shippingcart
        ]);
    }

    public function destroy($id)
    {
        $item = ShippingCart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->delete();

        return response()->json(['message' => 'Item dihapus']);
    }
}
