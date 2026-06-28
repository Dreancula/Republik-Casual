@extends('v_layouts.app')

@section('title', 'Republik Casual')

@push('styles')
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .marquee-track {
        animation: marquee 26s linear infinite;
        width: max-content;
    }

    .grid-noise {
        background-image:
            linear-gradient(rgba(0,0,0,.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,.035) 1px, transparent 1px);
        background-size: 42px 42px;
    }

    html.dark .grid-noise {
        background-image:
            linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
    }

    .hero-card {
        transition: transform 300ms ease, opacity 300ms ease;
    }

    .hero-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')

{{-- HERO / AUTOSLIDER --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0 grid-noise opacity-[0.28] pointer-events-none"></div>
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[900px] h-[900px] rounded-full bg-black/5 dark:bg-white/5 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 py-8 lg:py-12">
        <div class="grid lg:grid-cols-12 gap-8 items-stretch">

            <div class="lg:col-span-5 relative z-10 flex flex-col justify-center">
                <div class="inline-flex items-center gap-3 glass rounded-full px-4 py-2 text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">
                    <span class="w-2 h-2 rounded-full bg-black dark:bg-white"></span>
                    Limited Drop • Premium Batch
                </div>

                <h1 class="mt-8 text-[clamp(3.4rem,7.5vw,7.4rem)] font-black tracking-[-0.08em] leading-[0.88]">
                    Timeless
                    <br>
                    <span class="italic font-light tracking-[-0.08em]">casual</span>
                    <br>
                    for every day.
                </h1>

                <p class="mt-8 max-w-2xl text-base sm:text-lg leading-relaxed text-neutral-600 dark:text-neutral-400">
                    Republik Casual menghadirkan fashion premium dengan pendekatan yang tenang, tegas, dan relevan.
                    Dirancang agar terlihat mahal tanpa terlihat berusaha keras.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="#products" class="px-8 py-4 rounded-full bg-black text-white dark:bg-white dark:text-black font-medium transition duration-300 hover:-translate-y-0.5">
                        Shop the Drop
                    </a>
                    <a href="#lookbook" class="px-8 py-4 rounded-full glass font-medium transition duration-300 hover:-translate-y-0.5">
                        Explore Lookbook
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-3 gap-4 max-w-xl">
                    <div class="glass rounded-[24px] p-5 hero-card">
                        <div class="text-2xl font-black">12+</div>
                        <div class="text-xs uppercase tracking-[0.22em] text-neutral-600 dark:text-neutral-400 mt-2">batch released</div>
                    </div>
                    <div class="glass rounded-[24px] p-5 hero-card">
                        <div class="text-2xl font-black">98%</div>
                        <div class="text-xs uppercase tracking-[0.22em] text-neutral-600 dark:text-neutral-400 mt-2">happy buyers</div>
                    </div>
                    <div class="glass rounded-[24px] p-5 hero-card">
                        <div class="text-2xl font-black">24h</div>
                        <div class="text-xs uppercase tracking-[0.22em] text-neutral-600 dark:text-neutral-400 mt-2">support</div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div x-data="heroSlider()" x-init="init()" class="relative h-[72vh] min-h-[620px]">
                    <template x-for="(slide, idx) in slides" :key="idx">
                        <div
                            x-cloak
                            x-show="active === idx"
                            x-transition.opacity.duration.600ms
                            class="absolute inset-0 rounded-[36px] overflow-hidden glass shadow-premium">
                            <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover" :alt="slide.title">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/62 via-black/18 to-black/5"></div>

                            <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-between text-white">
                                <div class="flex items-center justify-between">
                                    <span class="glass text-white/90 rounded-full px-4 py-2 text-[11px] uppercase tracking-[0.3em] bg-white/10 border-white/10">
                                        <span x-text="slide.tag"></span>
                                    </span>

                                    <div class="glass text-white/90 rounded-full px-4 py-2 text-[11px] uppercase tracking-[0.3em] bg-white/10 border-white/10">
                                        <span x-text="slide.caption"></span>
                                    </div>
                                </div>

                                <div class="max-w-xl">
                                    <p class="text-[11px] uppercase tracking-[0.35em] text-white/70" x-text="slide.kicker"></p>
                                    <h2 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-black leading-[0.95] tracking-[-0.06em]" x-text="slide.title"></h2>
                                    <p class="mt-5 max-w-lg text-white/78 leading-relaxed" x-text="slide.desc"></p>

                                    <div class="mt-8 flex flex-wrap gap-3">
                                        <a href="#" class="px-6 py-3 rounded-full bg-white text-black font-medium">
                                            <span x-text="slide.cta"></span>
                                        </a>
                                        <a href="#" class="px-6 py-3 rounded-full bg-white/10 border border-white/10 backdrop-blur-md">
                                            View details
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <template x-for="(dot, i) in slides" :key="i">
                                            <button
                                                @click="go(i)"
                                                class="h-2 rounded-full transition-all duration-300"
                                                :class="active === i ? 'w-10 bg-white' : 'w-2 bg-white/50'"
                                                aria-label="slider dot"></button>
                                        </template>
                                    </div>

                                    <div class="text-sm text-white/80">
                                        <span x-text="String(active + 1).padStart(2,'0')"></span>
                                        /
                                        <span x-text="String(slides.length).padStart(2,'0')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="absolute -bottom-6 left-6 right-6 sm:right-auto sm:max-w-sm glass rounded-[26px] p-4 shadow-premium">
                        <div class="flex items-center gap-4">
                            <img
                                src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=500&q=80"
                                class="w-16 h-16 rounded-[18px] object-cover"
                                alt="Best Seller"
                            >
                            <div class="min-w-0">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-neutral-600 dark:text-neutral-400">Best seller</p>
                                <p class="font-semibold truncate">Essential Oversized Tee</p>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">Rp 249.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- MARQUEE --}}
<section class="border-y border-neutral-200 dark:border-neutral-800 overflow-hidden">
    <div class="marquee-track flex items-center gap-10 py-4 whitespace-nowrap text-sm uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">
        <span>Republik Casual</span><span>•</span><span>Premium Fabric</span><span>•</span><span>Limited Batch</span><span>•</span><span>Quiet Luxury</span><span>•</span><span>Modern Fit</span><span>•</span>
        <span>Republik Casual</span><span>•</span><span>Premium Fabric</span><span>•</span><span>Limited Batch</span><span>•</span><span>Quiet Luxury</span><span>•</span><span>Modern Fit</span><span>•</span>
    </div>
</section>

{{-- FEATURED CATEGORIES --}}
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Featured categories</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Browse by mood</h2>
            </div>
            <p class="max-w-2xl text-neutral-600 dark:text-neutral-400 leading-relaxed">
                Kategori dibuat seperti editorial grid agar terasa lebih modern dan tidak seperti marketplace generik.
            </p>
        </div>

        <div class="grid lg:grid-cols-12 gap-5">
            <a href="#" class="lg:col-span-5 glass rounded-[32px] p-7 sm:p-8 card-hover relative overflow-hidden min-h-[280px]">
                <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(0,0,0,.03),transparent)] dark:bg-[linear-gradient(135deg,rgba(255,255,255,.04),transparent)]"></div>
                <div class="relative z-10">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">01</p>
                    <h3 class="mt-5 text-3xl font-black">Essentials</h3>
                    <p class="mt-3 text-neutral-600 dark:text-neutral-400 max-w-sm leading-relaxed">Basic pieces dengan kualitas premium dan siluet yang bersih.</p>
                </div>
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80" class="absolute right-0 bottom-0 w-[58%] h-full object-cover" alt="Essentials">
            </a>

            <a href="#" class="lg:col-span-3 glass rounded-[32px] p-7 sm:p-8 card-hover min-h-[280px] flex flex-col justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">02</p>
                    <h3 class="mt-5 text-2xl font-bold">Oversized</h3>
                </div>
                <p class="text-neutral-600 dark:text-neutral-400 leading-relaxed">Relaxed, clean, dan modern.</p>
            </a>

            <a href="#" class="lg:col-span-4 glass rounded-[32px] p-7 sm:p-8 card-hover min-h-[280px] flex flex-col justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">03</p>
                    <h3 class="mt-5 text-2xl font-bold">Outerwear</h3>
                    <p class="mt-3 text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-xs">Layering pieces yang terlihat mahal dan elegan.</p>
                </div>
                <div class="absolute inset-0 opacity-40">
                    <img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=900&q=80" class="w-full h-full object-cover" alt="Outerwear">
                </div>
            </a>

            <a href="#" class="lg:col-span-4 glass rounded-[32px] p-7 sm:p-8 card-hover min-h-[240px] flex flex-col justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">04</p>
                    <h3 class="mt-5 text-2xl font-bold">Limited Batch</h3>
                </div>
                <p class="text-neutral-600 dark:text-neutral-400 leading-relaxed">Dirilis terbatas untuk menjaga eksklusivitas.</p>
            </a>

            <a href="#" class="lg:col-span-8 glass rounded-[32px] p-7 sm:p-8 card-hover min-h-[240px] flex flex-col justify-between">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">05</p>
                        <h3 class="mt-5 text-2xl sm:text-3xl font-black">Search by style, not just by category.</h3>
                    </div>
                    <p class="text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-xl">
                        Tampilan dibuat seperti fashion magazine modern, supaya produk tampil lebih mahal dan lebih mudah dikonversi.
                    </p>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- NEW ARRIVALS --}}
