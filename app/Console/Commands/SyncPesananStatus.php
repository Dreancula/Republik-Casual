<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Console\Command;

class SyncPesananStatus extends Command
{
    protected $signature = 'pesanan:sync-status';
    protected $description = 'Sync status pesanan pending dengan Midtrans';

    public function handle()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $pesanans = Pesanan::where('status_pesanan', 'pending')->get();

        $updated = 0;
        foreach ($pesanans as $pesanan) {
            try {
                $status = Transaction::status('ORDER-' . $pesanan->id_pesanan);
                $transStatus = $status->transaction_status ?? '';

                if (in_array($transStatus, ['capture', 'settlement'])) {
                    $pesanan->update(['status_pesanan' => 'dibayar']);

                    $pembayaran = Pembayaran::where('id_pesanan', $pesanan->id_pesanan)->first();
                    if ($pembayaran) {
                        $pembayaran->update([
                            'transaction_id' => $status->transaction_id ?? '',
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

                    $this->info("✅ #RC-{$pesanan->id_pesanan} → dibayar");
                    $updated++;
                } elseif (in_array($transStatus, ['expire', 'cancel', 'deny'])) {
                    $pesanan->update(['status_pesanan' => 'dibatalkan']);
                    $this->info("❌ #RC-{$pesanan->id_pesanan} → dibatalkan");
                    $updated++;
                }
            } catch (\Exception $e) {
                $is404 = str_contains($e->getMessage(), '404');

                if ($is404 && $pesanan->created_at->diffInMinutes(now()) >= 15) {
                    $pesanan->update(['status_pesanan' => 'dibatalkan']);
                    $this->info("❌ #RC-{$pesanan->id_pesanan} → dibatalkan (expired, no transaction)");
                    $updated++;
                } else {
                    $this->warn("⚠️  #RC-{$pesanan->id_pesanan} : {$e->getMessage()}");
                }
            }
        }

        $this->info("Selesai! {$updated} pesanan diupdate.");
    }
}
