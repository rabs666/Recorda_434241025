@extends('layouts.recorda')

@section('title', 'Recorda - Login')
@section('body_class', 'page-auth page-login')

@section('content')
<section class="auth-wrap reveal">
    <div class="auth-media">
        <div class="auth-brand">
            <h2>Recorda</h2>
            <p>Music Record Store</p>
        </div>
        <div class="auth-photo"></div>
        <p class="auth-quote">"Temukan koleksi vinyl dan CD favoritmu"</p>
    </div>
    <div class="auth-form">
        <h1>Selamat datang kembali</h1>
        <p class="muted">Masuk ke akun Recorda-mu</p>
        <form class="form-stack" action="{{ route('recorda.login') }}" method="post">
            @csrf
            <p class="form-helper">User: user@recorda.com / recorda123 &middot; Admin: admin@recorda.com / recorda123</p>
            <label class="form-control">
                <span>Email</span>
                <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                @error('email')<span class="form-feedback" style="color:#c0392b;">{{ $message }}</span>@enderror
            </label>
            <label class="form-control">
                <span>Password</span>
                <div class="input-with-button">
                    <input class="input" id="login-password" name="password" type="password" placeholder="Masukkan password" required>
                    <button class="password-toggle" type="button" data-password-toggle data-target="login-password">Lihat</button>
                </div>
                @error('password')<span class="form-feedback" style="color:#c0392b;">{{ $message }}</span>@enderror
            </label>
            <div class="form-row">
                <label class="checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya selama 30 hari</span>
                </label>
                <a class="text-link" href="{{ route('recorda.forgot') }}">Lupa password?</a>
            </div>
            <button class="btn btn-primary" type="submit">Masuk</button>
            <p class="muted">Belum punya akun? <a class="text-link" href="{{ route('recorda.register') }}">Daftar sekarang</a></p>
        </form>
    </div>
</section>
@endsection
