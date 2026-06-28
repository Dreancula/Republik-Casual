@extends('layouts.app')

@section('content')

<div class="container">

    {{-- CATALOG HERO --}}
    <section class="rc-catalog-hero">
        <div class="rc-hero-content">
            <span class="rc-hero-tag">
                <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--rc-terracotta);"></span>
                Koleksi 2026
            </span>
            <h1 class="rc-hero-title">Katalog Produk</h1>
            <p class="rc-hero-sub" style="max-width:400px;">
                Setiap potongan dirancang untuk mereka yang menghargai kualitas dan estetika.
            </p>
        </div>
    </section>

    {{-- SEARCH --}}
    <div class="rc-glass rc-search-glass mt-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(43,30,23,0.3)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Cari produk favoritmu..." aria-label="Cari produk">
    </div>

    {{-- CATEGORY PILLS --}}
    <div class="mt-4">
        <span class="rc-pill active">Semua</span>
        <span class="rc-pill">Kaos</span>
        <span class="rc-pill">Hoodie</span>
        <span class="rc-pill">Jacket</span>
        <span class="rc-pill">Celana</span>
    </div>

    {{-- CONTENT --}}
    <div class="row mt-4">

        {{-- FILTER SIDEBAR --}}
        <div class="col-lg-3">
            <div class="rc-glass" style="padding:1.5rem;">
                <h5 style="font-weight:600; font-size:0.95rem; margin-bottom:1.5rem; color:var(--rc-espresso);">Filter</h5>

                <div class="rc-filter-section">
                    <h6>Kategori</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="filter-kaos" style="border-color:rgba(43,30,23,0.15); border-radius:4px;">
                        <label class="form-check-label" for="filter-kaos" style="font-size:0.9rem; color:var(--rc-espresso-soft);">Kaos</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="filter-hoodie" style="border-color:rgba(43,30,23,0.15); border-radius:4px;">
                        <label class="form-check-label" for="filter-hoodie" style="font-size:0.9rem; color:var(--rc-espresso-soft);">Hoodie</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="filter-jacket" style="border-color:rgba(43,30,23,0.15); border-radius:4px;">
                        <label class="form-check-label" for="filter-jacket" style="font-size:0.9rem; color:var(--rc-espresso-soft);">Jacket</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="filter-celana" style="border-color:rgba(43,30,23,0.15); border-radius:4px;">
                        <label class="form-check-label" for="filter-celana" style="font-size:0.9rem; color:var(--rc-espresso-soft);">Celana</label>
                    </div>
                </div>

                <hr style="opacity:0.1; margin:1.25rem 0;">

                <div class="rc-filter-section">
                    <h6>Ukuran</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="rc-pill" style="font-size:0.75rem; padding:0.35rem 0.9rem;">S</span>
                        <span class="rc-pill active" style="font-size:0.75rem; padding:0.35rem 0.9rem;">M</span>
                        <span class="rc-pill" style="font-size:0.75rem; padding:0.35rem 0.9rem;">L</span>
                        <span class="rc-pill" style="font-size:0.75rem; padding:0.35rem 0.9rem;">XL</span>
                    </div>
                </div>

                <hr style="opacity:0.1; margin:1.25rem 0;">

                <div class="rc-filter-section">
                    <h6>Harga</h6>
                    <div style="font-size:0.85rem; color:var(--rc-espresso-soft);">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="harga" id="harga1" checked style="border-color:rgba(43,30,23,0.15); border-radius:50%;">
                            <label class="form-check-label" for="harga1">Semua Harga</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="harga" id="harga2" style="border-color:rgba(43,30,23,0.15); border-radius:50%;">
                            <label class="form-check-label" for="harga2">&lt; Rp100K</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="harga" id="harga3" style="border-color:rgba(43,30,23,0.15); border-radius:50%;">
                            <label class="form-check-label" for="harga3">Rp100K &ndash; Rp250K</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="harga" id="harga4" style="border-color:rgba(43,30,23,0.15); border-radius:50%;">
                            <label class="form-check-label" for="harga4">&gt; Rp250K</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRODUCT GRID --}}
        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="rc-section-title" style="font-size:1.4rem;">Semua Produk</h2>
                <select class="form-select" style="width:auto; border-radius:12px; border:1px solid rgba(43,30,23,0.08); background:transparent; font-size:0.85rem; padding:0.4rem 2rem 0.4rem 1rem;">
                    <option>Terbaru</option>
                    <option>Harga Terendah</option>
                    <option>Harga Tertinggi</option>
                </select>
            </div>

            <div class="row g-4">
                @for($i = 1; $i <= 12; $i++)
                <div class="col-lg-4 col-md-6">
                    <div class="rc-product-card">
                        <div class="rc-product-image-wrap">
                            <img src="https://picsum.photos/seed/katalog{{$i}}/600/700" alt="Produk {{$i}}" loading="lazy">
                            <div class="rc-product-hover-actions">
                                <a href="/produk/{{$i}}" class="rc-btn rc-btn-primary" style="padding:0.6rem;flex:1;justify-content:center;font-size:0.8rem;">
                                    Detail
                                </a>
                                <button class="rc-btn rc-btn-outline" style="padding:0.6rem;flex:1;justify-content:center;font-size:0.8rem;">
                                    + Keranjang
                                </button>
                            </div>
                        </div>
                        <div class="rc-product-body">
                            <div style="margin-bottom:0.25rem;">
                                <span style="font-size:0.7rem; text-transform:uppercase; letter-spacing:0.08em; color:rgba(43,30,23,0.3); font-weight:500;">Kaos</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="rc-product-name">Oversized Essential Tee</div>
                                <div class="rc-product-price">Rp149K</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-5">
                <nav>
                    <ul class="pagination rc-pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="#">8</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

    </div>

</div>

@endsection
