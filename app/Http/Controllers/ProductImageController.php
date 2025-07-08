<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
  // Ambil semua gambar produk tertentu
    public function index($productId)
    {
        $images = ProductImage::where('product_id', $productId)->get();
        return response()->json($images);
    }

    // Tambahkan gambar baru ke produk
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url' => 'required|url',
            'is_primary' => 'boolean'
        ]);

        // jika is_primary = true, ubah yang lain jadi false
        if ($request->is_primary) {
            ProductImage::where('product_id', $request->product_id)->update(['is_primary' => false]);
        }

        $image = ProductImage::create($request->all());

        return response()->json([
            'message' => 'Gambar berhasil ditambahkan',
            'image' => $image
        ]);
    }

    // Hapus gambar
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->delete();

        return response()->json(['message' => 'Gambar berhasil dihapus']);
    }
}
