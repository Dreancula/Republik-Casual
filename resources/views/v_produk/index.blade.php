@extends('v_layouts.app')

@section('title', 'Katalog Pakaian Premium — Republik Casual')

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
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')

{{-- GRID BACKGROUND OVERLAY --}}
<div class="fixed inset-0 grid-noise opacity-[0.4] pointer-events-none z-0"></div>

<div class="max-w-7xl mx-auto px-6 pt-28 pb-24 relative z-10">
    
    {{-- 01. FRONTEND SHOP HEADER --}}
    <header class="border-b border-neutral-200/60 dark:border-neutral-800/60 pb-8 mb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <span class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase block">Shop Collection / Batch 04</span>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-neutral-900 dark:text-neutral-50 mt-2">
                    Arsip Pakaian Utama
                </h1>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-xs font-mono text-neutral-400">Menampilkan Koleksi Pilihan</span>
            </div>
        </div>

        {{-- FRONTEND KATEGORI SWITCHER --}}
        {{-- Di Controller nanti Anda bisa menangkap ini dengan request('category') --}}
        <div class="mt-8 flex gap-2 overflow-x-auto no-scrollbar pb-2">
            <a href="{{ url('/products') }}" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-semibold bg-neutral-950 text-white dark:bg-white dark:text-black transition shrink-0">
                Semua Koleksi
            </a>
            <a href="{{ url('/products?category=t-shirt') }}" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition shrink-0">
                T-Shirt
            </a>
            <a href="{{ url('/products?category=crewneck') }}" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition shrink-0">
                Crewneck
            </a>
            <a href="{{ url('/products?category=pants') }}" class="px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 transition shrink-0">
                Celana
            </a>
        </div>
    </header>

    {{-- 02. DYNAMIC FRONTEND PRODUCTS GRID --}}
    <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
        {{-- Variabel tunggal $products yang dikirim dari ProductController Anda nantinya --}}
        @php
            $mockProducts = [
                ['id' => 1, 'title' => 'Essential Oversized Heavy Tee', 'price' => 'Rp 249.000', 'tag' => 'Best Seller', 'fabric' => '245 GSM Open End Cotton', 'color' => 'Washed Black'],
                ['id' => 2, 'title' => 'Structured Boxy Crewneck', 'price' => 'Rp 389.000', 'tag' => 'Limited Drop', 'fabric' => '330 GSM Terry Fleece', 'color' => 'Heather Grey'],
                ['id' => 3, 'title' => 'Minimalist Relaxed Sweatpants', 'price' => 'Rp 419.000', 'tag' => 'Few Left', 'fabric' => '330 GSM Heavy Terry', 'color' => 'Olive Drab'],
                ['id' => 4, 'title' => 'Signature Interlock Boxy Tee', 'price' => 'Rp 269.000', 'tag' => 'New Release', 'fabric' => '260 GSM Double Knit', 'color' => 'Chalk White'],
            ];
        @endphp

        @foreach($mockProducts as $product)
        <article class="group flex flex-col justify-between">
            <div>
                {{-- Foto Produk --}}
                <div class="relative aspect-[3/4] overflow-hidden rounded-[24px] bg-neutral-100 dark:bg-neutral-900 border border-neutral-200/40 dark:border-neutral-800/40">
                    <img
                        src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80"
                        class="w-full h-full object-cover object-top transition duration-700 group-hover:scale-103"
                        alt="{{ $product['title'] }}"
                    >
                    
                    <div class="absolute top-4 left-4">
                        <span class="glass px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-neutral-800 dark:text-neutral-200 border border-white/20">
                            {{ $product['tag'] }}
                        </span>
                    </div>

                    {{-- Link Masuk Ke Halaman Detail Produk Pembeli --}}
                    <div class="absolute inset-x-4 bottom-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition duration-300">
                        <a href="{{ url('/products/'.$product['id']) }}" class="w-full block text-center py-3 px-4 glass hover:bg-white dark:hover:bg-neutral-950 text-neutral-900 dark:text-neutral-50 rounded-xl text-xs font-semibold uppercase tracking-wider transition shadow-md">
                            Periksa Detail
                        </a>
                    </div>
                </div>

                {{-- Deskripsi Produk Singkat --}}
                <div class="mt-4 px-1">
                    <h3 class="font-bold text-neutral-800 dark:text-neutral-100 tracking-tight text-base group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition">
                        <a href="{{ url('/products/'.$product['id']) }}">{{ $product['title'] }}</a>
                    </h3>
                    
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1 flex items-center gap-2">
                        <span>{{ $product['fabric'] }}</span>
                        <span>•</span>
                        <span>{{ $product['color'] }}</span>
                    </p>
                </div>
            </div>

            {{-- Harga Komersial --}}
            <div class="mt-3 pt-3 border-t border-dashed border-neutral-200 dark:border-neutral-800 flex items-center justify-between px-1">
                <span class="font-black text-neutral-900 dark:text-neutral-100">{{ $product['price'] }}</span>
                <span class="text-[9px] font-mono tracking-wider text-neutral-400 uppercase">Batch_04</span>
            </div>
        </article>
        @endforeach
    </section>

    {{-- 03. FRONTEND PAGINATION INTERFACE --}}
    <nav class="mt-20 border-t border-neutral-200/60 dark:border-neutral-800/60 pt-6 flex items-center justify-between">
        <div class="text-xs text-neutral-400 font-mono">
            Halaman 1 dari 1
        </div>
        
        <div class="flex items-center gap-1">
            <span class="px-3.5 py-2 rounded-lg bg-neutral-950 text-white dark:bg-white dark:text-black text-xs font-mono font-bold">1</span>
        </div>
    </nav>

</div>

@endsection