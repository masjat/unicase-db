<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // Tampilkan semua alamat
    public function index()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    // Tambahkan alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipient' => 'required|string|max:80',
            'phone' => 'required|string|max:20',
            'label' => 'nullable|string|max:50',
            'province' => 'required|string|max:80',
            'city' => 'required|string|max:80',
            'postal_code' => 'required|string|max:20',
            'full_address' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $address = Address::create($request->all());

        return response()->json([
            'message' => 'Address created successfully',
            'data' => $address,
        ], 201);
    }

    // Menampilkan detail alamat
    public function show($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        return response()->json($address);
    }

    // Update alamat
    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->update($request->all());

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => $address,
        ]);
    }

    // Hapus alamat
    public function destroy($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
