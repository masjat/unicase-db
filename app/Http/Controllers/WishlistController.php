<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return response()->json(Wishlist::with(['user', 'product'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create($request->all());

        return response()->json([
            'message' => 'Wishlist item added',
            'data' => $wishlist,
        ], 201);
    }

    public function show($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }

        return response()->json($wishlist);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }

        $wishlist->delete();

        return response()->json(['message' => 'Wishlist item deleted']);
    }
}
