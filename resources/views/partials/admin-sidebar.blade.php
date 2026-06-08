<aside class="admin-sidenav">
    <div class="admin-brand">
        <img class="logo-symbol" src="{{ asset('recorda-logo.svg') }}" alt="Recorda logo">
        <div class="admin-brand__text">
            <span class="logo-text">Recorda</span>
            <span class="admin-subtitle">Admin Panel</span>
        </div>
    </div>
    <nav class="admin-menu">
        <a class="admin-link {{ request()->routeIs('recorda.dashboard') ? 'active' : '' }}" href="{{ route('recorda.dashboard') }}">Dashboard</a>
        <a class="admin-link {{ request()->routeIs('recorda.manageArticles') ? 'active' : '' }}" href="{{ route('recorda.manageArticles') }}">Artikel</a>
        <a class="admin-link {{ request()->routeIs('recorda.manageProducts') ? 'active' : '' }}" href="{{ route('recorda.manageProducts') }}">Produk</a>
        <a class="admin-link {{ request()->routeIs('recorda.manageUsers') ? 'active' : '' }}" href="{{ route('recorda.manageUsers') }}">Pengguna</a>
        <a class="admin-link {{ request()->routeIs('recorda.manageTransactions') ? 'active' : '' }}" href="{{ route('recorda.manageTransactions') }}">Transaksi</a>
    </nav>
    <div class="admin-footer">
        <p class="muted">Terakhir update</p>
        <p class="mono">25 Mei 2026</p>
    </div>
</aside>
