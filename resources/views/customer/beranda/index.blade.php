@extends('v_layouts.app')

@section('title','Republik Casual')

@section('content')

<section class="py-24">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-10 items-center">

            <div>
                <span class="text-sm uppercase tracking-[5px]">
                    Republik Casual
                </span>

                <h1 class="mt-6 text-6xl font-black leading-none">
                    Modern Streetwear
                    For Everyday.
                </h1>

                <p class="mt-6 text-neutral-500 text-lg">
                    Premium fashion dengan desain minimal,
                    clean dan modern.
                </p>

                <div class="mt-8 flex gap-4">

                    <a href="{{ route('produk.index') }}"
                        class="px-8 py-4 bg-black text-white rounded-full">

                        Belanja Sekarang
                    </a>

                    <a href="#produk"
                        class="px-8 py-4 border rounded-full">

                        Lihat Produk
                    </a>

                </div>

            </div>

            <div>
                <img
                    src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f"
                    class="rounded-3xl h-[600px] w-full object-cover">
            </div>

        </div>

    </div>
</section>

<section id="produk" class="py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center mb-10">

            <div>

                <p class="uppercase tracking-[4px] text-sm">
                    Produk Terbaru
                </p>

                <h2 class="text-4xl font-black mt-2">
                    New Arrivals
                </h2>

            </div>

            <a href="{{ route('produk.index') }}">
                Lihat Semua →
            </a>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($produkTerbaru as $produk)

            <div class="group">

                <a href="{{ route('produk.show',$produk->id_produk) }}">

                    <div class="overflow-hidden rounded-3xl">

                        <img
                            src="{{ asset('storage/'.$produk->foto_produk) }}"
                            class="h-96 w-full object-cover group-hover:scale-105 transition">

                    </div>

                    <div class="mt-4">

                        <h3 class="font-semibold text-lg">
                            {{ $produk->nama_produk }}
                        </h3>

                        <p class="text-neutral-500 mt-1">
                            {{ $produk->size_produk }}
                        </p>

                        <p class="mt-3 text-xl font-bold">
                            Rp {{ number_format($produk->harga_produk,0,',','.') }}
                        </p>

                    </div>

                </a>

            </div>

            @empty

            <div class="col-span-4">

                <div class="border rounded-3xl p-10 text-center">

                    Belum ada produk.

                </div>

            </div>

            @endforelse

        </div>

    </div>

</section>

@endsection