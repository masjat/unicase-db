<?php
namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    public function index()
    {
        return ShippingAddress::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receivers_name'   => 'required|string',
            'phone_number'     => 'required|string',
            'address_label'    => 'required|string',
            'city'             => 'required|string',
            'postal_code'      => 'required|string',
            'full_address'     => 'required|string',
            'note_to_courier'  => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $address = ShippingAddress::create($validated);

        return response()->json([
            'message' => 'Shipping address saved successfully.',
            'data'    => $address
        ], 201);
    }

    public function show($id)
    {
        return ShippingAddress::where('user_id', Auth::id())->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $address = ShippingAddress::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'receivers_name'   => 'sometimes|required|string',
            'phone_number'     => 'sometimes|required|string',
            'address_label'    => 'sometimes|required|string',
            'city'             => 'sometimes|required|string',
            'postal_code'      => 'sometimes|required|string',
            'full_address'     => 'sometimes|required|string',
            'note_to_courier'  => 'nullable|string',
        ]);

        $address->update($validated);

        return response()->json([
            'message' => 'Shipping address updated successfully.',
            'data'    => $address
        ]);
    }

    public function destroy($id)
    {
        $address = ShippingAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return response()->json([
            'message' => 'Shipping address deleted successfully.'
        ]);
    }
}
