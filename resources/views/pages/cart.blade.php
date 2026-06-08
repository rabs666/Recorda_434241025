@extends('layouts.recorda')

@section('title', 'Recorda - Keranjang')
@section('body_class', 'page-cart')

@section('content')
<section class="section reveal">
    <div class="section-head">
        <div>
            <h1>Keranjang</h1>
            <p class="lead">Susun item yang akan kamu checkout hari ini.</p>
        </div>
        <a class="text-link" href="{{ route('recorda.catalog') }}">Lanjut belanja</a>
    </div>
</section>

<section class="cart-layout reveal">
    <div class="cart-list" data-cart-list>
        <!-- Populated by JavaScript from localStorage -->
    </div>

    <aside class="summary-card">
        <h2>Ringkasan</h2>
        <div class="summary-list">
            <div class="summary-line">
                <span>Subtotal</span>
                <span class="mono" data-subtotal>Rp 0</span>
            </div>
            <div class="summary-line">
                <span>Biaya kirim</span>
                <span class="mono">Rp 30.000</span>
            </div>
            <div class="summary-line">
                <strong>Total</strong>
                <strong class="mono" data-total>Rp 30.000</strong>
            </div>
        </div>
        <a class="btn btn-primary" href="{{ route('recorda.checkout') }}" data-checkout-button>Lanjut pembayaran</a>
        <a class="btn btn-ghost" href="{{ route('recorda.catalog') }}">Tambah item</a>
    </aside>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkoutBtn = document.querySelector('[data-checkout-button]');
    
    const checkCartAndDisable = () => {
        const cartJson = localStorage.getItem('recorda_cart');
        const cart = cartJson ? JSON.parse(cartJson) : [];
        
        if (checkoutBtn) {
            if (cart.length === 0) {
                checkoutBtn.style.pointerEvents = 'none';
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.title = 'Tambahkan item ke keranjang terlebih dahulu';
            } else {
                checkoutBtn.style.pointerEvents = 'auto';
                checkoutBtn.style.opacity = '1';
                checkoutBtn.title = '';
            }
        }
    };
    
    checkCartAndDisable();
});
</script>
@endsection
