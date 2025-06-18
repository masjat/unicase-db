<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Menampilkan semua review produk tertentu
    public function index($productId)
    {
        return Review::where('product_id', $productId)->with('user')->get();
    }

    // Menyimpan review baru
    public function store(Request $request)
    {
        $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|numeric|min:1|max:5',
        'comment' => 'required|string',
        'color' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

    $review = Review::create($validated);

    $this->updateProductRating($validated['product_id']);

    return response()->json($review, 201);
    }

    // Menghapus review
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
   // Mengupdate rata-rata rating produk
    private function updateProductRating($productId)
    {
        $avgRating = Review::where('product_id', $productId)->avg('rating');
        Product::where('id', $productId)->update(['rating' => $avgRating]);
    }
}


