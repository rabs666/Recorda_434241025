@extends('layouts.recorda')

@section('title', 'Recorda - Detail Artikel')
@section('body_class', 'page-article')

@section('content')
<section class="article-hero reveal">
    <a class="back-link" href="{{ route('recorda.archive') }}">Kembali ke arsip</a>
    <p class="eyebrow">Review</p>
    <h1>Judul artikel panjang: Review album heavy serenade NMIXX</h1>
    <p class="meta">Admin - 12 Mei 2026 - Review</p>
    <div class="hero-image"></div>
</section>

<section class="article-content reveal">
    <p>Album ini dibuka dengan tekstur synth tebal yang langsung mengarah ke era 80an, tapi tetap punya punch modern dari drum yang presisi.</p>
    <p>Di track kedua, vokal layered memberi ruang pada bassline yang santai, cocok untuk didengar di malam hari saat lampu kota mulai meredup.</p>
    <div class="quote-block">
        <p>Music gives a soul to the universe.</p>
        <span class="mono">Recorda note</span>
    </div>
    <p>Secara keseluruhan, mixing terasa bersih dan detail, terutama di bagian chorus yang terasa lebih luas tanpa kehilangan fokus pada vokal utama.</p>
    <p>Jika kamu suka pop dengan sentuhan brass dan atmosfer sinematik, album ini wajib ada di rak koleksi.</p>
</section>

<section class="related reveal">
    <div class="section-head">
        <div>
            <h2>Artikel terkait</h2>
            <p>Rekomendasi bacaan lain dari Recorda.</p>
        </div>
    </div>
    <div class="related-grid">
        <article class="related-card">
            <div class="related-thumb thumb-b"></div>
            <div>
                <p class="meta">Tips</p>
                <h3>Checklist setup turntable pemula</h3>
                <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
            </div>
        </article>
        <article class="related-card">
            <div class="related-thumb thumb-c"></div>
            <div>
                <p class="meta">Review</p>
                <h3>Soundstage vinyl jazz dan cara menikmati detailnya</h3>
                <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
            </div>
        </article>
    </div>
</section>
@endsection
