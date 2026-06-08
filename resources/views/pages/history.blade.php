@extends('layouts.recorda')

@section('title', 'Recorda - History Transaksi')
@section('body_class', 'page-history')

@section('content')
<section class="history-head reveal">
    <div>
        <p class="eyebrow">History Transaksi</p>
        <h1>Semua pembelian kamu tersimpan rapi.</h1>
        <p class="lead">Lihat status pesanan dan detail item pesanan kamu.</p>
    </div>
    <a class="btn btn-ghost" href="{{ route('recorda.catalog') }}">Belanja lagi</a>
</section>

<div class="filter-bar history-filters reveal">
    <button class="filter-button is-active" type="button" data-filter="all">Semua</button>
    <button class="filter-button" type="button" data-filter="menunggu">Menunggu</button>
    <button class="filter-button" type="button" data-filter="diproses">Diproses</button>
    <button class="filter-button" type="button" data-filter="dikirim">Dikirim</button>
    <button class="filter-button" type="button" data-filter="selesai">Selesai</button>
    <button class="filter-button" type="button" data-filter="dibatalkan">Dibatalkan</button>
</div>

<section class="history-list">
    @forelse($orders as $order)
    <article class="panel reveal" data-tags="{{ $order->status }}" style="margin-bottom:16px;">
        <div class="panel-head">
            <div>
                <p class="mono">#{{ $order->code }}</p>
                <p class="muted">{{ $order->created_at?->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <span class="status-badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="table" style="margin-top:12px;">
            @foreach($order->items as $item)
            <div class="summary-row">
                <span>{{ $item->product_name }} &times; {{ $item->qty }}</span>
                <span class="mono">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        <div class="summary-row" style="margin-top:10px; border-top:1px solid #eee; padding-top:10px;">
            <strong>Total</strong>
            <strong class="mono">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
        </div>
    </article>
    @empty
    <div class="panel reveal">
        <p class="muted">Belum ada transaksi. <a class="text-link" href="{{ route('recorda.catalog') }}">Mulai belanja</a>.</p>
    </div>
    @endforelse
</section>
@endsection
