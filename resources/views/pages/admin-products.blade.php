@extends('layouts.admin')

@section('title', 'Recorda - Kelola Produk')
@section('page_title', 'Kelola Produk')

@section('admin_top_actions')
    <a class="btn btn-primary btn-compact" href="{{ route('recorda.products.create') }}">+ Tambah Produk</a>
@endsection

@section('content')
<div class="admin-tools">
    <input class="input" type="search" placeholder="Cari nama produk..." data-admin-search>
</div>

<div class="admin-stats">
    <div class="stat-card">
        <p class="muted">Total Produk</p>
        <h3>{{ $stats['total'] }}</h3>
    </div>
    <div class="stat-card">
        <p class="muted">Stok Habis</p>
        <h3>{{ $stats['habis'] }}</h3>
    </div>
    <div class="stat-card">
        <p class="muted">Stok Rendah</p>
        <h3>{{ $stats['rendah'] }}</h3>
    </div>
</div>

<div class="panel">
    <div class="admin-table-scroll">
    <div class="table" data-admin-table>
        <div class="table-row table-head columns-8">
            <div>Img</div>
            <div>Nama Produk</div>
            <div>Kat.</div>
            <div>Genre</div>
            <div>Harga</div>
            <div>Stok</div>
            <div>Status</div>
            <div>Aksi</div>
        </div>
        @forelse($products as $product)
        <div class="table-row columns-8" data-table-row>
            <div class="thumb-sm {{ $product->cover }}" @if($product->imageUrl()) style="background-image:url('{{ $product->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
            <div data-search-text>{{ $product->name }}</div>
            <div>{{ $product->format }}</div>
            <div>{{ $product->genre }}</div>
            <div class="mono">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <div>{{ $product->stock }}</div>
            <div><span class="status-badge {{ $product->stockBadgeClass() }}">{{ $product->stockStatus() }}</span></div>
            <div class="table-actions">
                <a class="btn btn-light btn-compact" href="{{ route('recorda.products.edit', $product) }}">Edit</a>
                <form method="POST" action="{{ route('recorda.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk {{ $product->name }}?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-compact" type="submit">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="table-row"><div>Belum ada produk.</div></div>
        @endforelse
    </div>
    </div>
</div>
@endsection
