<?php
namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->shippingAddresses
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receivers_name'   => 'required|string|max:50',
            'phone_number'     => 'required|string|max:20',
            'province_id'      => 'required|integer',
            'province_name'    => 'required|string',
            'city_id'          => 'required|integer',
            'city'             => 'required|string',
            'district_id'      => 'required|integer',
            'district_name'    => 'required|string',
            'postal_code'      => 'required|string|max:10',
            'full_address'     => 'required|string',
            'note_to_courier'  => 'nullable|string',
            'is_primary'       => 'boolean',
        ]);

         // Jika primary, unset primary lain
         if ($request->is_primary) {
            ShippingAddress::where('user_id', $request->user()->id)
                ->update(['is_primary' => false]);
        }

        $address = ShippingAddress::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($address, 201);
    }

    public function show($id)
    {
        return ShippingAddress::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $address = ShippingAddress::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->firstOrFail();

        $validated = $request->validate([
            'receivers_name'   => 'sometimes|string|max:50',
            'phone_number'     => 'sometimes|string|max:20',
            'province_id'      => 'sometimes|integer',
            'province_name'    => 'sometimes|string',
            'city_id'          => 'sometimes|integer',
            'city'             => 'sometimes|required|string',
            'district_id'      => 'sometimes|integer',
            'district_name'    => 'sometimes|string',
            'postal_code'      => 'sometimes|string|max:10',
            'full_address'     => 'sometimes|string',
            'note_to_courier'  => 'nullable|string',
            'is_primary'       => 'boolean',
        ]);

        if ($request->is_primary) {
            ShippingAddress::where('user_id', $request->user()->id)
                ->update(['is_primary' => false]);
        }

        $address->update($validated);

        return response()->json($address);
    }

    public function destroy(Request $request, $id)
    {
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $address->delete();

        return response()->json(['message' => 'Alamat berhasil dihapus.']);
    }
}
