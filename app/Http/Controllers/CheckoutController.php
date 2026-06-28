<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use App\Models\PengirimanProduk;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Pembayaran;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('keranjang.index')
                ->with(
                    'error',
                    'Keranjang masih kosong.'
                );
        }

        $subtotal = 0;
        $totalBerat = 0;

        foreach ($cart as $item) {

            $produk = Produk::find(
                $item['id_produk']
            );

            if (!$produk) {
                continue;
            }

            $subtotal +=
                $item['harga'] *
                $item['qty'];

            $totalBerat +=
                $produk->berat *
                $item['qty'];
        }

        $alamat = explode(
            '|',
            Auth::user()->alamat ?? ''
        );

        return view(
            'frontend.customer.checkout.index',
            [
                'cart' => $cart,
                'subtotal' => $subtotal,
                'totalBerat' => $totalBerat,

                'provinsi' => $alamat[0] ?? '',
                'kota' => $alamat[1] ?? '',
                'kecamatan' => $alamat[2] ?? '',
                'destinationId' => $alamat[3] ?? '',
                'alamatLengkap' => $alamat[4] ?? '',
            ]
        );
    }

    public function cekOngkir(Request $request)
    {
        $alamat = explode(
            '|',
            Auth::user()->alamat
        );

        $destinationId = $alamat[3] ?? null;

        $cart = session('cart', []);

        $totalBerat = 0;

        foreach ($cart as $item) {

            $produk = Produk::find(
                $item['id_produk']
            );

            if (!$produk) {
                continue;
            }

            $totalBerat +=
                $produk->berat *
                $item['qty'];
        }

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->asForm()->post(
                env('RAJAONGKIR_BASE_URL')
                . '/calculate/domestic-cost',
                [
                    'origin' => env('RAJAONGKIR_ORIGIN'),
                    'destination' => $destinationId,
                    'weight' => $totalBerat,
                    'courier' => $request->courier
                ]
            );

        $services = collect($response['data'])
            ->reject(function ($item) {

                return str_contains(
                    strtoupper($item['service']),
                    'JTR'
                );

            })
            ->values();

        $json = $response->json();

        $services = collect($json['data'] ?? [])
            ->reject(function ($item) {

                return str_contains(
                    strtoupper($item['service']),
                    'JTR'
                );

            })
            ->values();

        return response()->json([
            'meta' => $json['meta'] ?? [],
            'data' => $services
        ]);
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('keranjang.index');
        }

        foreach ($cart as $item) {
            $produk = Produk::find($item['id_produk']);
            if (!$produk || $produk->stok < $item['qty']) {
                return redirect()
                    ->route('keranjang.index')
                    ->with('error', 'Stok ' . ($produk->nama_produk ?? 'produk') . ' tidak mencukupi. Tersedia: ' . ($produk->stok ?? 0));
            }
        }

        DB::beginTransaction();

        try {

            $subtotal = 0;

            foreach ($cart as $item) {

                $subtotal +=
                    $item['harga']
                    *
                    $item['qty'];
            }

            $idPesanan = 'RC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            $pesanan = Pesanan::create([

                'id_pesanan' =>
                    $idPesanan,

                'id_user' =>
                    Auth::user()->id_user,

                'tgl_pesanan' =>
                    now(),

                'status_pesanan' =>
                    'pending',

                'total_harga_produk' =>
                    $subtotal,

                'total_ongkir' =>
                    $request->ongkir ?? 0,

                'total_bayar' =>
                    $subtotal +
                    $request->ongkir,
            ]);

            foreach ($cart as $item) {

                DetailPesanan::create([

                    'id_pesanan' =>
                        $pesanan->id_pesanan,

                    'id_produk' =>
                        $item['id_produk'],

                    'quantity' =>
                        $item['qty'],

                    'harga_satuan' =>
                        $item['harga'],

                    'sub_total' =>
                        $item['harga']
                        *
                        $item['qty'],
                ]);
            }

            PengirimanProduk::create([

                'id_pesanan' =>
                    $pesanan->id_pesanan,

                'nama_kurir' =>
                    strtoupper($request->courier),

                'layanan' =>
                    $request->service,

                'status_pengiriman' =>
                    'menunggu',
            ]);

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
                    'first_name' => Auth::user()->nama,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->no_telp,
                ],

                'expiry' => [
                    'unit' => 'minute',
                    'duration' => 15
                ],

                'callbacks' => [
                    'finish' => route('payment.finish', $pesanan->id_pesanan),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            Pembayaran::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'snap_token' => $snapToken,
                'metode_pembayaran' => 'midtrans',
                'status_pembayaran' => 'pending',
            ]);


            DB::commit();

            session()->forget('cart');
            session()->forget('cart_count');

            return view(
                'frontend.customer.checkout.payment',
                compact(
                    'snapToken',
                    'pesanan'
                )
            );

        } catch (\Exception $e) {

            DB::rollBack();

            dd(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
        }
    }
}