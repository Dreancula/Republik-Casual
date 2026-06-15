<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;


class RajaOngkirController extends Controller
{
    private function getApiKey()
    {
        return env('RAJAONGKIR_API_KEY');
    }

    private function getBaseUrl()
    {
        return env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1');
    }

    public function getDestination(Request $request)
    {
        $search = $request->get('search', '');

        if (strlen($search) < 2) {
            return response()->json([
                'meta' => ['code' => 400, 'status' => 'error', 'message' => 'Minimal 2 karakter'],
                'data' => []
            ], 400);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getBaseUrl() . '/destination/domestic-destination?search=' . urlencode($search),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['key: ' . $this->getApiKey()],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            return response()->json([
                'meta' => ['code' => 500, 'status' => 'error', 'message' => 'Koneksi gagal: ' . $err],
                'data' => []
            ], 500);
        }

        $data = json_decode($response, true);
        return response()->json($data, $httpCode);
    }

    public function calculateOngkir(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $weight = $request->input('weight');
        $courier = $request->input('courier');

        if (!$origin || !$destination || !$weight || !$courier) {
            return response()->json([
                'meta' => ['code' => 400, 'status' => 'error', 'message' => 'Semua field wajib diisi'],
                'data' => []
            ], 400);
        }

        $postData = http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => 'lowest'
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getBaseUrl() . '/calculate/domestic-cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => ['key: ' . $this->getApiKey()],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($err) {
            return response()->json([
                'meta' => ['code' => 500, 'status' => 'error', 'message' => 'Koneksi gagal: ' . $err],
                'data' => []
            ], 500);
        }

        return response()->json(json_decode($response, true), $httpCode);
    }
}