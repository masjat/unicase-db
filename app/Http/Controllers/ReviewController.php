<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // 📄 Tampilkan semua review untuk produk tertentu
    // /review → untuk semua review (opsional)
public function index()
{
    $reviews = Review::with(['user', 'product'])->get();

    return response()->json($reviews);
}


    // ➕ Tambah review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|numeric|min:1|max:5',
            'review'     => 'nullable|string',
        ]);

        // 🔒 Cegah review ganda dari user yang sama
        $existing = Review::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Kamu sudah mereview produk ini.'
            ], 409);
        }

        $review = Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $validated['product_id'],
            'rating'     => $validated['rating'],
            'review'     => $validated['review'],
        ]);

        $this->updateProductRating($validated['product_id']);

        return response()->json([
            'message' => 'Review berhasil ditambahkan.',
            'review'  => $review
        ], 201);
    }

    // 🔍 Tampilkan satu review
    public function show($id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);

        return response()->json($review);
    }

    // ✏️ Ubah review
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if (auth()->id() != $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|numeric|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $review->update($validated);

        $this->updateProductRating($review->product_id);

        return response()->json([
            'message' => 'Review berhasil diperbarui.',
            'review'  => $review
        ]);
    }

    // ❌ Hapus review
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if (auth()->id() != $review->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $productId = $review->product_id;

        $review->delete();

        $this->updateProductRating($productId);

        return response()->json(['message' => 'Review berhasil dihapus.']);
    }

    // 🧠 Fungsi bantu: update rating rata-rata produk
    private function updateProductRating($productId)
    {
        $avg = Review::where('product_id', $productId)->avg('rating') ?? 0;

        Product::where('id', $productId)->update([
            'rating' => $avg
        ]);
    }

    // /products/{productId}/reviews → khusus review 1 produk
public function reviewsByProduct($productId)
{
    $reviews = Review::with('user')
        ->where('product_id', $productId)
        ->get();

    return response()->json($reviews);
}

}
