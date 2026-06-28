@extends('v_layouts.app')

@section('title', 'Republik Casual')

@section('content')

{{-- HERO --}}
<section class="relative py-24 lg:py-32">

    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <div>

                <span class="inline-flex px-4 py-2 rounded-full glass text-xs uppercase tracking-[4px]">
                    Republik Casual
                </span>

                <h1 class="mt-8 text-6xl lg:text-8xl font-black leading-none tracking-tight">
                    Modern
                    <br>
                    Streetwear
                    <br>
                    Fashion.
                </h1>

                <p class="mt-8 text-lg text-neutral-500 max-w-xl">
                    Premium fashion dengan desain clean, modern,
                    dan nyaman digunakan sehari-hari.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">

                    <a
                        href="{{ route('produk.index') }}"
                        class="px-8 py-4 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-semibold">
                        Belanja Sekarang
                    </a>

                    <a
                        href="#kategori"
                        class="px-8 py-4 rounded-2xl border">
                        Lihat Kategori
                    </a>

                </div>

            </div>

            <div>

                <div class="overflow-hidden rounded-[40px]">

                    <img
                        src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1200"
                        class="w-full h-[700px] object-cover"
                        alt="Republik Casual">

                </div>

            </div>

        </div>

    </div>

</section>

{{-- KATEGORI --}}
<section id="kategori" class="py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <p class="uppercase tracking-[4px] text-sm text-neutral-500">
                Explore
            </p>

            <h2 class="text-5xl font-black mt-3">
                Kategori Produk
            </h2>

        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="glass rounded-[32px] p-10 text-center">
                <h3 class="text-2xl font-bold">
                    T-Shirt
                </h3>
            </div>

            <div class="glass rounded-[32px] p-10 text-center">
                <h3 class="text-2xl font-bold">
                    Hoodie
                </h3>
            </div>

            <div class="glass rounded-[32px] p-10 text-center">
                <h3 class="text-2xl font-bold">
                    Jacket
                </h3>
            </div>

        </div>

    </div>

</section>

{{-- PROMO --}}
<section class="py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="glass rounded-[40px] overflow-hidden">

            <div class="grid lg:grid-cols-2">

                <div class="p-12 flex flex-col justify-center">

                    <span class="uppercase tracking-[4px] text-sm text-neutral-500">
                        Limited Batch
                    </span>

                    <h2 class="text-5xl font-black mt-5">
                        Soft Essentials Collection
                    </h2>

                    <p class="mt-6 text-neutral-500">
                        Koleksi premium dengan desain minimalis
                        dan material terbaik.
                    </p>

                    <div class="mt-8">

                        <a
                            href="{{ route('produk.index') }}"
                            class="inline-flex px-8 py-4 rounded-2xl bg-black text-white dark:bg-white dark:text-black">
                            Shop Collection
                        </a>

                    </div>

                </div>

                <div>

                    <img
                        src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=1200"
                        class="w-full h-full object-cover"
                        alt="Collection">

                </div>

            </div>

        </div>

    </div>

</section>

{{-- CTA --}}
<section class="py-20">

    <div class="max-w-5xl mx-auto px-6 text-center">

        <h2 class="text-5xl font-black">
            Temukan Produk Favoritmu
        </h2>

        <p class="mt-6 text-neutral-500">
            Jelajahi seluruh koleksi Republik Casual sekarang.
        </p>

        <div class="mt-10">

            <a
                href="{{ route('produk.index') }}"
                class="px-10 py-5 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-semibold">
                Lihat Semua Produk
            </a>

        </div>

    </div>

</section>

@endsection