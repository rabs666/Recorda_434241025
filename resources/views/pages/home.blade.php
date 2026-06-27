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
            <a class="btn btn-primary" href="{{ route('recorda.catalog') }}">Jelajahi katalog</a>
            <a class="btn btn-ghost" href="{{ route('recorda.archive') }}">Baca artikel</a>
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
            @php($coverPhoto = file_exists(public_path('images/covers/heavy-serenade.jpg')) ? asset('images/covers/heavy-serenade.jpg') : (file_exists(public_path('images/products/heavy-serenade.jpg')) ? asset('images/products/heavy-serenade.jpg') : null))
            <div class="cover-main {{ $coverPhoto ? 'has-photo' : '' }}" @if($coverPhoto) style="background-image:url('{{ $coverPhoto }}');" @endif>
                <div class="cover-label">
                    <span class="mono">REC-042</span>
                    <span>Heavy Serenade</span>
                </div>
            </div>
            <div class="vinyl">
                @php($labelPhoto = file_exists(public_path('images/products/olivia-rodrigo-label.jpg')) ? asset('images/products/olivia-rodrigo-label.jpg') : null)
                @if($labelPhoto)
                    <div class="vinyl-label" style="background-image:url('{{ $labelPhoto }}');"></div>
                @endif
            </div>
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
        <a class="text-link" href="{{ route('recorda.catalog') }}">Lihat katalog</a>
    </div>
    <div class="album-grid">
        @forelse($featured as $product)
        <article class="album-card">
            <div class="album-cover {{ $product->cover }}" @if($product->imageUrl()) style="background-image:url('{{ $product->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
            <div class="album-info">
                <div>
                    <h3>{{ $product->name }}</h3>
                    <p class="muted">{{ $product->artist }} - {{ $product->format }} {{ $product->year }}</p>
                </div>
                <span class="price mono">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            <div class="album-tags">
                <span class="chip">{{ $product->format }}</span>
                <span class="chip">{{ $product->genre }}</span>
            </div>
            <a class="btn btn-light" href="{{ route('recorda.product', $product->slug) }}" style="margin-top: 10px; width: 100%;">Detail</a>
        </article>
        @empty
        <p class="muted">Belum ada produk.</p>
        @endforelse
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
        @forelse($articles as $article)
        <article class="article-card">
            <div class="article-thumb {{ $article->cover }}" @if($article->imageUrl()) style="background-image:url('{{ $article->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
            <div class="article-body">
                <p class="meta">{{ $article->author }} - {{ $article->displayDate() }} - {{ $article->category }}</p>
                <h3>{{ $article->title }}</h3>
                <p class="muted">{{ $article->excerpt }}</p>
                <a class="text-link" href="{{ route('recorda.article', $article->slug) }}">Baca detail</a>
            </div>
        </article>
        @empty
        <p class="muted">Belum ada artikel.</p>
        @endforelse
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
        <a class="btn btn-primary" href="{{ route('recorda.checkout') }}">Lanjut pembayaran</a>
    </div>
</section>

<section class="newsletter reveal">
    <div>
        <h2>Newsletter Recorda</h2>
        <p>Update mingguan soal rilis baru, restock, dan promo kolektor.</p>
    </div>
    <form class="newsletter-form" action="#" method="post" data-newsletter-form>
        <input class="input" type="email" name="email" placeholder="email kamu" data-newsletter-email required>
        <button class="btn btn-primary" type="submit">Subscribe</button>
    </form>
    <div class="newsletter-note">
        <p class="mono">Update mingguan</p>
        <p class="muted">Tanpa spam, kapan saja bisa berhenti.</p>
    </div>
    <p class="form-feedback" data-newsletter-feedback aria-live="polite" style="margin-top: 12px;"></p>
</section>
@endsection
