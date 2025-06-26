<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return Wishlist::with(['product','user'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
        ]);

        return response()->json([
            'message' => 'Wishlist item added',
            'data' => $wishlist,
        ], 201);
    }

    public function show($id)
    {
       $wishlist = Wishlist::with(['product', 'user'])->findOrFail($id);

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }

        return response()->json($wishlist);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $productId = $wishlist->product_id;
        $wishlist->delete();
    
        if (auth()->id() !== $wishlist->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['message' => 'Wishlist item deleted']);
    }
}
