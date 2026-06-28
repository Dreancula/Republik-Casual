<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // ◄--- GANTI / PASTIKAN PAKAI YANG INI!
use App\Models\User; // ◄--- Pastikan model User juga di-import

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate untuk akses bersama Admin & Manajer
        Gate::define('access-admin-or-manajer', function (User $user) {
            return in_array($user->id_role, [1, 2]);
        });

        // Gate khusus Manajer Toko saja
        Gate::define('access-manajer', function (User $user) {
            return $user->id_role == 2;
        });
    }
}