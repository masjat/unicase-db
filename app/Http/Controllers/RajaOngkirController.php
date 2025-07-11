<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = 'https://api.rajaongkir.com/starter/';
    }

    public function provinces()
    {
        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get($this->baseUrl . 'province');

        return $response->json();
    }

    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');

        $response = Http::withHeaders(['key' => $this->apiKey])
            ->get($this->baseUrl . 'city', [
                'province' => $provinceId
            ]);

        return $response->json();
    }

    public function courierServices(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer',
            'courier' => 'required|string',
        ]);

        $origin = env('RAJAONGKIR_ORIGIN_CITY_ID');

        $response = Http::withHeaders(['key' => $this->apiKey])
            ->post($this->baseUrl . 'cost', [
                'origin' => $origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
            ]);

        return $response->json();
    }
}