<section id="new-arrivals" class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">New arrivals</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Fresh drop</h2>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">
            <div class="lg:col-span-7 glass rounded-[34px] p-6 sm:p-8 card-hover">
                <div class="grid md:grid-cols-2 gap-6 items-center">
                    <div class="overflow-hidden rounded-[26px]">
                        <img
                            src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1200&q=80"
                            class="w-full h-[440px] object-cover"
                            alt="New Arrival"
                        >
                    </div>

                    <div>
                        <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">New arrival</p>
                        <h3 class="mt-4 text-3xl font-black leading-tight">Soft essentials with a sharper, cleaner fit.</h3>
                        <p class="mt-4 text-neutral-600 dark:text-neutral-400 leading-relaxed">
                            Koleksi terbaru dirancang dengan visual yang tenang namun tetap tegas dan modern.
                        </p>

                        <div class="mt-8 flex items-center gap-4">
                            <span class="text-3xl font-black">Rp 279.000</span>
                            <a href="#" class="px-6 py-3 rounded-full bg-black text-white dark:bg-white dark:text-black font-medium">
                                Shop Now
                            </a>
                        </div>

                        <div class="mt-8 flex items-center gap-3 text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="w-2 h-2 rounded-full bg-black dark:bg-white"></span>
                            Limited quantities available
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 grid gap-6">
                <div class="glass rounded-[32px] p-7 sm:p-8 card-hover">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Style note</p>
                    <h3 class="mt-4 text-2xl font-bold leading-tight">The aesthetic is calm, but the brand is loud in quality.</h3>
                    <p class="mt-4 text-neutral-600 dark:text-neutral-400 leading-relaxed">
                        Desain dibuat timeless, mengikuti tren sekarang tanpa cepat terasa usang.
                    </p>
                </div>

                <div class="glass rounded-[32px] p-7 sm:p-8 card-hover">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Drop info</p>
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div class="rounded-[22px] bg-black text-white dark:bg-white dark:text-black p-4">
                            <p class="text-xs uppercase tracking-[0.25em]">style</p>
                            <p class="mt-3 font-semibold">Modern</p>
                        </div>
                        <div class="glass rounded-[22px] p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-neutral-600 dark:text-neutral-400">fit</p>
                            <p class="mt-3 font-semibold">Relaxed</p>
                        </div>
                        <div class="glass rounded-[22px] p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-neutral-600 dark:text-neutral-400">stock</p>
                            <p class="mt-3 font-semibold">Low</p>
                        </div>
                    </div>
                </div>

                <div class="glass rounded-[32px] overflow-hidden card-hover">
                    <img
                        src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1200&q=80"
                        class="w-full h-[290px] object-cover"
                        alt="Minimal fashion"
                    >
                </div>
            </div>
        </div>
    </div>
