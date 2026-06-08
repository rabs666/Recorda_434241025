@extends('layouts.admin')

@section('title', 'Recorda - Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="admin-stats">
    <div class="stat-card">
        <p class="muted">Total Revenue</p>
        <h3>Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
        <p class="mono">Order selesai</p>
    </div>
    <div class="stat-card">
        <p class="muted">Total Pesanan</p>
        <h3>{{ $orderCount }}</h3>
        <p class="mono">Semua status</p>
    </div>
    <div class="stat-card">
        <p class="muted">Pengguna</p>
        <h3>{{ $userCount }}</h3>
        <p class="mono">Terdaftar</p>
    </div>
    <div class="stat-card">
        <p class="muted">Produk Aktif</p>
        <h3>{{ $activeProducts }}</h3>
        <p class="mono">Tampil di katalog</p>
    </div>
</div>

<div class="admin-grid">
    <div class="panel">
        <div class="panel-head">
            <div>
                <h2>Grafik Penjualan</h2>
                <p class="muted" data-dashboard-chart-label>7 hari terakhir</p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button class="btn btn-light btn-compact is-active" type="button" data-dashboard-range="7">7 Hari</button>
                <button class="btn btn-light btn-compact" type="button" data-dashboard-range="30">30 Hari</button>
                <button class="btn btn-light btn-compact" type="button" data-dashboard-range="90">90 Hari</button>
            </div>
        </div>
        <div class="chart-placeholder" data-dashboard-chart data-orders='@json($chartOrders)'>
            <span>Memuat grafik...</span>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head">
            <h2>Produk Terlaris</h2>
        </div>
        <div class="progress-list">
            @forelse($topProducts as $top)
            <div class="progress-item">
                <div>
                    <p>{{ $top->product_name }}</p>
                    <div class="progress-bar"><span style="width: {{ round($top->sold / $maxSold * 100) }}%;"></span></div>
                </div>
                <span class="mono">{{ $top->sold }} terjual</span>
            </div>
            @empty
            <p class="muted">Belum ada penjualan.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Transaksi Terbaru</h2>
    </div>
    <div class="admin-table-scroll">
    <div class="table">
        <div class="table-row table-head columns-6">
            <div>Order ID</div>
            <div>Pengguna</div>
            <div>Tanggal</div>
            <div>Total</div>
            <div>Status</div>
            <div>Aksi</div>
        </div>
        @forelse($recentOrders as $order)
        <div class="table-row columns-6">
            <div class="mono">#{{ $order->code }}</div>
            <div>{{ $order->customer_name }}</div>
            <div>{{ optional($order->created_at)->translatedFormat('d M Y') }}</div>
            <div class="mono">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
            <div><span class="status-badge {{ $order->statusBadgeClass() }}">{{ ucfirst($order->status) }}</span></div>
            <div><a class="btn btn-light btn-compact" href="{{ route('recorda.manageTransactions') }}">Kelola</a></div>
        </div>
        @empty
        <div class="table-row"><div>Belum ada transaksi.</div></div>
        @endforelse
    </div>
    </div>
</div>
@endsection
