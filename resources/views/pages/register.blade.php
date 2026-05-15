@extends('layouts.recorda')

@section('title', 'Recorda - Daftar')
@section('body_class', 'page-auth')

@section('content')
<section class="auth-wrap reveal">
    <div class="auth-media">
        <div class="auth-brand">
            <h2>Recorda</h2>
            <p>Music Record Store</p>
        </div>
        <div class="auth-photo"></div>
        <p class="auth-quote">"Gabung dan mulai koleksi rekaman impianmu"</p>
    </div>
    <div class="auth-form">
        <h1>Buat akun baru</h1>
        <p class="muted">Gratis, tanpa biaya pendaftaran</p>
        <form class="form-stack" action="#" method="post">
            <div class="form-grid">
                <label class="form-control">
                    <span>Nama depan</span>
                    <input class="input" type="text" placeholder="Rafael">
                </label>
                <label class="form-control">
                    <span>Nama belakang</span>
                    <input class="input" type="text" placeholder="Anandi">
                </label>
            </div>
            <label class="form-control">
                <span>Email</span>
                <input class="input" type="email" placeholder="contoh@email.com">
            </label>
            <label class="form-control">
                <span>Password</span>
                <div class="input-with-button">
                    <input class="input" id="register-password" type="password" placeholder="Min 8 karakter" data-strength-input>
                    <button class="password-toggle" type="button" data-password-toggle data-target="register-password">Lihat</button>
                </div>
                <div class="strength-meter" data-strength-meter="register-password">
                    <span class="strength-bar"></span>
                    <span class="strength-label">Kekuatan password</span>
                </div>
            </label>
            <label class="form-control">
                <span>Konfirmasi password</span>
                <input class="input" type="password" placeholder="Ulangi password">
            </label>
            <label class="checkbox">
                <input type="checkbox">
                <span>Saya setuju dengan Syarat dan Ketentuan serta Kebijakan Privasi</span>
            </label>
            <button class="btn btn-primary" type="submit">Buat akun</button>
            <p class="muted">Sudah punya akun? <a class="text-link" href="{{ route('recorda.login') }}">Masuk di sini</a></p>
        </form>
    </div>
</section>
@endsection