</section>

{{-- LOOKBOOK --}}
<section id="lookbook" class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Lookbook</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Editorial moments</h2>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-5">
            <div class="lg:col-span-4 glass rounded-[32px] overflow-hidden card-hover">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=80" class="w-full h-[520px] object-cover" alt="Lookbook 1">
                <div class="p-6">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Look 01</p>
                    <p class="mt-2 text-xl font-semibold">Weekend layers</p>
                </div>
            </div>

            <div class="lg:col-span-4 glass rounded-[32px] overflow-hidden card-hover">
                <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=1200&q=80" class="w-full h-[520px] object-cover" alt="Lookbook 2">
                <div class="p-6">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Look 02</p>
                    <p class="mt-2 text-xl font-semibold">Monochrome clean fit</p>
                </div>
            </div>

            <div class="lg:col-span-4 glass rounded-[32px] overflow-hidden card-hover">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1200&q=80" class="w-full h-[520px] object-cover" alt="Lookbook 3">
                <div class="p-6">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Look 03</p>
                    <p class="mt-2 text-xl font-semibold">Soft structure</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ACTIVE BATCH --}}
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="glass rounded-[36px] p-7 sm:p-10 lg:p-12 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-black/5 dark:bg-white/5 blur-3xl"></div>

            <div class="grid lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-7">
                    <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Active batch</p>
                    <h2 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">Batch #03 — Soft Essentials</h2>
                    <p class="mt-5 text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-2xl">
                        Batch dibuat terbatas agar terasa eksklusif. Di sinilah proses PO bekerja: batch dibuka, customer order, lalu produk diproses sesuai kuota.
                    </p>

                    <div class="mt-10 grid sm:grid-cols-3 gap-4 max-w-3xl">
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.28em] text-neutral-600 dark:text-neutral-400">Open</p>
                            <p class="mt-3 text-xl font-semibold">1 Juni</p>
                        </div>
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.28em] text-neutral-600 dark:text-neutral-400">Close</p>
                            <p class="mt-3 text-xl font-semibold">10 Juni</p>
                        </div>
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.28em] text-neutral-600 dark:text-neutral-400">Slots</p>
                            <p class="mt-3 text-xl font-semibold">120 / 200</p>
                        </div>
                    </div>

                    <div class="mt-8 max-w-2xl">
                        <div class="flex items-center justify-between text-sm text-neutral-600 dark:text-neutral-400 mb-3">
                            <span>Batch progress</span>
                            <span>60%</span>
                        </div>
                        <div class="h-3 rounded-full bg-neutral-200 dark:bg-neutral-800 overflow-hidden">
                            <div class="w-[60%] h-full rounded-full bg-black dark:bg-white"></div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="grid gap-4">
                        <div class="glass rounded-[28px] p-6">
                            <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Why it works</p>
                            <p class="mt-4 text-2xl font-bold leading-tight">Eksklusif, sederhana, dan fokus pada kualitas produk.</p>
                        </div>
                        <div class="glass rounded-[28px] overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1200&q=80" class="w-full h-[290px] object-cover" alt="Batch">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PRODUCTS --}}
