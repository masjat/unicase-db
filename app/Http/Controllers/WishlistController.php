<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with(['product'])->where('user_id', auth()->id())->get();

        return response()->json([
            'message' => 'Wishlist fetched successfully',
            'data' => $wishlists,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Cegah duplikasi
        $exists = Wishlist::where('user_id', auth()->id())
                    ->where('product_id', $validated['product_id'])
                    ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Product already in wishlist',
            ], 409); // 409 Conflict
        }

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
        $wishlist = Wishlist::with(['product'])->findOrFail($id);

        if ($wishlist->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Wishlist item detail',
            'data' => $wishlist,
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);

        if ($wishlist->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $wishlist->delete();

        return response()->json([
            'message' => 'Wishlist item deleted',
        ]);
    }
}
