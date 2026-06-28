<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Pesanan::where('status_pesanan', 'pending')
            ->where('created_at', '<=', now()->subMinutes(15))
            ->update([
                'status_pesanan' => 'dibatalkan'
            ]);

        Pembayaran::where('status_pembayaran', 'pending')
            ->whereHas('pesanan', function ($q) {
                $q->where('status_pesanan', 'dibatalkan');
            })
            ->update([
                'status_pembayaran' => 'expired'
            ]);
    }
}
