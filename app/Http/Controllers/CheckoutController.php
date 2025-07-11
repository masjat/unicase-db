<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutItem;
use App\Models\ShippingCart;
use App\Models\ShippingAddress;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:shipping_carts,id',
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'courier' => 'required|string',
            'courier_service' => 'required|string',
        ]);

        $user = auth()->user();

        $cartItems = ShippingCart::with('product')
            ->where('user_id', $user->id)
            ->whereIn('id', $validated['cart_ids'])
            ->get();

        $totalWeight = $cartItems->sum(fn($item) => $item->product->weight * $item->quantity);
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        $address = ShippingAddress::findOrFail($validated['shipping_address_id']);
        $destinationDistrictId = $address->district_id;
        $shippingCost = $this->getShippingCostFromRajaOngkir(
            $destinationDistrictId,
            $totalWeight,
            $validated['courier'],
            $validated['courier_service']
        );

        $checkout = Checkout::create([
            'user_id' => $user->id,
            'shipping_address_id' => $validated['shipping_address_id'],
            'payment_method_id' => $validated['payment_method_id'],
            'courier' => $validated['courier'],
            'courier_service' => $validated['courier_service'],
            'shipping_cost' => $shippingCost,
            'subtotal' => $subtotal,
            'total' => $subtotal + $shippingCost,
            'status' => 'pending'
        ]);

        foreach ($cartItems as $item) {
            CheckoutItem::create([
                'checkout_id' => $checkout->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_price' => $item->product->price,
                'weight' => $item->product->weight,
                'quantity' => $item->quantity,
            ]);
        }

        ShippingCart::whereIn('id', $validated['cart_ids'])->delete();

        return response()->json([
            'status' => true,
            'message' => 'Checkout berhasil',
            'checkout' => $checkout
        ]);
    }

    private function getShippingCostFromRajaOngkir(
        string $destinationDistrictId,
        int    $weight,
        string $courier,
        string $service
    ): int {
        $originDistrictId = env('RAJAONGKIR_ORIGIN_DISTRICT_ID');
        $apiKey = env('RAJAONGKIR_API_KEY');
    
        $payload = [
            'origin'      => $originDistrictId,
            'destination' => $destinationDistrictId,
            'weight'      => $weight,
            'courier'     => $courier,
            'price'       => 'lowest'
        ];
    
        \Log::info('Guzzle Payload Debug', $payload); // ✅ log isi payload dulu
    
        try {
            $client = new Client();
    
            $response = $client->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
                'form_params' => $payload,
                'headers' => [
                    'accept' => 'application/json',
                    'key'    => $apiKey
                ],
                'http_errors' => false,
            ]);
    
            $status = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
    
            if ($status === 200 && isset($body['data'])) {
                foreach ($body['data'] as $svc) {
                    if (strtolower($svc['service']) === strtolower($service)) {
                        return (int) $svc['cost'];
                    }
                }
            }
    
            \Log::warning('Gagal hitung ongkir:', [
                'status' => $status,
                'body'   => $body,
            ]);
    
        } catch (RequestException $e) {
            \Log::error('Guzzle Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    
        return 0;
    }  
}
