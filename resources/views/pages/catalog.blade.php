@extends('layouts.recorda')

@section('title', 'Recorda - Katalog Produk')
@section('body_class', 'page-catalog')

@section('content')
<section class="catalog-head reveal">
    <div>
        <p class="eyebrow">Katalog Produk</p>
        <h1>Record fisik untuk kolektor.</h1>
        <p class="lead">Kurasi vinyl, CD, dan kaset dengan stok terjaga, siap dikirim kapan pun kamu butuh.</p>
    </div>
    <div class="catalog-tools">
        <div class="search-field">
            <span class="search-icon"></span>
            <input class="input" type="search" placeholder="Cari album atau artis..." data-search>
        </div>
        <a class="btn btn-ghost" href="{{ route('recorda.cart') }}">Lihat keranjang</a>
    </div>
</section>

<section class="filter-bar reveal">
    <button class="filter-button is-active" type="button" data-filter="all">Semua</button>
    <button class="filter-button" type="button" data-filter="vinyl">Vinyl</button>
    <button class="filter-button" type="button" data-filter="cd">CD</button>
    <button class="filter-button" type="button" data-filter="kaset">Kaset</button>
    <button class="filter-button" type="button" data-filter="limited">Limited</button>
</section>

<section class="catalog-grid">
    @forelse($products as $product)
    <article class="product-card reveal" data-tags="{{ $product->tags() }}">
        <div class="product-cover {{ $product->cover }}" @if($product->imageUrl()) style="background-image:url('{{ $product->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
        <div class="product-body">
            <div>
                <p class="meta">{{ $product->format }} - {{ $product->genre }}</p>
                <h3 data-search-text>{{ $product->name }}</h3>
                <p class="muted">{{ $product->artist }} - {{ $product->year }}</p>
            </div>
            <div class="product-foot">
                <span class="price mono">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <a class="btn btn-light" href="{{ route('recorda.product', $product->slug) }}">Detail</a>
            </div>
            @if($product->badge)
            <span class="product-badge">{{ $product->badge }}</span>
            @endif
        </div>
    </article>
    @empty
    <p class="muted">Belum ada produk yang tersedia.</p>
    @endforelse
</section>
@endsection
