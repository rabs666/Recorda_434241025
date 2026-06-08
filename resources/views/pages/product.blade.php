@extends('layouts.recorda')

@section('title', 'Recorda - ' . ($album['name'] ?? 'Detail Produk'))
@section('body_class', 'page-product')

@section('content')
<section class="product-detail reveal" data-product-key="{{ request()->route('slug') }}">
    @php
        $photos = array_values(array_filter(array_merge(
            [$album['image_url'] ?? null],
            $album['gallery_urls'] ?? []
        )));
    @endphp
    <div class="product-gallery">
        @if(count($photos))
            <div class="product-main" data-product-main style="background-image:url('{{ $photos[0] }}'); background-size:cover; background-position:center;"></div>
            <div class="product-thumbs">
                @foreach($photos as $photo)
                <div class="product-thumb" data-product-photo="{{ $photo }}" style="background-image:url('{{ $photo }}'); background-size:cover; background-position:center;"></div>
                @endforeach
            </div>
        @else
            <div class="product-main {{ $album['covers'][0] }}" data-product-main></div>
            <div class="product-thumbs">
                @foreach($album['covers'] as $cover)
                <div class="product-thumb {{ $cover }}" data-product-thumb="{{ $cover }}"></div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="product-info">
        <p class="eyebrow">Detail Produk</p>
        <h1>{{ $album['name'] }}</h1>
        <p class="meta">{{ $album['format'] }} - {{ $album['genre'] }}</p>
        <p class="lead">{{ $album['description'] }}</p>
        <div class="product-price">
            <span class="price mono">Rp {{ number_format($album['price'], 0, ',', '.') }}</span>
            <span class="stock-badge">Stok {{ $album['stock'] }}</span>
        </div>
        <div class="product-actions">
            <button class="btn btn-primary" type="button" data-add-to-cart data-product-slug="{{ $album['slug'] }}" data-product-name="{{ $album['name'] }}" data-product-price="{{ $album['price'] }}" data-product-image="{{ $album['image_url'] ?? '' }}">Tambah ke keranjang</button>
            <a class="btn btn-ghost" href="{{ route('recorda.cart') }}">Lihat keranjang</a>
        </div>
        <div class="detail-grid">
            <div class="detail-card">
                <p class="muted">Format</p>
                <h3>{{ $album['format'] }}</h3>
            </div>
            <div class="detail-card">
                <p class="muted">Tahun</p>
                <h3>{{ $album['year'] }}</h3>
            </div>
            <div class="detail-card">
                <p class="muted">Label</p>
                <h3>{{ $album['label'] }}</h3>
            </div>
            <div class="detail-card">
                <p class="muted">Kondisi</p>
                <h3>{{ $album['condition'] }}</h3>
            </div>
        </div>
    </div>
</section>

<section class="section reveal">
    <div class="section-head">
        <div>
            <h2>Tracklist</h2>
            @php($tracks = $album['tracklist'] ?? [])
            <p>
                @if(count($tracks) > 0)
                    {{ count($tracks) }} lagu
                @endif
            </p>
        </div>
    </div>
    <ol class="tracklist">
        @forelse($tracks as $track)
        <li><span>{{ $track['title'] }}</span><span class="mono">{{ $track['duration'] ?? '' }}</span></li>
        @empty
        <li><span class="muted">Tracklist belum tersedia.</span></li>
        @endforelse
    </ol>
</section>

<section class="section reveal">
    <div class="section-head">
        <div>
            <h2>Produk terkait</h2>
            <p>Rekomendasi lain yang sejalan dengan selera kamu.</p>
        </div>
        <a class="text-link" href="{{ route('recorda.catalog') }}">Kembali ke katalog</a>
    </div>
    <div class="catalog-grid">
        @foreach($related as $item)
        <article class="product-card">
            <div class="product-cover {{ $item->cover }}" @if($item->imageUrl()) style="background-image:url('{{ $item->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
            <div class="product-body">
                <div>
                    <p class="meta">{{ $item->format }} - {{ $item->genre }}</p>
                    <h3>{{ $item->name }}</h3>
                    <p class="muted">{{ $item->artist }} - {{ $item->year }}</p>
                </div>
                <div class="product-foot">
                    <span class="price mono">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <a class="btn btn-light" href="{{ route('recorda.product', $item->slug) }}">Detail</a>
                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endsection
