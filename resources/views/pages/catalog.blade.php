@extends('layouts.recorda')

@section('title', 'Recorda - Katalog Produk')
@section('body_class', 'page-catalog')

@section('content')
@php
    $selectedKategori = (array) request('kategori', []);
    $selectedGenre = (array) request('genre', []);
    $kategoriList = [
        'vinyl' => 'Vinyl',
        'cd' => 'CD',
        'kaset' => 'Kaset',
        'limited' => 'Limited Album',
    ];
@endphp

<section class="catalog-head reveal">
    <div>
        <p class="eyebrow">Katalog Produk</p>
        <h1>Record fisik untuk kolektor.</h1>
        <p class="lead">Kurasi vinyl, CD, dan kaset dengan stok terjaga, siap dikirim kapan pun kamu butuh.</p>
    </div>
</section>

<div class="catalog-layout reveal">
    {{-- Sidebar filter (server-side via GET) --}}
    <aside class="catalog-filter">
        <form method="GET" action="{{ route('recorda.catalog') }}" class="filter-form">
            <p class="filter-title">FILTER</p>

            <div class="filter-group">
                <p class="filter-label">Kategori</p>
                @foreach($kategoriList as $value => $label)
                <label class="filter-check">
                    <input type="checkbox" name="kategori[]" value="{{ $value }}"
                        @checked(in_array($value, $selectedKategori))>
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>

            <div class="filter-group">
                <p class="filter-label">Genre</p>
                @foreach($genreOptions as $genre)
                <label class="filter-check">
                    <input type="checkbox" name="genre[]" value="{{ $genre }}"
                        @checked(in_array($genre, $selectedGenre))>
                    <span>{{ $genre }}</span>
                </label>
                @endforeach
            </div>

            <div class="filter-group">
                <p class="filter-label">Harga</p>
                <input class="input filter-price" type="number" name="min" min="0" step="1000"
                    placeholder="Min" value="{{ request('min') }}">
                <input class="input filter-price" type="number" name="max" min="0" step="1000"
                    placeholder="Max" value="{{ request('max') }}">
            </div>

            {{-- Pertahankan sort & query saat menerapkan filter --}}
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="q" value="{{ request('q') }}">

            <button class="btn btn-dark filter-apply" type="submit">Terapkan</button>
            <a class="filter-reset" href="{{ route('recorda.catalog') }}">Reset filter</a>
        </form>
    </aside>

    {{-- Konten katalog --}}
    <div class="catalog-content">
        <form method="GET" action="{{ route('recorda.catalog') }}" class="catalog-toolbar">
            {{-- Pertahankan filter aktif saat mencari / mengganti urutan --}}
            @foreach($selectedKategori as $k)
                <input type="hidden" name="kategori[]" value="{{ $k }}">
            @endforeach
            @foreach($selectedGenre as $g)
                <input type="hidden" name="genre[]" value="{{ $g }}">
            @endforeach
            <input type="hidden" name="min" value="{{ request('min') }}">
            <input type="hidden" name="max" value="{{ request('max') }}">

            <div class="search-field">
                <span class="search-icon"></span>
                <input class="input" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Cari produk...">
            </div>

            <select class="input catalog-sort" name="sort" onchange="this.form.submit()">
                <option value="terbaru" @selected(request('sort','terbaru')==='terbaru')>Terbaru</option>
                <option value="termurah" @selected(request('sort')==='termurah')>Harga termurah</option>
                <option value="termahal" @selected(request('sort')==='termahal')>Harga termahal</option>
                <option value="nama" @selected(request('sort')==='nama')>Nama A-Z</option>
            </select>
        </form>

        <section class="catalog-grid">
            @forelse($products as $product)
            <article class="product-card">
                <a class="product-cover {{ $product->cover }}" href="{{ route('recorda.product', $product->slug) }}"
                    @if($product->imageUrl()) style="background-image:url('{{ $product->imageUrl() }}'); background-size:cover; background-position:center;" @endif></a>
                <div class="product-body">
                    <div>
                        <p class="meta">{{ $product->format }} - {{ $product->genre }}</p>
                        <h3>{{ $product->name }}</h3>
                        <p class="muted">{{ $product->artist }} - {{ $product->year }}</p>
                    </div>
                    <div class="product-foot">
                        <span class="price mono">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($product->badge)
                        <span class="product-badge">{{ $product->badge }}</span>
                        @endif
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-dark btn-cart" type="button"
                            data-add-to-cart
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}"
                            data-product-slug="{{ $product->slug }}"
                            data-product-image="{{ $product->imageUrl() }}">
                            + Keranjang
                        </button>
                        <a class="btn btn-light btn-detail" href="{{ route('recorda.product', $product->slug) }}">Detail</a>
                    </div>
                </div>
            </article>
            @empty
            <p class="muted catalog-empty">Tidak ada produk yang cocok dengan filter. <a href="{{ route('recorda.catalog') }}">Reset filter</a>.</p>
            @endforelse
        </section>
    </div>
</div>
@endsection
