@extends('layouts.recorda')

@section('title', 'Recorda - Login')
@section('body_class', 'page-auth')

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
        <div class="toggle-tabs">
            <button class="tab is-active" type="button">Email</button>
            <button class="tab" type="button">Username</button>
        </div>
        <form class="form-stack" action="#" method="post" data-dummy-login data-success-redirect="/home">
            <label class="form-control">
                <span>Email</span>
                <input class="input" type="email" placeholder="contoh@email.com" required>
            </label>
            <label class="form-control">
                <span>Password</span>
                <div class="input-with-button">
                    <input class="input" id="login-password" type="password" placeholder="Masukkan password" required>
                    <button class="password-toggle" type="button" data-password-toggle data-target="login-password">Lihat</button>
                </div>
            </label>
            <div class="form-row">
                <label class="checkbox">
                    <input type="checkbox">
                    <span>Ingat saya selama 30 hari</span>
                </label>
                <a class="text-link" href="{{ route('recorda.forgot') }}">Lupa password?</a>
            </div>
            <p class="form-feedback" data-login-feedback aria-live="polite"></p>
            <button class="btn btn-primary" type="submit">Masuk</button>
            <div class="divider"><span>atau masuk dengan</span></div>
            <button class="btn btn-light" type="button">Lanjut dengan Google</button>
            <p class="muted">Belum punya akun? <a class="text-link" href="{{ route('recorda.register') }}">Daftar sekarang</a></p>
        </form>
    </div>
</section>
@endsection
