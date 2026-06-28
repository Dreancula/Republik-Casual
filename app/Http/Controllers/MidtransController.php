<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function callback()
    {
        \Log::info('MIDTRANS CALLBACK MASUK');

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $notification = new Notification();

        \Log::info('NOTIFICATION', [
            'order_id' => $notification->order_id,
            'status' => $notification->transaction_status,
            'transaction_id' => $notification->transaction_id,
        ]);

        $idPesanan = str_replace(
            'ORDER-',
            '',
            $notification->order_id
        );

        $pesanan = Pesanan::find($idPesanan);

        \Log::info('PESANAN', [
            'found' => $pesanan ? true : false,
            'id_pesanan' => $idPesanan
        ]);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan'
            ]);
        }

        $pembayaran = Pembayaran::where(
            'id_pesanan',
            $pesanan->id_pesanan
        )->first();

        \Log::info('PEMBAYARAN', [
            'found' => $pembayaran ? true : false
        ]);

        $status = $notification->transaction_status;

        if (
            $status == 'capture' ||
            $status == 'settlement'
        ) {

            $pesanan->update([
                'status_pesanan' => 'dibayar'
            ]);

            if ($pembayaran) {

                $pembayaran->update([
                    'transaction_id' =>
                    $notification->transaction_id,

                    'tgl_pembayaran' =>
                    now(),

                    'status_pembayaran' =>
                    'paid',
                ]);
            }

            $pesanan->load('detailPesanan.produk');

            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $detail->produk->decrement('stok', $detail->quantity);
                }
            }
        }

        if ($status == 'expire') {

            $pesanan->update([
                'status_pesanan' => 'dibatalkan'
            ]);

            if ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => 'expired'
                ]);
            }
        }

        if ($status == 'cancel') {

            $pesanan->update([
                'status_pesanan' => 'dibatalkan'
            ]);

            if ($pembayaran) {

                $pembayaran->update([
                    'status_pembayaran' => 'cancelled'
                ]);
            }
        }

        if ($status == 'deny') {

            if ($pembayaran) {

                $pembayaran->update([
                    'status_pembayaran' => 'failed'
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function retry($id)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')->find($id);

        if (!$pesanan) {
            return response()->json(['error' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($pesanan->id_user !== auth()->user()->id_user) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        if ($pesanan->status_pesanan !== 'pending') {
            return response()->json(['error' => 'Pesanan sudah diproses.'], 400);
        }

        $pembayaran = Pembayaran::where('id_pesanan', $pesanan->id_pesanan)->first();
        if ($pembayaran && $pembayaran->status_pembayaran === 'paid') {
            return response()->json(['error' => 'Pembayaran sudah lunas.'], 400);
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $pesanan->id_pesanan,
                'gross_amount' => $pesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $pesanan->user->nama ?? 'Customer',
                'email' => $pesanan->user->email ?? '',
                'phone' => $pesanan->user->no_telp ?? '',
            ],
            'callbacks' => [
                'finish' => route('payment.finish', $pesanan->id_pesanan),
            ],
        ];

        $prevOrderId = 'ORDER-' . $pesanan->id_pesanan;

        try {
            Transaction::expire($prevOrderId);
        } catch (\Exception $e) {
            \Log::info('MIDTRANS RETRY EXPIRE OLD', [
                'message' => $e->getMessage(),
                'order_id' => $prevOrderId,
            ]);
        }

        try {
            $newSnapToken = Snap::getSnapToken($params);

            if ($pembayaran) {
                $pembayaran->update([
                    'snap_token' => $newSnapToken,
                    'status_pembayaran' => 'pending',
                ]);
            }

            if ($pesanan->status_pesanan !== 'pending') {
                $pesanan->update(['status_pesanan' => 'pending']);
            }

            return response()->json([
                'snap_token' => $newSnapToken,
            ]);
        } catch (\Exception $e) {
            \Log::error('MIDTRANS RETRY ERROR', [
                'message' => $e->getMessage(),
                'id_pesanan' => $id,
            ]);

            return response()->json([
                'error' => 'Gagal membuat ulang token pembayaran. Silakan refresh halaman dan coba lagi.',
            ], 500);
        }
    }

    public function finish($id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return redirect()
                ->route('pesanan.saya')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($pesanan->status_pesanan !== 'pending') {
            return redirect()
                ->route('pesanan.saya')
                ->with('info', 'Status pesanan sudah diperbarui.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $status = Transaction::status('ORDER-' . $pesanan->id_pesanan);

            $transStatus = $status->transaction_status ?? '';
            $transId = $status->transaction_id ?? '';

            if ($transStatus == 'capture' || $transStatus == 'settlement') {
                $pesanan->update([
                    'status_pesanan' => 'dibayar',
                ]);

                $pembayaran = Pembayaran::where('id_pesanan', $pesanan->id_pesanan)->first();
                if ($pembayaran) {
                    $pembayaran->update([
                        'transaction_id' => $transId,
                        'tgl_pembayaran' => now(),
                        'status_pembayaran' => 'paid',
                    ]);
                }

                $pesanan->load('detailPesanan.produk');
                foreach ($pesanan->detailPesanan as $detail) {
                    if ($detail->produk) {
                        $detail->produk->decrement('stok', $detail->quantity);
                    }
                }

                return redirect()
                    ->route('pesanan.saya')
                    ->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
            }

            if ($transStatus == 'pending') {
                return redirect()
                    ->route('pesanan.saya')
                    ->with('info', 'Pembayaran masih menunggu konfirmasi. Silakan cek kembali nanti.');
            }

            if (in_array($transStatus, ['expire', 'cancel', 'deny'])) {
                $pesanan->update(['status_pesanan' => 'dibatalkan']);

                return redirect()
                    ->route('pesanan.saya')
                    ->with('error', 'Pembayaran gagal atau dibatalkan.');
            }

            return redirect()
                ->route('pesanan.saya')
                ->with('info', 'Status pembayaran: ' . $transStatus);
        } catch (\Exception $e) {
            \Log::error('MIDTRANS FINISH ERROR', [
                'message' => $e->getMessage(),
                'id_pesanan' => $id,
            ]);

            return redirect()
                ->route('pesanan.saya')
                ->with('error', 'Gagal memverifikasi pembayaran. Silakan cek kembali nanti.');
        }
    }
}
