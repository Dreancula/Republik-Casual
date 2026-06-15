@extends('v_layouts.app')
@section('content')
<!-- template -->

<!-- STORE -->
<div id="store">
    <!-- row -->
    <div class="row">
        @foreach ($produk as $row)
        <!-- Product Single -->
        <div class="col-md-4 col-sm-6 col-xs-6">
            <div class="product product-single">
                <div class="product-thumb">
                    <div class="product-label">
                        <span>Kategori</span>
                        <span class="sale">{{ $row->kategori->nama_kategori }}</span>
                    </div>

                    <a href="{{ route('produk.detail', $row->id) }}">
                        <button class="main-btn quick-view"><i class="fa fa-search-plus"></i> Detail Produk</button>
                    </a>
                    <img src="{{ asset('storage/img-produk/thumb_md_' . $row->foto) }}" alt="">
                </div>
                <div class="product-body">
                    <h3 class="product-price"> Rp. {{ number_format($row-<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products — Republik Casual</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        html,
        body{
            margin: 0;
            padding: 0;
        }

        *{
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        html{
            scroll-behavior: smooth;
        }

        body{
            overflow: hidden;
        }

        .theme-transition,
        .theme-transition *,
        .theme-transition *::before,
        .theme-transition *::after{
            transition-property:
                background-color,
                border-color,
                color,
                fill,
                stroke,
                opacity,
                box-shadow,
                transform;
            transition-duration: 450ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass{
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .card-hover{
            transition: .3s ease;
        }

        .card-hover:hover{
            transform: translateY(-5px);
        }

        .fade-up{
            animation: fadeUp .6s ease forwards;
        }

        @keyframes fadeUp{
            from{
                opacity: 0;
                transform: translateY(18px);
            }
            to{
                opacity: 1;
                transform: translateY(0);
            }
        }

        ::-webkit-scrollbar{
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-thumb{
            background: rgba(120,120,120,.4);
            border-radius: 999px;
        }
    </style>
</head>

<body class="h-screen overflow-hidden bg-[#f4f4f4] dark:bg-[#050505] theme-transition">

<div class="flex h-screen w-full overflow-hidden">

    {{-- SIDEBAR --}}
    @include('components.sidebar')

    <!-- MAIN -->
    <main class="flex-1 h-screen overflow-y-auto">

        {{-- TOPBAR --}}
        @include('components.topbar', [
            'title' => 'Products',
            'subtitle' => 'Kelola seluruh katalog produk Republik Casual.',
            'search' => 'Search products...',
            'button' => '+ Add Product'
        ])

        <div class="w-full px-6 md:px-10 pb-10">

            <!-- HERO -->
            <section class="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#111111] to-[#1c1c1c] p-10 md:p-14 mb-8 text-white fade-up">

                <div class="absolute right-[-100px] top-[-100px] w-[320px] h-[320px] rounded-full bg-white/10 blur-3xl"></div>

                <div class="relative z-10 w-full">

                    <p class="uppercase tracking-[0.4em] text-sm text-white/40 mb-5">
                        Premium Collection
                    </p>

                    <h2 class="text-4xl md:text-6xl font-bold leading-tight">
                        Organize Your Fashion Products Easily.
                    </h2>

                    <p class="mt-6 text-white/60 text-lg leading-relaxed max-w-2xl">
                        Kelola hoodie, t-shirt, pants, dan seluruh koleksi Republik Casual dengan dashboard modern premium.
                    </p>

                </div>

            </section>

            <!-- ACTION BAR -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8 fade-up">

                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('products') }}"
                       class="px-5 py-3 rounded-2xl bg-[#111111] dark:bg-white text-white dark:text-black font-medium">
                        All Products
                    </a>

                    <a href="{{ route('products') }}?category=hoodie"
                       class="px-5 py-3 rounded-2xl bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 text-[#111111] dark:text-white">
                        Hoodie
                    </a>

                    <a href="{{ route('products') }}?category=tshirt"
                       class="px-5 py-3 rounded-2xl bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 text-[#111111] dark:text-white">
                        T-Shirt
                    </a>

                    <a href="{{ route('products') }}?category=pants"
                       class="px-5 py-3 rounded-2xl bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 text-[#111111] dark:text-white">
                        Pants
                    </a>

                </div>

            </div>

            <!-- STATS -->
            <section class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8 fade-up">

                <div class="card-hover glass bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 rounded-[30px] p-7 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="w-16 h-16 rounded-3xl bg-[#111111] dark:bg-white text-white dark:text-black flex items-center justify-center text-2xl">
                            🛍️
                        </div>

                        <span class="text-green-500 font-semibold">
                            +12%
                        </span>

                    </div>

                    <p class="mt-8 text-black/40 dark:text-white/40">
                        Total Products
                    </p>

                    <h3 class="text-5xl font-bold mt-2 text-[#111111] dark:text-white">
                        {{ $products->total() }}
                    </h3>

                </div>

                <div class="card-hover glass bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 rounded-[30px] p-7 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="w-16 h-16 rounded-3xl bg-[#111111] dark:bg-white text-white dark:text-black flex items-center justify-center text-2xl">
                            🔥
                        </div>

                        <span class="text-green-500 font-semibold">
                            +8%
                        </span>

                    </div>

                    <p class="mt-8 text-black/40 dark:text-white/40">
                        Best Seller
                    </p>

                    <h3 class="text-5xl font-bold mt-2 text-[#111111] dark:text-white">
                        24
                    </h3>

                </div>

                <div class="card-hover glass bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 rounded-[30px] p-7 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="w-16 h-16 rounded-3xl bg-[#111111] dark:bg-white text-white dark:text-black flex items-center justify-center text-2xl">
                            📦
                        </div>

                        <span class="text-green-500 font-semibold">
                            +19%
                        </span>

                    </div>

                    <p class="mt-8 text-black/40 dark:text-white/40">
                        In Stock
                    </p>

                    <h3 class="text-5xl font-bold mt-2 text-[#111111] dark:text-white">
                        97
                    </h3>

                </div>

                <div class="card-hover glass bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 rounded-[30px] p-7 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="w-16 h-16 rounded-3xl bg-[#111111] dark:bg-white text-white dark:text-black flex items-center justify-center text-2xl">
                            ⭐
                        </div>

                        <span class="text-green-500 font-semibold">
                            +4%
                        </span>

                    </div>

                    <p class="mt-8 text-black/40 dark:text-white/40">
                        Avg Rating
                    </p>

                    <h3 class="text-5xl font-bold mt-2 text-[#111111] dark:text-white">
                        4.9
                    </h3>

                </div>

            </section>

            <!-- PRODUCTS GRID -->
            <section class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 fade-up">

                @forelse($products as $product)

                    <article class="group relative card-hover glass bg-white dark:bg-[#0b0b0b] border border-black/5 dark:border-white/10 rounded-[30px] overflow-hidden">

                        <a href="{{ route('products.show', $product->id_produk) }}" class="block">
                            <div class="relative">
                                <img
                                    src="{{ $product->foto_produk ? asset($product->foto_produk) : 'https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=1200&auto=format&fit=crop' }}"
                                    class="w-full h-72 object-cover"
                                    alt="{{ $product->nama_produk }}"
                                >

                                <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-black">
                                    {{ ucfirst($product->kategori->nama_kategori ?? 'Product') }}
                                </div>
                            </div>
                        </a>

                        <div class="p-6">

                            <div class="flex items-start justify-between gap-4">

                                <div>
                                    <h3 class="text-2xl font-bold text-[#111111] dark:text-white">
                                        {{ $product->nama_produk }}
                                    </h3>

                                    <p class="mt-2 text-black/40 dark:text-white/40">
                                        {{ $product->detail ?? 'Premium fashion item from Republik Casual.' }}
                                    </p>
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $product->status
                                        ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                    {{ $product->status ? '🟢 Active' : '🔴 Inactive' }}
                                </span>

                            </div>

                            <div class="mt-6 flex items-center justify-between">

                                <div>
                                    <p class="text-sm text-black/40 dark:text-white/40">
                                        Price
                                    </p>

                                    <h4 class="text-3xl font-bold text-[#111111] dark:text-white">
                                        Rp {{ number_format($product->harga ?? 0, 0, ',', '.') }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-2">

                                    <a href="{{ route('products.edit', $product->id_produk) }}"
                                       class="px-4 py-3 rounded-xl bg-black/5 dark:bg-white/5 text-[#111111] dark:text-white hover:bg-black/10 dark:hover:bg-white/10 transition-all duration-300">
                                        Edit
                                    </a>


                                </div>

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="xl:col-span-3 rounded-[32px] border border-black/5 dark:border-white/10 bg-white dark:bg-[#0b0b0b] p-10 text-center">
                        <h3 class="text-2xl font-bold text-[#111111] dark:text-white">
                            Belum ada produk
                        </h3>
                        <p class="mt-3 text-black/40 dark:text-white/40">
                            Tambahkan produk pertama kamu untuk mulai mengelola katalog.
                        </p>
                    </div>

                @endforelse

            </section>

            <!-- PAGINATION -->
            <div class="mt-10">
                {{ $products->links() }}
            </div>

        </div>

    </main>

</div>

</body>
</html>>harga, 0, ',', '.') }} <span
                            class="product-old-price">{{ $row->kategori->nama_kategori }}</span></h3>

                    <h2 class="product-name"><a href="#">{{ $row->nama_produk }}</a></h2>
                    <div class="product-btns">
                        <a href="{{ route('produk.detail', $row->id) }}" title="Detail Produk">
                            <button class="main-btn icon-btn"><i class="fa fa-search-plus"></i></button>
                        </a>
                        <form action="3" method="post"
                            style="display: inline-block;" title="Pesan Ke Aplikasi">
                            @csrf
                            <button type="submit" class="primary-btn add-to-cart"><i
                                    class="fa fa-shopping-cart"></i> Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Product Single -->
        @endforeach
        <div class="clearfix visible-md visible-lg visible-sm visible-xs"></div>

    </div>
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $produk->links() }}
    </div>
    <!-- /Pagination -->
    <!-- /row -->
</div>
<!-- /STORE -->

<!-- end template-->
@endsection

