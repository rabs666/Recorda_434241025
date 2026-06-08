<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Recorda Admin')</title>
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
<body class="recorda-body admin-body @yield('body_class')">
    <div class="admin-shell">
        @include('partials.admin-sidebar')
        <div class="admin-content">
            <div class="admin-topbar">
                <div class="admin-topbar__title">@yield('page_title', 'Admin')</div>
                <div class="admin-topbar__actions">
                    @hasSection('admin_top_actions')
                        @yield('admin_top_actions')
                    @endif
                    <a class="btn btn-light btn-compact" href="{{ route('recorda.home') }}">Lihat Situs</a>
                    <form method="POST" action="{{ route('recorda.logout') }}" style="display:inline;">
                        @csrf
                        <button class="admin-account" type="submit">{{ auth()->user()->name ?? 'Admin' }} · Keluar</button>
                    </form>
                </div>
            </div>
            <main class="admin-page">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <div class="modal-overlay" data-admin-modal style="display: none;">
        <div class="modal-content" style="background: white; border-radius: 12px; padding: 24px; width: min(720px, calc(100% - 32px)); text-align: left;">
            <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; margin-bottom: 16px;">
                <div>
                    <p class="eyebrow" style="margin-bottom: 4px;" data-admin-modal-eyebrow>Admin Panel</p>
                    <h2 style="margin:0;" data-admin-modal-title>Modal</h2>
                </div>
                <button class="btn btn-ghost btn-compact" type="button" data-admin-modal-close>Tutup</button>
            </div>
            <div data-admin-modal-body></div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top: 18px; flex-wrap:wrap;">
                <button class="btn btn-ghost" type="button" data-admin-modal-close-2>Batal</button>
                <button class="btn btn-primary" type="button" data-admin-modal-submit>Simpan</button>
            </div>
        </div>
    </div>
</body>
</html>
