<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class PesananSayaController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with([
            'pembayaran',
            'pengirimanUtama',
            'pengiriman'
        ])
            ->where(
                'id_user',
                Auth::user()->id_user
            )
            ->latest()
            ->get();

        return view(
            'frontend.customer.pesanan.index',
            compact('pesanans')
        );
    }

    public function show($id)
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pengirimanUtama',
            'pengiriman',
            'pembayaran',
            'komplain.produk'
        ])
            ->where(
                'id_user',
                Auth::user()->id_user
            )
            ->findOrFail($id);

        return view(
            'frontend.customer.pesanan.show',
            compact('pesanan')
        );
    }
}