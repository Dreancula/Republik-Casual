<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PengirimanProduk;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function index()
    {
        // 1. Ambil data pesanan
        $pesanans = Pesanan::with(['user', 'detailPesanan.produk', 'pembayaran', 'pengirimanUtama', 'pengiriman'])
            ->orderBy('tgl_pesanan', 'desc')
            ->get();

        // 2. Menghitung pesanan berstatus pending/proses
        $totalPerluDiproses = Pesanan::whereIn('status_pesanan', ['pending', 'proses', 'menunggu_resi'])->count();

        // 3. Hitung juga variabel pendukung lainnya (Selesai bulan ini)
        $totalSelesaiBulanIni = Pesanan::where('status_pesanan', 'selesai')
            ->whereMonth('tgl_pesanan', \Carbon\Carbon::now()->month)
            ->whereYear('tgl_pesanan', \Carbon\Carbon::now()->year)
            ->count();

        // 4. PERBAIKAN: Arahkan ke view pesanan, bukan view user!
        return view('backend.pesanan.index', compact('pesanans', 'totalPerluDiproses', 'totalSelesaiBulanIni'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_resi' => 'nullable|string|max:100',
            'nomor_resi_pengganti' => 'nullable|string|max:100',

            'status_pesanan' =>
                'required|in:diproses,dikirim,selesai,dibatalkan',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // Update status di tabel Pesanan
        $pesanan->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        // Update atau Buat data nomor resi di tabel Pengiriman (PengirimanProduk)
        $pengirimanUtama = $pesanan->pengirimanUtama;
        if ($pengirimanUtama) {
            $pengirimanUtama->update([
                'no_resi' => $request->nomor_resi
            ]);
        } else {
            $pesanan->pengiriman()->create([
                'id_pesanan' => $pesanan->id_pesanan,
                'no_resi' => $request->nomor_resi,
                'nama_kurir' => 'JNE',
                'layanan' => 'REG',
                'jenis_pengiriman' => 'utama',
            ]);
        }

        if ($request->filled('nomor_resi_pengganti')) {
            $pengirimanPengganti = $pesanan->pengiriman()
                ->where('jenis_pengiriman', 'pengganti')
                ->first();
            if ($pengirimanPengganti) {
                $pengirimanPengganti->update([
                    'no_resi' => $request->nomor_resi_pengganti,
                    'status_pengiriman' => 'dikirim',
                ]);
            }
        }

        return redirect()->back()->with('success', "Pesanan #RC-{$id} berhasil diperbarui.");
    }

    public function quickUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $status = $request->status;

        $pesanan->update(['status_pesanan' => $status]);

        if ($status == 'dikirim') {
            $pengirimanUtama = $pesanan->pengirimanUtama;
            $kurir = $pengirimanUtama->nama_kurir ?? 'JNE';
            $resi = str_replace(' ', '', $kurir) . mt_rand(100000000000, 999999999999);

            if ($pengirimanUtama) {
                $pengirimanUtama->update(['no_resi' => $resi]);
            } else {
                $pesanan->pengiriman()->create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'no_resi' => $resi,
                    'nama_kurir' => 'JNE',
                    'layanan' => 'REG',
                    'jenis_pengiriman' => 'utama',
                ]);
            }
        }

        return redirect()->back()->with('success', "Pesanan #RC-{$id} status diubah ke " . $status);
    }
}