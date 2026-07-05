@extends('frontend.layouts.app')

@section('title', 'Tentang Kami - Republik Casual')

@section('content')

<style>
    .rc-about-hero {
        padding: 100px 24px 60px;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }
    .rc-about-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.1;
        margin-bottom: 20px;
    }
    .rc-about-hero h1 span {
        color: var(--rc-accent);
    }
    .rc-about-hero p {
        color: var(--rc-text-secondary);
        font-size: 1.05rem;
        line-height: 1.7;
    }
    .rc-section {
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 24px;
    }
    .rc-section-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 32px;
        letter-spacing: -0.5px;
    }
    .rc-about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }
    .rc-about-card {
        background: var(--rc-card-dark);
        border: 1px solid var(--rc-card-border);
        border-radius: var(--radius-md);
        padding: 32px;
        backdrop-filter: blur(12px);
        transition: all 0.3s var(--transition-smooth);
    }
    .rc-about-card:hover {
        border-color: rgba(255,255,255,0.15);
        transform: translateY(-4px);
    }
    .rc-about-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(234, 230, 223, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--rc-accent);
        margin-bottom: 18px;
    }
    .rc-about-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .rc-about-card p {
        color: var(--rc-text-secondary);
        font-size: 0.88rem;
        line-height: 1.6;
    }
    .rc-timeline {
        position: relative;
        padding-left: 32px;
    }
    .rc-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(234, 230, 223, 0.15);
    }
    .rc-timeline-item {
        position: relative;
        padding-bottom: 36px;
    }
    .rc-timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--rc-accent);
        border: 3px solid var(--rc-bg-dark);
    }
    .rc-timeline-item .year {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--rc-accent);
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .rc-timeline-item h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .rc-timeline-item p {
        color: var(--rc-text-secondary);
        font-size: 0.85rem;
        line-height: 1.5;
    }
    .rc-values-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    .rc-value-item {
        background: rgba(234, 230, 223, 0.04);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .rc-value-item .emoji {
        font-size: 2rem;
        margin-bottom: 12px;
        display: block;
    }
    .rc-value-item h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .rc-value-item p {
        color: var(--rc-text-secondary);
        font-size: 0.8rem;
        line-height: 1.5;
    }
    @media (max-width: 768px) {
        .rc-about-hero h1 { font-size: 2rem; }
        .rc-about-hero { padding: 60px 20px 40px; }
        .rc-section { padding: 40px 20px; }
    }
</style>

<div class="rc-about-hero">
    <span class="inline-flex px-4 py-2 rounded-pill mb-4" style="background: rgba(234,230,223,0.08); color: var(--rc-accent); font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase;">
        Tentang Kami
    </span>
    <h1>Lebih dari Sekadar <span>Fashion</span></h1>
    <p>
        Republik Casual adalah brand streetwear premium asal Depok yang lahir dari semangat anak muda 
        Indonesia untuk menciptakan fashion yang tidak hanya keren, tapi juga nyaman dan berkualitas tinggi. 
        Setiap koleksi kami dirancang dengan detail, ketelitian, dan jiwa yang sama — untuk kamu yang 
        percaya diri menjadi diri sendiri.
    </p>
</div>

<div class="rc-section">
    <h2 class="rc-section-title">Visi &amp; Misi</h2>
    <div class="rc-about-grid">
        <div class="rc-about-card">
            <div class="icon"><i class="fa-solid fa-eye"></i></div>
            <h3>Visi</h3>
            <p>Menjadi brand streetwear Indonesia yang mendunia, dikenal karena kualitas premium, desain ikonik, dan semangat kebebasan berekspresi melalui fashion.</p>
        </div>
        <div class="rc-about-card">
            <div class="icon"><i class="fa-solid fa-bullseye"></i></div>
            <h3>Misi</h3>
            <p>Menghadirkan produk streetwear berkualitas tinggi dengan harga terjangkau, mendukung kreativitas lokal, dan memberikan pengalaman berbelanja terbaik bagi setiap pelanggan.</p>
        </div>
        <div class="rc-about-card">
            <div class="icon"><i class="fa-solid fa-handshake"></i></div>
            <h3>Komitmen</h3>
            <p>Kami berkomitmen pada kualitas, kenyamanan, dan kepuasan pelanggan. Setiap produk melewati kontrol kualitas ketat sebelum sampai ke tangan kamu.</p>
        </div>
    </div>
</div>

<div class="rc-section">
    <h2 class="rc-section-title">Perjalanan Kami</h2>
    <div class="rc-about-card" style="max-width: 600px;">
        <div class="rc-timeline">
            <div class="rc-timeline-item">
                <div class="year">2022</div>
                <h4>Berdiri</h4>
                <p>Republik Casual lahir dari sebuah vision untuk membawa streetwear lokal ke level yang lebih tinggi. Berawal dari koleksi kecil, kami mulai dikenal karena kualitas dan desain yang berbeda.</p>
            </div>
            <div class="rc-timeline-item">
                <div class="year">2023</div>
                <h4>Ekspansi Produk</h4>
                <p>Memperluas lini produk ke berbagai item fashion seperti cargo pants, kemeja, outer, dan aksesoris. Mulai dikenal di kalangan anak muda Depok dan sekitarnya.</p>
            </div>
            <div class="rc-timeline-item">
                <div class="year">2024</div>
                <h4>Digital &amp; Online</h4>
                <p>Meluncurkan platform e-commerce website resmi untuk menjangkau lebih banyak pelanggan di seluruh Indonesia. Sistem pemesanan dan pembayaran modern dengan Midtrans.</p>
            </div>
            <div class="rc-timeline-item">
                <div class="year">2025</div>
                <h4>Inovasi &amp; AI</h4>
                <p>Menghadirkan fitur AI Stylist untuk membantu pelanggan menemukan gaya terbaik mereka. Terus berinovasi dalam pelayanan dan pengalaman berbelanja.</p>
            </div>
        </div>
    </div>
</div>

<div class="rc-section">
    <h2 class="rc-section-title">Nilai Kami</h2>
    <div class="rc-values-list">
        <div class="rc-value-item">
            <span class="emoji">🎯</span>
            <h4>Kualitas</h4>
            <p>Setiap produk melalui quality control ketat untuk memastikan standar premium.</p>
        </div>
        <div class="rc-value-item">
            <span class="emoji">💡</span>
            <h4>Inovasi</h4>
            <p>Selalu mengikuti tren dan menghadirkan teknologi terbaru dalam fashion.</p>
        </div>
        <div class="rc-value-item">
            <span class="emoji">🤝</span>
            <h4>Kepercayaan</h4>
            <p>Kepuasan dan kepercayaan pelanggan adalah prioritas utama kami.</p>
        </div>
        <div class="rc-value-item">
            <span class="emoji">🇮🇩</span>
            <h4>Lokal Pride</h4>
            <p>Bangga menjadi brand lokal Indonesia yang siap bersaing di kancah global.</p>
        </div>
    </div>
</div>

<div class="rc-section" style="padding-bottom: 80px;">
    <div class="rc-about-card" style="text-align: center; max-width: 600px; margin: 0 auto;">
        <div class="icon" style="margin: 0 auto 16px;"><i class="fa-solid fa-store"></i></div>
        <h3>Temukan Kami</h3>
        <p style="max-width: 400px; margin: 12px auto 0;">
            <strong>Republik Casual</strong><br>
            Jl. Margonda No.8, Pondok Cina<br>
            Kecamatan Beji, Kota Depok<br>
            Jawa Barat 16424
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px;">
            <a href="https://wa.me/6285694520082" target="_blank" class="action-btn" style="width: 42px; height: 42px; border-radius: 12px; font-size: 16px;" title="WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="https://instagram.com/dreancula" target="_blank" class="action-btn" style="width: 42px; height: 42px; border-radius: 12px; font-size: 16px;" title="Instagram">
                <i class="fa-brands fa-instagram"></i>
            </a>
        </div>
    </div>
</div>

@endsection
