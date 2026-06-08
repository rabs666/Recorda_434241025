<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Recorda')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('recorda-logo.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700,800|dm-sans:400,500,600,700|space-mono:400,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('recorda.css') }}">
        <script defer src="{{ asset('recorda.js') }}"></script>
    @endif
</head>
<body class="recorda-body @yield('body_class')">
    <header class="site-header">
        <div class="site-header__inner">
            <a class="logo" href="{{ route('recorda.home') }}">
                <img class="logo-symbol" src="{{ asset('recorda-logo.svg') }}" alt="Recorda logo">
                <span class="logo-text">Recorda</span>
            </a>
            <nav class="site-nav">
                <a class="nav-link {{ request()->routeIs('recorda.home') ? 'active' : '' }}" href="{{ route('recorda.home') }}">Beranda</a>
                <a class="nav-link {{ request()->routeIs('recorda.archive', 'recorda.article') ? 'active' : '' }}" href="{{ route('recorda.archive') }}">Artikel</a>
                <a class="nav-link {{ request()->routeIs('recorda.catalog', 'recorda.product') ? 'active' : '' }}" href="{{ route('recorda.catalog') }}">Katalog</a>
                <a class="nav-link {{ request()->routeIs('recorda.cart', 'recorda.checkout', 'recorda.history') ? 'active' : '' }}" href="{{ route('recorda.cart') }}">Keranjang</a>
            </nav>
            <div class="site-actions">
                <a class="icon-button" href="{{ route('recorda.archive') }}" aria-label="Cari artikel">
                    <span class="search-icon"></span>
                </a>
                @auth
                <div class="user-menu-wrapper" data-user-menu-wrapper>
                    <button class="btn btn-ghost user-menu-trigger" type="button" data-user-menu-trigger>
                        <span class="user-icon" style="display: inline-block; width: 20px; height: 20px; margin-right: 8px; background: currentColor; border-radius: 50%;"></span>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <div class="user-menu-dropdown" data-user-menu-dropdown>
                        <div class="user-menu-header">
                            <p class="muted">{{ auth()->user()->email }}</p>
                            <p class="muted" style="margin: 4px 0 0;">Role: {{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        @if (auth()->user()->isAdmin())
                        <a class="user-menu-item" href="{{ route('recorda.dashboard') }}">
                            <span>🛠️ Panel Admin</span>
                        </a>
                        @endif
                        <a class="user-menu-item" href="{{ route('recorda.account') }}">
                            <span>👤 Profil Saya</span>
                        </a>
                        <a class="user-menu-item" href="{{ route('recorda.changePassword') }}">
                            <span>⚙️ Ubah Kata Sandi</span>
                        </a>
                        <a class="user-menu-item" href="{{ route('recorda.history') }}">
                            <span>📋 Riwayat Transaksi</span>
                        </a>
                        <hr style="margin: 8px 0; border: none; border-top: 1px solid #eee;">
                        <form method="POST" action="{{ route('recorda.logout') }}">
                            @csrf
                            <button class="user-menu-item" type="submit" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 12px 16px; font: inherit; color: inherit;">
                                <span>🚪 Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a class="btn btn-ghost" href="{{ route('recorda.login') }}">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="recorda-page">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div>
                <h3>Recorda</h3>
                <p>Music Record Store</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('recorda.catalog') }}">Katalog produk</a>
                <a href="{{ route('recorda.cart') }}">Keranjang</a>
                <a href="{{ route('recorda.history') }}">History transaksi</a>
                <a href="{{ route('recorda.archive') }}">Arsip artikel</a>
                <a href="{{ route('recorda.login') }}">Login</a>
                <a href="{{ route('recorda.register') }}">Daftar</a>
            </div>
            <div class="footer-meta">
                <p>Recorda UI concept 2026</p>
                <p class="mono">W 1440 / H 1024</p>
            </div>
        </div>
    </footer>
</body>
</html>
