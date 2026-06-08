@extends('layouts.recorda')

@section('title', 'Recorda - Daftar')
@section('body_class', 'page-auth page-register')

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
        <form class="form-stack" action="{{ route('recorda.register') }}" method="post">
            @csrf
            <label class="form-control">
                <span>Nama lengkap</span>
                <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Rafael Anandi" required>
                @error('name')<span class="form-feedback" style="color:#c0392b;">{{ $message }}</span>@enderror
            </label>
            <label class="form-control">
                <span>Email</span>
                <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                @error('email')<span class="form-feedback" style="color:#c0392b;">{{ $message }}</span>@enderror
            </label>
            <label class="form-control">
                <span>Password</span>
                <div class="input-with-button">
                    <input class="input" id="register-password" name="password" type="password" placeholder="Min 8 karakter" data-strength-input required>
                    <button class="password-toggle" type="button" data-password-toggle data-target="register-password">Lihat</button>
                </div>
                <div class="strength-meter" data-strength-meter="register-password">
                    <span class="strength-bar"></span>
                    <span class="strength-label">Kekuatan password</span>
                </div>
                @error('password')<span class="form-feedback" style="color:#c0392b;">{{ $message }}</span>@enderror
            </label>
            <label class="form-control">
                <span>Konfirmasi password</span>
                <input class="input" type="password" name="password_confirmation" placeholder="Ulangi password" required>
            </label>
            <label class="checkbox">
                <input type="checkbox" required>
                <span>Saya setuju dengan Syarat dan Ketentuan serta Kebijakan Privasi</span>
            </label>
            <button class="btn btn-primary" type="submit">Buat akun</button>
            <p class="muted">Sudah punya akun? <a class="text-link" href="{{ route('recorda.login') }}">Masuk di sini</a></p>
        </form>
    </div>
</section>
@endsection
