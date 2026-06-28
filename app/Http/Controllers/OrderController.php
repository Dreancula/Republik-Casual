<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class OrderController extends Controller
{
    public function viewCart()
    {
        $cart = session()->get('cart', []);

        return view(
            'frontend.customer.keranjang.index',
            compact('cart')
        );
    }
    public function addToCart(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $cart = session()->get('cart', []);

        $qtyDiCart = isset($cart[$id]) ? $cart[$id]['qty'] : 0;
        $totalQty = $qtyDiCart + $request->qty;

        if ($totalQty > $produk->stok) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', "Stok {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok}");
        }

        if (isset($cart[$id])) {

            $cart[$id]['qty'] = $totalQty;

        } else {

            $cart[$id] = [
                'id_produk' => $produk->id_produk,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'foto_produk' => $produk->foto_produk,
                'qty' => $request->qty
            ];
        }

        session()->put('cart', $cart);

        session()->put(
            'cart_count',
            collect($cart)->sum('qty')
        );

        return redirect()
            ->route('keranjang.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan ke keranjang'
            );
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $qty = (int) $request->qty;

            if ($qty < 1) {
                $qty = 1;
            }

            $cart[$id]['qty'] = $qty;

            session()->put('cart', $cart);
        }

        return back();
    }
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            unset($cart[$id]);

            session()->put('cart', $cart);

            session()->put(
                'cart_count',
                collect($cart)->sum('qty')
            );
        }

        return back();
    }
}