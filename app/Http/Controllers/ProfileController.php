<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $alamat = explode('|', Auth::user()->alamat ?? '');

        return view(
            'frontend.customer.profil.index',
            [
                'provinsi' => $alamat[0] ?? '',
                'kota' => $alamat[1] ?? '',
                'kecamatan' => $alamat[2] ?? '',
                'destinationId' => $alamat[3] ?? '',
                'alamatLengkap' => $alamat[4] ?? '',
            ]
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_telp' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'alamat_lengkap' => 'required',
            'destination_id' => 'required',
        ]);

        $user = Auth::user();

        $user->update([
            'nama' => $request->nama,
            'no_telp' => $request->no_telp,

            'alamat' =>
                $request->provinsi . '|' .
                $request->kota . '|' .
                $request->kecamatan . '|' .
                $request->destination_id . '|' .
                $request->alamat_lengkap,

            'destination_id' => $request->destination_id
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }

    public function searchDestination(Request $request)
    {
        $keyword = $request->keyword;

        if (!$keyword) {
            return response()->json([
                'data' => []
            ]);
        }

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get(
                env('RAJAONGKIR_BASE_URL') . '/destination/domestic-destination',
                [
                    'search' => $keyword,
                    'limit' => 10
                ]
            );

        return response()->json(
            $response->json()
        );
    }
}