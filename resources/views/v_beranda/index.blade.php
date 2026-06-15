@extends('v_layouts.app')

@section('title', 'Republik Casual — Selected Archives')

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
    /* Kustom scrollbar untuk box chat agar estetik */
    .chat-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .chat-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
</style>
@endpush

@section('content')

{{-- GRID BACKGROUND OVERLAY --}}
<div class="fixed inset-0 grid-noise opacity-[0.4] pointer-events-none z-0"></div>

<main class="relative z-10 max-w-7xl mx-auto px-6 pt-32 pb-24 min-h-[90vh] flex flex-col justify-between">
    
    {{-- 01. HERO STATEMENT --}}
    <section class="max-w-4xl">
        <div class="inline-flex items-center gap-3 glass rounded-full px-4 py-2 text-[10px] uppercase tracking-[0.35em] text-neutral-400 font-medium mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-neutral-900 dark:bg-neutral-100"></span>
            Established 2024 / Jakarta
        </div>

        <h1 class="text-[clamp(2.5rem,6.5vw,5.5rem)] font-black tracking-[-0.06em] leading-[0.92] text-neutral-950 dark:text-neutral-50">
            Quietly confident. <br>
            <span class="italic font-light font-serif text-neutral-400 dark:text-neutral-500">Loudly premium.</span>
        </h1>

        <p class="mt-8 max-w-xl text-base sm:text-lg leading-relaxed text-neutral-500 dark:text-neutral-400">
            Kami tidak mendesain tren. Kami mengurasi sistem pakaian esensial dengan potongan kontemporer tegap, material berat tinggi, dan detail yang sengaja disembunyikan.
        </p>
    </section>

    {{-- 02. CATEGORICAL PORTAL ARCHITECTURE (Pintu Masuk Navigasi) --}}
    <section class="mt-16 lg:mt-24">
        <div class="grid md:grid-cols-12 gap-6 items-stretch">
            
            {{-- SEKSI KATALOG UTAMA (Arahkan ke Index Produk) --}}
            <div class="md:col-span-7 glass rounded-[36px] p-8 border border-neutral-200/40 dark:border-neutral-800/40 flex flex-col justify-between group min-h-[340px] relative overflow-hidden">
                <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-neutral-100 dark:bg-neutral-900 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition duration-500"></div>
                
                <div>
                    <span class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase block">01 / Commercial Shop</span>
                    <h2 class="text-3xl font-black text-neutral-950 dark:text-neutral-50 mt-4 tracking-tight leading-tight">
                        Katalog Produk <br>& Koleksi Aktif
                    </h2>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2 max-w-sm">
                        Jelajahi seluruh rilisan *batch* pakaian, pakaian luar, dan aksesori dasar premium kami.
                    </p>
                </div>

                <div class="mt-8 relative z-10">
                    <a href="{{ url('/products') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-neutral-950 text-neutral-50 dark:bg-neutral-50 dark:text-neutral-950 font-semibold text-xs uppercase tracking-wider shadow-lg hover:bg-neutral-800 dark:hover:bg-neutral-200 transition">
                        Buka Katalog Belanja →
                    </a>
                </div>
            </div>

            {{-- SEKSI EDITORIAL LOOKBOOK (Arahkan ke Index Jurnal/Kampanye) --}}
            <div class="md:col-span-5 glass rounded-[36px] p-8 border border-neutral-200/40 dark:border-neutral-800/40 flex flex-col justify-between group min-h-[340px]">
                <div>
                    <span class="text-[10px] font-mono tracking-widest text-neutral-400 uppercase block">02 / Editorial Archive</span>
                    <h2 class="text-2xl font-bold text-neutral-950 dark:text-neutral-50 mt-4 tracking-tight">
                        Jurnal Kampanye & <br>Arah Estetika
                    </h2>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-2 leading-relaxed">
                        Esai visual, proses kurasi material kain di balik layar, dan pandangan gaya hidup dari sudut pandang Republik Casual.
                    </p>
                </div>

                <div class="mt-8">
                    <a href="{{ url('/editorial') }}" class="w-full text-center block px-5 py-3.5 rounded-xl border border-neutral-200/60 dark:border-neutral-800/60 text-xs font-semibold uppercase tracking-wider text-neutral-800 dark:text-neutral-200 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition">
                        Lihat Jurnal Visual
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- 03. SYSTEM FOOTER (Status Hub) --}}
    <footer class="mt-20 pt-8 border-t border-neutral-200/50 dark:border-neutral-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-[10px] font-mono text-neutral-400 uppercase tracking-widest">
            © 2026 Republik Casual. All Rights Reserved.
        </div>
        <div class="flex items-center gap-6">
            <a href="{{ url('/about') }}" class="text-xs text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition">Tentang Kami</a>
            <a href="{{ url('/contact') }}" class="text-xs text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition">Kontak</a>
        </div>
    </footer>

</main>
@endsection