<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use app\Services\RajaOngkirService;

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerProdukController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanBarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AiStylistController;

use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PesananSayaController;
use App\Http\Controllers\CustomerChatBotController;
use App\Http\Controllers\KomplainController;



// Route untuk nampilin halaman form komplain
Route::get('/komplain/ajukan/{id_pesanan}', [KomplainController::class, 'create'])->name('komplain.create')->middleware('auth');

// Route untuk proses submit-nya (yang udah kita bikin kemarin)
Route::post('/komplain/store', [KomplainController::class, 'store'])->name('komplain.store')->middleware('auth');

// Route untuk riwayat komplain customer
Route::get('/komplain/saya', [KomplainController::class, 'riwayatSaya'])->name('komplain.saya')->middleware('auth');

// Route untuk konfirmasi kirim retur + upload foto_return
Route::put('/komplain/{id_komplain}/konfirmasi-retur', [KomplainController::class, 'konfirmasiRetur'])->name('komplain.konfirmasi-retur')->middleware('auth');

Route::post('/chatbot/respond', [CustomerChatBotController::class, 'getBotResponse']);

Route::get(
    '/ai-stylist',
    [AiStylistController::class, 'index']
)->name('ai.index');

Route::post(
    '/ai-stylist/recommend',
    [AiStylistController::class, 'recommend']
)->name('ai.recommend');

Route::post(
    '/midtrans/callback',
    [MidtransController::class, 'callback']
);

/*
|--------------------------------------------------------------------------
| GOOGLE LOGIN CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

/*
|--------------------------------------------------------------------------
| LOGIN CUSTOMER
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return redirect()->route('google.redirect');
})->name('login');

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('beranda');
});

Route::get('/beranda', [HomeController::class, 'index'])
    ->name('beranda');

Route::get('/products', [CustomerProdukController::class, 'index'])
    ->name('products');

Route::get('/products/{id}', [CustomerProdukController::class, 'show'])
    ->name('products.show');

Route::get('/produk', [CustomerProdukController::class, 'index'])
    ->name('produk.index');

Route::get('/produk/{id}', [CustomerProdukController::class, 'show'])
    ->name('produk.show');

/*
|--------------------------------------------------------------------------
| LOGOUT CUSTOMER
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('beranda');

})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| CUSTOMER AREA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {



    Route::prefix('keranjang')->group(function () {

        Route::get('/', [OrderController::class, 'viewCart'])
            ->name('keranjang.index');

        Route::post('/add/{id}', [OrderController::class, 'addToCart'])
            ->name('keranjang.add');

        Route::post('/update/{id}', [OrderController::class, 'updateCart'])
            ->name('keranjang.update');

        Route::post('/remove/{id}', [OrderController::class, 'removeFromCart'])
            ->name('keranjang.remove');
    });

    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout.index');

    Route::get(
        '/profil',
        [ProfileController::class, 'index']
    )->name('profil.index');

    Route::put(
        '/profil',
        [ProfileController::class, 'update']
    )->name('profil.update');

    Route::get(
        '/api/search-destination',
        [ProfileController::class, 'searchDestination']
    )->middleware('auth');

    Route::post(
        '/checkout/ongkir',
        [CheckoutController::class, 'cekOngkir']
    )->name('checkout.ongkir');

    Route::post(
        '/checkout/store',
        [CheckoutController::class, 'store']
    )->name('checkout.store');

    Route::get(
        '/pesanan-saya',
        [PesananSayaController::class, 'index']
    )->name('pesanan.saya');

    Route::get(
        '/pesanan-saya/{id}',
        [PesananSayaController::class, 'show']
    )->name('pesanan.saya.show');

    Route::get(
        '/payment/finish/{id}',
        [MidtransController::class, 'finish']
    )->name('payment.finish');

    Route::post(
        '/payment/retry/{id}',
        [MidtransController::class, 'retry']
    )->name('payment.retry');

});


Route::get('/test-depok', function () {

    $service =
        app(\App\Services\RajaOngkirService::class);

    return $service->searchDestination(
        'depok'
    );

});

Route::post(
    '/checkout/cek-ongkir',
    [CheckoutController::class, 'cekOngkir']
)->name('checkout.cek-ongkir');

/*
|--------------------------------------------------------------------------
| LOGIN ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/backend/login', function () {
        return view('backend.login');
    })->name('admin.login');

    Route::post('/backend/login', function () {

        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            request()->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);

    })->name('admin.login.post');

});

/*
|--------------------------------------------------------------------------
| LOGOUT ADMIN
|--------------------------------------------------------------------------
*/

