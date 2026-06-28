<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL');
    }

    public function searchDestination($keyword)
    {
        return Http::withHeaders([
            'key' => $this->apiKey
        ])->get(
                $this->baseUrl . '/destination/domestic-destination',
                [
                    'search' => $keyword,
                    'limit' => 10
                ]
            )->json();
    }

    public function calculate(
        $originId,
        $destinationId,
        $weight,
        $courier
    ) {
        return Http::withHeaders([
            'key' => $this->apiKey
        ])->post(
                $this->baseUrl . '/calculate/domestic-cost',
                [
                    'origin' => $originId,
                    'destination' => $destinationId,
                    'weight' => $weight,
                    'courier' => $courier,
                    'price' => 'lowest'
                ]
            )->json();
    }
}