<section id="products" class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Featured products</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Best sellers</h2>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6">
            @for($i = 1; $i <= 8; $i++)
            <article class="group">
                <div class="relative overflow-hidden rounded-[30px] bg-neutral-100 dark:bg-neutral-900">
                    <img
                        src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80"
                        class="w-full h-[420px] object-cover transition duration-500 group-hover:scale-105"
                        alt="Product {{ $i }}"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition duration-300"></div>

                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="glass rounded-full px-3 py-1 text-[11px] uppercase tracking-[0.25em]">new</span>
                        <span class="glass rounded-full px-3 py-1 text-[11px] uppercase tracking-[0.25em]">limited</span>
                    </div>

                    <button class="absolute bottom-4 right-4 glass rounded-full px-4 py-2 text-sm opacity-0 group-hover:opacity-100 transition duration-300">
                        Quick View
                    </button>
                </div>

                <div class="mt-5 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-semibold text-lg">Essential Oversized Tee</h3>
                        <p class="text-neutral-600 dark:text-neutral-400 text-sm mt-1">Premium Cotton • Black</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-lg">Rp 249.000</p>
                        <p class="text-xs uppercase tracking-[0.25em] text-neutral-600 dark:text-neutral-400 mt-2">4.9 / 5</p>
                    </div>
                </div>
            </article>
            @endfor
        </div>
    </div>
