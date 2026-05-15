<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Recorda')</title>
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
            <a class="logo" href="{{ route('recorda.home') }}">Recorda</a>
            <nav class="site-nav">
                <a class="nav-link {{ request()->routeIs('recorda.home') ? 'active' : '' }}" href="{{ route('recorda.home') }}">Beranda</a>
                <a class="nav-link {{ request()->routeIs('recorda.archive', 'recorda.article') ? 'active' : '' }}" href="{{ route('recorda.archive') }}">Artikel</a>
                <a class="nav-link" href="{{ route('recorda.home') }}#katalog">Katalog</a>
                <a class="nav-link" href="{{ route('recorda.home') }}#keranjang">Keranjang</a>
            </nav>
            <div class="site-actions">
                <a class="icon-button" href="{{ route('recorda.archive') }}" aria-label="Cari artikel">
                    <span class="search-icon"></span>
                </a>
                <a class="btn btn-ghost" href="{{ route('recorda.login') }}">Login</a>
            </div>
        </div>
    </header>

    <main class="recorda-page">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div>
                <h3>Recorda</h3>
                <p>Music Record Store</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('recorda.archive') }}">Arsip artikel</a>
                <a href="{{ route('recorda.article') }}">Detail artikel</a>
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
