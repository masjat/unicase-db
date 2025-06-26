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
        return Review::with(['product', 'user'])->get();
    }

    // Menyimpan review baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string',
    ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);

    $this->updateProductRating($validated['product_id']);

    return response()->json($review, 201);
    }
   public function show($id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);
        return response()->json($review);
    }
    public function update(Request $request, $id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'sometimes|numeric|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $review->update($validated);
        $this->updateProductRating($review->product_id);
        
        if (auth()->id() !== $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);
        $productId = $review->product_id;
        $review->delete();

        $this->updateProductRating($productId);

        if (auth()->id() !== $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json(['message' => 'Review deleted']);
    }

    // Fungsi bantu untuk update rating produk
    private function updateProductRating($productId)
    {
        $avg = Review::where('product_id', $productId)->avg('rating') ?? 0;
        Product::where('id', $productId)->update(['rating' => $avg]);
    }
}


