@extends('layouts.recorda')

@section('title', 'Recorda - Arsip Artikel')
@section('body_class', 'page-archive')

@section('content')
<section class="archive-head reveal">
    <div>
        <p class="eyebrow">Arsip artikel</p>
        <h1>Review, tips, dan cerita rilis baru.</h1>
        <p class="lead">Cari artikel berdasarkan topik, lalu simpan sebagai referensi.</p>
    </div>
    <div class="search-field">
        <span class="search-icon"></span>
        <input class="input" type="search" placeholder="Cari artikel..." data-search>
    </div>
</section>

<section class="filter-bar reveal">
    <button class="filter-button is-active" type="button" data-filter="all">Semua</button>
    <button class="filter-button" type="button" data-filter="review">Review</button>
    <button class="filter-button" type="button" data-filter="tips">Tips</button>
    <button class="filter-button" type="button" data-filter="rilis">Rilis</button>
</section>

<section class="archive-list">
    <article class="article-row reveal" data-tags="review">
        <div class="row-thumb thumb-a"></div>
        <div class="row-body">
            <p class="meta">Admin - 12 Mei 2026 - Review</p>
            <h3>Review album heavy serenade NMIXX</h3>
            <p class="muted">NMIXX melakukan debut terbarunya dengan warna synth dan brass yang tebal.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
    <article class="article-row reveal" data-tags="tips">
        <div class="row-thumb thumb-b"></div>
        <div class="row-body">
            <p class="meta">Admin - 10 Mei 2026 - Tips</p>
            <h3>Checklist setup turntable pemula</h3>
            <p class="muted">Panduan ringkas mulai dari cartridge sampai setting anti skate.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
    <article class="article-row reveal" data-tags="rilis">
        <div class="row-thumb thumb-c"></div>
        <div class="row-body">
            <p class="meta">Admin - 08 Mei 2026 - Rilis</p>
            <h3>Rilis ulang kaset indie lokal dari tahun 2000an</h3>
            <p class="muted">Recorda menghadirkan ulang kaset langka dengan jumlah press terbatas.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
    <article class="article-row reveal" data-tags="review tips">
        <div class="row-thumb thumb-d"></div>
        <div class="row-body">
            <p class="meta">Admin - 06 Mei 2026 - Review</p>
            <h3>Soundstage vinyl jazz dan cara menikmati detailnya</h3>
            <p class="muted">Tips memilih pressing dan cara merawat piringan agar tetap bersih.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
    <article class="article-row reveal" data-tags="tips">
        <div class="row-thumb thumb-e"></div>
        <div class="row-body">
            <p class="meta">Admin - 02 Mei 2026 - Tips</p>
            <h3>Membersihkan stylus dengan aman di rumah</h3>
            <p class="muted">Langkah sederhana agar stylus tetap tajam dan musik tetap jernih.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
    <article class="article-row reveal" data-tags="review">
        <div class="row-thumb thumb-f"></div>
        <div class="row-body">
            <p class="meta">Admin - 29 April 2026 - Review</p>
            <h3>Review pressing ulang album klasik folk 1972</h3>
            <p class="muted">Dibandingkan versi lama, pressing baru punya detail vokal lebih jelas.</p>
            <a class="text-link" href="{{ route('recorda.article') }}">Baca detail</a>
        </div>
    </article>
</section>

<div class="pagination reveal">
    <button class="page-button" type="button">Prev</button>
    <button class="page-button is-active" type="button">1</button>
    <button class="page-button" type="button">2</button>
    <button class="page-button" type="button">3</button>
    <button class="page-button" type="button">Next</button>
</div>
@endsection
