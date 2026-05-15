@extends('layouts.recorda')

@section('title', 'Recorda - Beranda')
@section('body_class', 'page-home')

@section('content')
<section class="hero reveal">
    <div class="hero-copy">
        <p class="eyebrow">Recorda music record store</p>
        <h1>Temukan koleksi album fisik yang punya cerita.</h1>
        <p class="lead">Katalog vinyl, CD, dan kaset dengan kurasi yang rapi, dari rilis klasik sampai rilisan baru.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="#katalog">Jelajahi katalog</a>
            <a class="btn btn-ghost" href="#artikel">Baca artikel</a>
        </div>
        <div class="hero-meta">
            <div class="meta-card">
                <p class="mono">Kurasi mingguan</p>
                <h3>32 album</h3>
                <span>Vinyl, CD, kaset</span>
            </div>
            <div class="meta-card">
                <p class="mono">Pengiriman</p>
                <h3>2-3 hari</h3>
                <span>Surabaya Timur</span>
            </div>
        </div>
    </div>
    <div class="hero-media">
        <div class="hero-cover">
            <div class="cover-back"></div>
            <div class="cover-main">
                <div class="cover-label">
                    <span class="mono">REC-048</span>
                    <span>Abbey Road</span>
                </div>
            </div>
            <div class="vinyl"></div>
            <div class="hero-stamp">Limited Pressing</div>
        </div>
    </div>
</section>

<section id="katalog" class="section reveal">
    <div class="section-head">
        <div>
            <h2>Featured Albums</h2>
            <p>Koleksi pilihan yang siap diputar.</p>
        </div>
        <a class="text-link" href="#katalog">Lihat katalog</a>
    </div>
    <div class="album-grid">
        <article class="album-card">
            <div class="album-cover cover-a"></div>
            <div class="album-info">
                <div>
                    <h3>Abbey Road</h3>
                    <p class="muted">The Beatles - Vinyl 1969</p>
                </div>
                <span class="price mono">Rp 450.000</span>
            </div>
            <div class="album-tags">
                <span class="chip">Vinyl</span>
                <span class="chip">Rock</span>
            </div>
        </article>
        <article class="album-card">
            <div class="album-cover cover-b"></div>
            <div class="album-info">
                <div>
                    <h3>Rumours</h3>
                    <p class="muted">Fleetwood Mac - CD 1977</p>
                </div>
                <span class="price mono">Rp 250.000</span>
            </div>
            <div class="album-tags">
                <span class="chip">CD</span>
                <span class="chip">Pop</span>
            </div>
        </article>
        <article class="album-card">
            <div class="album-cover cover-c"></div>
            <div class="album-info">
                <div>
                    <h3>Blue Train</h3>
                    <p class="muted">John Coltrane - Vinyl 1958</p>
                </div>
                <span class="price mono">Rp 520.000</span>
            </div>
            <div class="album-tags">
                <span class="chip">Vinyl</span>
                <span class="chip">Jazz</span>
            </div>
        </article>
        <article class="album-card">
            <div class="album-cover cover-d"></div>
            <div class="album-info">
                <div>
                    <h3>After Hours</h3>
                    <p class="muted">The Weeknd - Kaset 2020</p>
                </div>
                <span class="price mono">Rp 185.000</span>
            </div>
            <div class="album-tags">
                <span class="chip">Kaset</span>
                <span class="chip">Rnb</span>
            </div>
        </article>
    </div>
</section>

<section id="artikel" class="section reveal">
    <div class="section-head">
        <div>
            <h2>Artikel Terbaru</h2>
            <p>Review, tips, dan cerita album.</p>
        </div>
        <a class="text-link" href="{{ route('recorda.archive') }}">Ke arsip artikel</a>
    </div>
    <div class="article-list">
        <article class="article-card">
            <div class="article-thumb thumb-a"></div>
            <div class="article-body">
                <p class="meta">Admin - 12 Mei 2026 - Review</p>
                <h3>Review album heavy serenade NMIXX</h3>
                <p class="muted">NMIXX melakukan debut terbarunya dengan warna synth dan brass yang tebal.</p>
                <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
            </div>
        </article>
        <article class="article-card">
            <div class="article-thumb thumb-b"></div>
            <div class="article-body">
                <p class="meta">Admin - 10 Mei 2026 - Tips</p>
                <h3>Checklist setup turntable pemula</h3>
                <p class="muted">Mulai dari cartridge, stylus, sampai cara menjaga kebersihan piringan.</p>
                <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
            </div>
        </article>
    </div>
</section>

<section id="keranjang" class="section cart-banner reveal">
    <div>
        <h2>Keranjang siap checkout</h2>
        <p>Susun wishlist, lalu lanjut ke pembayaran dengan sekali klik.</p>
    </div>
    <div class="cart-summary">
        <div class="summary-row">
            <span>2 item</span>
            <span class="mono">Rp 820.000</span>
        </div>
        <button class="btn btn-primary" type="button">Lanjut pembayaran</button>
    </div>
</section>

<section class="newsletter reveal">
    <div>
        <h2>Newsletter Recorda</h2>
        <p>Update mingguan soal rilis baru, restock, dan promo kolektor.</p>
    </div>
    <form class="newsletter-form" action="#" method="post">
        <input class="input" type="email" name="email" placeholder="email kamu">
        <button class="btn btn-primary" type="submit">Subscribe</button>
    </form>
    <div class="newsletter-note">
        <p class="mono">Update mingguan</p>
        <p class="muted">Tanpa spam, kapan saja bisa berhenti.</p>
    </div>
</section>
@endsection
