@extends('layouts.admin')

@section('title', 'Recorda - Kelola Transaksi')
@section('page_title', 'Kelola Transaksi')

@section('content')
<div class="filter-bar">
    @php($statuses = ['all' => 'Semua', 'menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'])
    @foreach($statuses as $key => $label)
        <a class="filter-button {{ $filter === $key ? 'is-active' : '' }}" href="{{ route('recorda.manageTransactions', ['status' => $key]) }}">{{ $label }}</a>
    @endforeach
</div>

<div class="admin-tools">
    <input class="input" type="search" placeholder="Order ID / Nama..." data-admin-search>
</div>

<div class="panel">
    <div class="admin-table-scroll">
    <div class="table" data-admin-table>
        <div class="table-row table-head columns-7">
            <div>Order ID</div>
            <div>Pengguna</div>
            <div>Item</div>
            <div>Tanggal</div>
            <div>Total</div>
            <div>Status</div>
            <div>Ubah Status</div>
        </div>
        @forelse($orders as $order)
        <div class="table-row columns-7" data-table-row>
            <div class="mono" data-search-text>#{{ $order->code }}</div>
            <div>{{ $order->customer_name }}</div>
            <div>{{ $order->itemsSummary() }}</div>
            <div>{{ optional($order->created_at)->translatedFormat('d M Y') }}</div>
            <div class="mono">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            <div><span class="status-badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span></div>
            <div class="table-actions">
                <form method="POST" action="{{ route('recorda.transactions.updateStatus', $order) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <select class="input" name="status" onchange="this.form.submit()" style="padding:4px 8px;">
                        @foreach(['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $st)
                            <option value="{{ $st }}" @selected($order->status === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        @empty
        <div class="table-row"><div>Belum ada transaksi.</div></div>
        @endforelse
    </div>
    </div>
</div>
@endsection
