<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Komplain;
use Illuminate\Support\Facades\Auth;

class PesananSayaController extends Controller
{
    public function index()
    {
        $status = request('status');
        $filter = request('filter');

        $komplainCount = Komplain::where('id_user', Auth::user()->id_user)->count();

        if ($filter === 'komplain') {
            $komplainList = Komplain::with([
                'pesanan.pengiriman',
                'returPesanan.pengiriman',
                'detailKomplain.produk',
            ])
                ->where('id_user', auth()->user()->id_user)
                ->latest()
                ->get();

            return view('frontend.customer.pesanan.index', compact('komplainList', 'komplainCount'));
        }

        $query = Pesanan::with([
            'pembayaran',
            'pengirimanUtama',
            'pengiriman'
        ])
            ->where('id_user', Auth::user()->id_user);

        if ($status && in_array($status, ['pending', 'dibayar', 'diproses', 'dikirim', 'selesai'])) {
            $query->where('status_pesanan', $status);
        }

        $pesanans = $query->latest()->get();

        return view('frontend.customer.pesanan.index', compact('pesanans', 'komplainCount'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'pengirimanUtama',
            'pengiriman',
            'pembayaran',
            'komplain.produk',
            'komplain.detailKomplain.produk'
        ])
            ->where('id_user', Auth::user()->id_user)
            ->findOrFail($id);

        return view('frontend.customer.pesanan.show', compact('pesanan'));
    }
}