Route::post('/admin/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('admin.login');

})->middleware('auth')->name('admin.logout');

/*
|--------------------------------------------------------------------------
| BACKEND
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])
            ->name('user.index');

        Route::post('/user/store', [App\Http\Controllers\UserController::class, 'store'])
            ->name('user.store');

        Route::put('/user/{id}', [App\Http\Controllers\UserController::class, 'update'])
            ->name('user.update');

        Route::delete('/user/{id}', [App\Http\Controllers\UserController::class, 'destroy'])
            ->name('user.destroy');

        Route::get('/pesanan', [PesananController::class, 'index'])
            ->name('pesanan.index');

        Route::put('/pesanan/{id}', [PesananController::class, 'update'])
            ->name('pesanan.update');

        Route::get('/pesanan/{id}', [PesananController::class, 'show'])
            ->name('pesanan.show');

        Route::post('/pesanan/{id}/quick', [PesananController::class, 'quickUpdate'])
            ->name('pesanan.quick');

        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan.index');

        Route::middleware(['can:access-admin-or-manajer'])->group(function () {

            Route::resource('produk', ProdukController::class);

            Route::get('/kategori', [KategoriController::class, 'index'])
                ->name('kategori.index');

            Route::post('/kategori/store', [KategoriController::class, 'store'])
                ->name('kategori.store');

            Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])
                ->name('kategori.destroy');

            Route::get('/brand', [BrandController::class, 'index'])
                ->name('brand.index');

            Route::post('/brand/store', [BrandController::class, 'store'])
                ->name('brand.store');

            Route::delete('/brand/{id}', [BrandController::class, 'destroy'])
                ->name('brand.destroy');
        });

        Route::middleware(['can:access-manajer'])->group(function () {

            Route::get('/pemasukan', [PemasukanBarangController::class, 'index'])
                ->name('pemasukan-barang.index');

            Route::get('/pemasukan/create', [PemasukanBarangController::class, 'create'])
                ->name('pemasukan-barang.create');

            Route::post('/pemasukan', [PemasukanBarangController::class, 'store'])
                ->name('pemasukan-barang.store');

            Route::get('/pemasukan/{id}', [PemasukanBarangController::class, 'show'])
                ->name('pemasukan-barang.show');
        });

        // ... (route user & pesanan bawaan lu) ...
    
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        // 🔥 TARUH ROUTE ADMIN KOMPLAIN DI SINI
        // Di dalam Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () { ...
    
        Route::get('/komplain', [KomplainController::class, 'indexAdmin'])->name('komplain.index');
        Route::get('/komplain/{id_komplain}', [KomplainController::class, 'showAdmin'])->name('komplain.show');

        // 🔥 Ubah baris approve ini (buang teks 'admin.' pada bagian ->name() nya):
        Route::put('/komplain/{id_komplain}/approve', [KomplainController::class, 'approve'])->name('komplain.approve');

        Route::put('/komplain/{id_komplain}/reject', [KomplainController::class, 'reject'])->name('komplain.reject');

        Route::post('/komplain/{id_komplain}/finalize', [KomplainController::class, 'finalize'])->name('komplain.finalize');

    });

Route::get('/test-depok', function () {

    $service =
        app(\App\Services\RajaOngkirService::class);

    return $service->searchDestination(
        'depok'
    );

});
