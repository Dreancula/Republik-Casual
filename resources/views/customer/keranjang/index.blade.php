@extends('v_layouts.app')

@section('title', 'Keranjang Belanja — Republik Casual')

@push('styles')
<style>
    .grid-noise {
        background-image:
            linear-gradient(rgba(0,0,0,.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,.02) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .dark .grid-noise {
        background-image:
            linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
    }
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        background: rgba(0,0,0,.04);
        border: 1px solid rgba(0,0,0,.06);
        cursor: pointer;
        transition: all 200ms ease;
        user-select: none;
    }
    .dark .qty-btn {
        background: rgba(255,255,255,.06);
        border-color: rgba(255,255,255,.08);
    }
    .qty-btn:hover {
        background: rgba(0,0,0,.1);
    }
    .dark .qty-btn:hover {
        background: rgba(255,255,255,.12);
    }
    .cart-item {
        transition: transform 300ms ease, box-shadow 300ms ease;
    }
    .cart-item:hover {
        transform: translateY(-2px);
    }
    .summary-sticky {
        position: sticky;
        top: 100px;
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .empty-state {
        animation: fadeSlideUp 500ms ease both;
    }
</style>
@endpush

@section('content')

<div class="fixed inset-0 grid-noise opacity-[0.4] pointer-events-none z-0"></div>

<div class="relative z-10 max-w-7xl mx-auto px-6 pt-28 pb-24">

    {{-- HEADER --}}
    <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-8 mb-10 border-b border-neutral-200/60 dark:border-neutral-800/60">
        <div>
            <span class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase block">Shopping Bag</span>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-neutral-900 dark:text-neutral-50 mt-2">
                Keranjang Belanja
            </h1>
        </div>
        @if($keranjang && $keranjang->detailKeranjang->count() > 0)
            <div class="text-xs text-neutral-500 font-mono">
                {{ $keranjang->detailKeranjang->count() }} item
            </div>
        @endif
    </header>

    @if($keranjang && $keranjang->detailKeranjang->count() > 0)

        <div class="grid lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT: CART ITEMS --}}
            <section class="lg:col-span-8 space-y-4">

                @php $totalHarga = 0; @endphp
                @foreach($keranjang->detailKeranjang as $item)
                    @php $subtotal = $item->sub_total; $totalHarga += $subtotal; @endphp

                    <div class="glass rounded-[28px] p-4 sm:p-6 cart-item">
                        <div class="flex gap-5 sm:gap-6">

                            {{-- PRODUCT IMAGE --}}
                            <div class="shrink-0">
                                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-[20px] overflow-hidden bg-neutral-100 dark:bg-neutral-900 border border-neutral-200/40 dark:border-neutral-800/40">
                                    @if($item->produk->foto_produk)
                                        <img src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto_produk) }}"
                                             alt="{{ $item->produk->nama_produk }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-neutral-400 text-xs">No Image</div>
                                    @endif
                                </div>
                            </div>

                            {{-- DETAILS + CONTROLS --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-neutral-900 dark:text-neutral-100 truncate">
                                                {{ $item->produk->nama_produk }}
                                            </h3>
                                            <p class="text-xs text-neutral-500 mt-1">
                                                {{ $item->produk->size_produk ?? 'Regular' }} / {{ $item->produk->berat }}g
                                            </p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="font-black text-neutral-900 dark:text-neutral-100">
                                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-4 mt-4 pt-3 border-t border-neutral-200/50 dark:border-neutral-800/50">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('keranjang.update', $item->id_detail_keranjang) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepDown(); this.parentNode.submit();">−</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                   class="w-12 text-center bg-transparent border-0 font-semibold text-sm outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                   onchange="this.form.submit()">
                                            <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepUp(); this.parentNode.submit();">+</button>
                                        </form>

                                        <span class="text-sm font-semibold text-neutral-600 dark:text-neutral-400">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <form action="{{ route('keranjang.remove', $item->id_detail_keranjang) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs text-neutral-400 hover:text-red-500 transition font-medium uppercase tracking-wider"
                                                onclick="return confirm('Hapus item ini dari keranjang?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                @endforeach

            </section>

            {{-- RIGHT: ORDER SUMMARY --}}
            <aside class="lg:col-span-4">
                <div class="summary-sticky">
                    <div class="glass rounded-[28px] p-6 sm:p-8">
                        <h3 class="font-bold text-lg text-neutral-900 dark:text-neutral-100 mb-6">
                            Ringkasan Belanja
                        </h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Subtotal</span>
                                <span class="font-semibold text-neutral-900 dark:text-neutral-100">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-neutral-600 dark:text-neutral-400">Ongkos Kirim</span>
                                <span class="text-neutral-500">Dihitung saat checkout</span>
                            </div>

                            <div class="border-t border-neutral-200/60 dark:border-neutral-800/60 pt-4 mt-4">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-neutral-900 dark:text-neutral-100">Total</span>
                                    <span class="font-black text-xl text-neutral-900 dark:text-neutral-100">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ url('/checkout') }}" method="GET" class="mt-8">
                            <button type="submit"
                                    class="w-full py-4 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-semibold text-sm uppercase tracking-wider hover:scale-[1.02] transition duration-300 shadow-lg">
                                Lanjut ke Checkout
                            </button>
                        </form>

                        <a href="{{ route('produk.index') }}"
                           class="block text-center mt-4 text-xs text-neutral-500 hover:text-neutral-900 dark:hover:text-neutral-100 transition font-medium uppercase tracking-wider">
                            Lanjut Belanja
                        </a>
                    </div>

                    {{-- SHIPPING NOTE --}}
                    <div class="mt-4 glass rounded-[20px] p-5 text-xs text-neutral-500 leading-relaxed">
                        <span class="font-semibold text-neutral-700 dark:text-neutral-300 block mb-1">Informasi Pengiriman</span>
                        Ongkos kirim akan dihitung setelah kamu memilih alamat pengiriman di halaman berikutnya.
                    </div>
                </div>
            </aside>

        </div>

    @else

        {{-- EMPTY STATE --}}
        <div class="empty-state max-w-lg mx-auto text-center py-20">
            <div class="w-20 h-20 rounded-[24px] bg-neutral-100 dark:bg-neutral-900 flex items-center justify-center mx-auto mb-8 border border-neutral-200/40 dark:border-neutral-800/40">
                <svg class="w-10 h-10 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>

            <h2 class="text-2xl font-black text-neutral-900 dark:text-neutral-100 tracking-tight">
                Keranjangmu Masih Sepi
            </h2>
            <p class="text-neutral-500 mt-3 max-w-sm mx-auto leading-relaxed">
                Kelihatannya kamu belum memasukkan produk apapun. Yuk, lihat koleksi terbaru kami.
            </p>

            <a href="{{ route('produk.index') }}"
               class="inline-flex items-center gap-2 mt-8 px-8 py-4 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-semibold text-sm uppercase tracking-wider hover:scale-[1.02] transition duration-300 shadow-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Mulai Belanja
            </a>
        </div>

    @endif

</div>

@endsection
