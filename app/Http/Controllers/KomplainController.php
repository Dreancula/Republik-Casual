<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komplain;
use App\Models\DetailKomplain;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\PengirimanProduk;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KomplainController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'id_produk' => 'required|array',
            'id_produk.*' => 'required|exists:produk,id_produk',
            'jenis_komplain' => 'required|string',
            'deskripsi' => 'required|string|min:10',
        ]);

        $idProduks = $request->id_produk;

        $detailPesanan = DetailPesanan::with('produk')
            ->where('id_pesanan', $request->id_pesanan)
            ->whereIn('id_produk', $idProduks)
            ->get()
            ->keyBy('id_produk');

        $rules = [];
        $messages = [];
        foreach ($idProduks as $idp) {
            $maxQty = $detailPesanan->has($idp) ? $detailPesanan[$idp]->quantity : 1;
            $productName = $detailPesanan->has($idp) ? $detailPesanan[$idp]->produk->nama_produk ?? 'Produk' : 'Produk';
            $rules['qty_' . $idp] = 'required|integer|min:1|max:' . $maxQty;
            $rules['fotos_' . $idp] = 'required|array';
            $rules['fotos_' . $idp . '.*'] = 'image|mimes:jpeg,png,jpg|max:2048';
            $messages['qty_' . $idp . '.max'] = "Jumlah rusak untuk \"{$productName}\" tidak boleh lebih dari {$maxQty}.";
            $messages['qty_' . $idp . '.min'] = "Jumlah rusak untuk \"{$productName}\" minimal 1.";
            $messages['fotos_' . $idp . '.required'] = "Foto bukti untuk \"{$productName}\" wajib diupload.";
        }
        $request->validate($rules, $messages);

        $komplain = Komplain::create([
            'id_pesanan' => $request->id_pesanan,
            'id_user' => auth()->user()->id_user,
            'jenis_komplain' => $request->jenis_komplain,
            'deskripsi' => $request->deskripsi,
        ]);

        foreach ($idProduks as $idp) {
            $fotoPaths = [];
            $files = $request->file('fotos_' . $idp, []);
            foreach ($files as $file) {
                if ($file) {
                    $fotoPaths[] = $file->store('komplain', 'public');
                }
            }

            DetailKomplain::create([
                'id_komplain' => $komplain->id_komplain,
                'id_produk' => $idp,
                'qty' => $request->input('qty_' . $idp),
                'foto' => json_encode($fotoPaths),
            ]);
        }

        return redirect()->route('komplain.saya')->with('success', 'Komplain berhasil diajukan. Menunggu konfirmasi admin.');
    }

    public function create($id_pesanan)
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'komplain.detailKomplain.produk',
            'komplain.produk',
        ])->findOrFail($id_pesanan);

        return view('frontend.customer.komplain.komplain-form', compact('pesanan'));
    }

    public function approve(Request $request, $id_komplain)
    {
        $komplain = Komplain::with('detailKomplain')->findOrFail($id_komplain);

        if ($komplain->status_komplain !== 'pending') {
            return redirect()->back()->with('error', 'Komplain ini sudah diproses.');
        }

        $request->validate([
            'id_produk' => 'array',
            'id_produk.*' => 'exists:produk,id_produk',
        ]);

        $approvedIds = $request->id_produk ?? [];

        foreach ($komplain->detailKomplain as $dk) {
            if (in_array($dk->id_produk, $approvedIds)) {
                $newQty = $request->input('qty_' . $dk->id_produk, $dk->qty);
                if ((int) $newQty !== (int) $dk->qty) {
                    $dk->update(['qty' => (int) $newQty]);
                }
            } else {
                $dk->delete();
            }
        }

        $komplain->update([
            'status_komplain' => 'approved',
        ]);

        return redirect()->route('admin.komplain.index')->with('success', 'Komplain berhasil disetujui. Menunggu retur dari customer.');
    }

    public function reject($id_komplain)
    {
        $komplain = Komplain::findOrFail($id_komplain);

        if ($komplain->status_komplain !== 'pending') {
            return redirect()->back()->with('error', 'Komplain ini sudah diproses.');
        }

        $komplain->update([
            'status_komplain' => 'rejected',
        ]);

        return redirect()->route('admin.komplain.index')->with('success', 'Komplain ditolak.');
    }

    public function konfirmasiRetur(Request $request, $id_komplain)
    {
        $komplain = Komplain::findOrFail($id_komplain);

        if ($komplain->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        if ($komplain->status_komplain !== 'approved') {
            return redirect()->back()->with('error', 'Komplain belum disetujui.');
        }

        if ($komplain->no_resi_return) {
            return redirect()->back()->with('error', 'Resi retur sudah pernah dibuat.');
        }

        $request->validate([
            'foto_return' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoReturnPath = $request->file('foto_return')->store('komplain/retur', 'public');

        $noResiReturn = 'RET-' . $komplain->id_komplain . '-' . strtoupper(Str::random(6));

        $komplain->update([
            'no_resi_return' => $noResiReturn,
            'foto_return' => $fotoReturnPath,
        ]);

        return redirect()->back()->with('success', 'Retur berhasil dikonfirmasi. No. Resi: ' . $noResiReturn . '. Barang pengganti akan dikirim setelah admin memverifikasi.');
    }

    public function finalize($id_komplain)
    {
        DB::beginTransaction();
        try {
            $komplain = Komplain::with('detailKomplain.produk')->findOrFail($id_komplain);

            if ($komplain->status_komplain !== 'approved') {
                return redirect()->back()->with('error', 'Komplain harus berstatus approved untuk difinalisasi.');
            }

            if (!$komplain->no_resi_return) {
                return redirect()->back()->with('error', 'Customer belum mengkonfirmasi pengiriman retur.');
            }

            $komplain->update([
                'status_komplain' => 'selesai',
            ]);

            foreach ($komplain->detailKomplain as $dk) {
                if ($dk->id_produk) {
                    Produk::where('id_produk', $dk->id_produk)
                        ->decrement('stok', $dk->qty);
                    Produk::where('id_produk', $dk->id_produk)
                        ->increment('deadstok', $dk->qty);
                }
            }

            $idPesananRetur = 'RC-RETUR-' . strtoupper(Str::random(6));

            $kurirList = ['JNE', 'JNT', 'Sicepat', 'Anteraja', 'Pos Indonesia'];
            $kurir = $kurirList[array_rand($kurirList)];
            $resi = str_replace(' ', '', $kurir) . mt_rand(100000000000, 999999999999);

            $komplain->update([
                'id_pesanan_retur' => $idPesananRetur,
            ]);

            Pesanan::create([
                'id_pesanan' => $idPesananRetur,
                'id_user' => $komplain->id_user,
                'tgl_pesanan' => now(),
                'status_pesanan' => 'dikirim',
                'total_harga_produk' => 0,
                'total_ongkir' => 0,
                'total_bayar' => 0,
            ]);

            foreach ($komplain->detailKomplain as $dk) {
                DetailPesanan::create([
                    'id_pesanan' => $idPesananRetur,
                    'id_produk' => $dk->id_produk,
                    'quantity' => $dk->qty,
                    'harga_satuan' => 0,
                    'sub_total' => 0,
                ]);
            }

            Pembayaran::create([
                'id_pesanan' => $idPesananRetur,
                'status_pembayaran' => 'paid',
                'metode_pembayaran' => 'retur',
                'tgl_pembayaran' => now(),
            ]);

            PengirimanProduk::create([
                'id_pesanan' => $idPesananRetur,
                'nama_kurir' => $kurir,
                'layanan' => 'REG',
                'no_resi' => $resi,
                'jenis_pengiriman' => 'pengganti',
                'tgl_pengiriman' => now(),
                'status_pengiriman' => 'dikirim',
            ]);

            DB::commit();

            return redirect()->route('admin.komplain.index')->with('success', 'Komplain selesai. Stok & deadstok tercatat. Pengiriman pengganti: ' . $kurir . ' - ' . $resi);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayatSaya()
    {
        $komplainList = Komplain::with([
            'pesanan.pengiriman',
            'returPesanan.pengiriman',
            'detailKomplain.produk',
        ])
            ->where('id_user', auth()->user()->id_user)
            ->latest()
            ->get();

        return view('frontend.customer.komplain.riwayat', compact('komplainList'));
    }

    public function indexAdmin()
    {
        $allKomplain = Komplain::with([
            'pesanan',
            'user',
            'detailKomplain.produk',
        ])->latest()->get();

        return view('backend.komplain.index', compact('allKomplain'));
    }

    public function showAdmin($id_komplain)
    {
        $komplain = Komplain::with([
            'user',
            'pesanan.detailPesanan.produk',
            'detailKomplain.produk',
        ])->findOrFail($id_komplain);

        return view('backend.komplain.show', compact('komplain'));
    }
}