</section>

{{-- BEST SELLERS --}}
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Top picks</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Best sellers</h2>
            </div>
        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
            @for($i = 1; $i <= 4; $i++)
            <div class="glass rounded-[28px] p-5 card-hover">
                <div class="flex items-center gap-4">
                    <img
                        src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80"
                        class="w-24 h-24 rounded-[22px] object-cover"
                        alt="Best Seller"
                    >

                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] uppercase tracking-[0.28em] text-neutral-600 dark:text-neutral-400">top seller</p>
                        <h3 class="mt-2 font-semibold truncate">Heavyweight Hoodie</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Relaxed fit • washed black</p>
                        <p class="font-black mt-4">Rp 399.000</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section id="testimonials" class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Testimonials</p>
                <h2 class="mt-3 text-4xl sm:text-5xl font-black tracking-tight">Loved by customers</h2>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            @for($i = 1; $i <= 3; $i++)
            <div class="glass rounded-[30px] p-8 card-hover">
                <div class="flex items-center gap-1 text-sm">★★★★★</div>

                <p class="mt-5 text-neutral-600 dark:text-neutral-400 leading-relaxed">
                    “Desainnya berbeda dari e-commerce fashion biasa. Bersih, elegan, dan produknya benar-benar jadi pusat perhatian.”
                </p>

                <div class="mt-8 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-200 dark:bg-neutral-800"></div>
                    <div>
                        <p class="font-semibold">Customer {{ $i }}</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Verified buyer</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="py-20 lg:py-28">
    <div class="max-w-4xl mx-auto px-6">
        <div class="glass rounded-[36px] p-8 sm:p-12 text-center">
            <p class="text-[11px] uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-400">Newsletter</p>
            <h2 class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">
                Get early access to new drops.
            </h2>
            <p class="mt-4 text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto leading-relaxed">
                Dapatkan akses batch baru, produk terbatas, dan promo eksklusif sebelum dirilis ke publik.
            </p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
                <input
                    type="email"
                    placeholder="Enter your email"
                    class="flex-1 premium-input rounded-2xl px-5 py-4"
                >
                <button class="px-8 py-4 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-medium">
                    Subscribe
                </button>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function heroSlider() {
        return {
            active: 0,
            interval: null,
            slides: [
                {
                    tag: 'Look 01',
                    caption: 'New Drop',
                    kicker: 'Premium fashion label',
                    title: 'Quiet luxury for everyday confidence.',
                    desc: 'Potongan clean, visual tenang, dan material yang terasa mahal saat dipakai.',
                    cta: 'Shop Collection',
                    image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1600&q=80'
                },
                {
                    tag: 'Look 02',
                    caption: 'Limited Batch',
                    kicker: 'Editorial style',
                    title: 'Modern essentials, made to last.',
                    desc: 'Koleksi ini menjaga keseimbangan antara trend dan desain yang tidak cepat usang.',
                    cta: 'View Batch',
                    image: 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1600&q=80'
                },
                {
                    tag: 'Look 03',
                    caption: 'Best Seller',
                    kicker: 'Signature fit',
                    title: 'Minimal forms, premium feel.',
                    desc: 'Tampil bersih, rapi, dan relevan untuk dipakai harian maupun ke acara santai.',
                    cta: 'Explore Now',
                    image: 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1600&q=80'
                }
            ],

            init() {
                this.interval = setInterval(() => {
                    this.active = (this.active + 1) % this.slides.length;
                }, 5000);
            },

            go(index) {
                this.active = index;
                clearInterval(this.interval);
                this.init();
            }
        }
    }
</script>
@endpush