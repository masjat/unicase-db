<?php

namespace App\Http\Controllers;

use App\Models\Custom;
use Illuminate\Http\Request;

class CustomController extends Controller
{
    /**
     * Endpoint untuk tes POST
     */
    public function testPost(Request $request)
    {
        return response()->json([
            'message' => 'POST method OK',
            'data'    => $request->all()
        ]);
    }

    /**
     * Tampilkan semua data custom case
     */
    public function index()
    {
        $customCases = Custom::with('user')->get();
        return response()->json($customCases);
    }

    /**
     * Simpan custom case baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'image_url'    => 'nullable|string',
            'case_type'    => 'required|in:hardcase,softcase,premium anti-crack',
            'print_effect' => 'required|in:glossy,doff,glow effect',
            'brand'        => 'required|string|max:80',
            'phone_model'  => 'required|string|max:80',
            'description'  => 'nullable|string',
            'price_case'   => 'required|numeric',
            'price_print'  => 'required|numeric',
            'total_price'  => 'required|numeric',
        ]);

        $custom = Custom::create($validated);
        return response()->json($custom, 201);
    }

    /**
     * Tampilkan data berdasarkan ID
     */
    public function show($id)
    {
        $custom = Custom::with('user')->find($id);

        if (!$custom) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($custom);
    }

    /**
     * Update data custom case
     */
    public function update(Request $request, $id)
    {
        $custom = Custom::find($id);

        if (!$custom) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'user_id'      => 'sometimes|exists:users,id',
            'image_url'    => 'nullable|string',
            'case_type'    => 'sometimes|in:hardcase,softcase,premium anti-crack',
            'print_effect' => 'sometimes|in:glossy,doff,glow effect',
            'brand'        => 'sometimes|string|max:80',
            'phone_model'  => 'sometimes|string|max:80',
            'description'  => 'nullable|string',
            'price_case'   => 'sometimes|numeric',
            'price_print'  => 'sometimes|numeric',
            'total_price'  => 'sometimes|numeric',
        ]);

        $custom->update($validated);

        return response()->json($custom);
    }

    /**
     * Hapus data custom case
     */
    public function destroy($id)
    {
        $custom = Custom::find($id);

        if (!$custom) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $custom->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
