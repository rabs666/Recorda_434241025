@extends('layouts.recorda')

@section('title', 'Recorda - Lupa Password')
@section('body_class', 'page-auth')

@section('content')
<section class="auth-wrap reveal">
    <div class="auth-media">
        <div class="auth-brand">
            <h2>Recorda</h2>
            <p>Music Record Store</p>
        </div>
        <div class="auth-photo"></div>
        <p class="auth-quote">"Tenang, kami bantu kamu masuk kembali"</p>
    </div>
    <div class="auth-form">
        <h1>Lupa password?</h1>
        <p class="muted">Masukkan email terdaftar untuk menerima link reset password.</p>
        <form class="form-stack" action="#" method="post">
            <label class="form-control">
                <span>Email terdaftar</span>
                <input class="input" type="email" placeholder="contoh@email.com">
            </label>
            <button class="btn btn-primary" type="submit">Kirim link reset password</button>
            <div class="notice">
                <p class="mono">Cek inbox email kamu</p>
                <p class="muted">Link reset berlaku selama 15 menit. Cek folder spam jika tidak muncul di inbox.</p>
            </div>
            <p class="muted">Ingat password? <a class="text-link" href="{{ route('recorda.login') }}">Kembali ke login</a></p>
        </form>
    </div>
</section>
@endsection
