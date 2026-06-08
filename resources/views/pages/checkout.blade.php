@extends('layouts.recorda')

@section('title', 'Recorda - Pembayaran')
@section('body_class', 'page-checkout')

@section('content')
<section class="section reveal">
    <div class="section-head">
        <div>
            <h1>Pembayaran</h1>
            <p class="lead">Lengkapi data pengiriman dan pilih metode pembayaran.</p>
        </div>
        <a class="text-link" href="{{ route('recorda.cart') }}">Kembali ke keranjang</a>
    </div>
    <div class="checkout-steps">
        <span class="step">1. Keranjang</span>
        <span class="step is-active">2. Pembayaran</span>
        <span class="step">3. Konfirmasi</span>
    </div>
</section>

<section class="checkout-layout reveal">
    <div class="checkout-form">
        <div class="panel">
            <div class="panel-head">
                <h2>Informasi Pengiriman</h2>
            </div>
            <div class="form-grid">
                <label class="form-control">
                    <span>Nama depan</span>
                    <input class="input" type="text" placeholder="Nama Depan" data-checkout-first-name required>
                </label>
                <label class="form-control">
                    <span>Nama belakang</span>
                    <input class="input" type="text" placeholder="Nama Belakang" data-checkout-last-name required>
                </label>
            </div>
            <label class="form-control">
                <span>Alamat lengkap</span>
                <input class="input" type="text" placeholder="Jl. Contoh No. 123" data-checkout-address required>
            </label>
            <div class="form-grid">
                <label class="form-control">
                    <span>Kota</span>
                    <input class="input" type="text" placeholder="Surabaya" data-checkout-city required>
                </label>
                <label class="form-control">
                    <span>Kode pos</span>
                    <input class="input" type="text" placeholder="60111" data-checkout-postal required>
                </label>
            </div>
            <label class="form-control">
                <span>No. HP</span>
                <input class="input" type="text" placeholder="08xxxxxxxxxx" data-checkout-phone required>
            </label>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h2>Metode Pembayaran</h2>
            </div>
            <div class="payment-options">
                <button class="payment-option is-active" type="button" data-payment-option data-payment-name="Transfer Bank">
                    <span class="payment-title">Transfer Bank</span>
                    <span class="muted">BCA · BNI · Mandiri</span>
                </button>
                <button class="payment-option" type="button" data-payment-option data-payment-name="E-Wallet">
                    <span class="payment-title">E-Wallet</span>
                    <span class="muted">GoPay · OVO · Dana</span>
                </button>
                <button class="payment-option" type="button" data-payment-option data-payment-name="COD">
                    <span class="payment-title">COD</span>
                    <span class="muted">Bayar di Tempat</span>
                </button>
            </div>
        </div>
    </div>

    <aside class="summary-panel">
        <h2>Ringkasan Pesanan</h2>
        <p class="muted" data-checkout-item-count>0 item</p>
        <div class="summary-items" data-checkout-items>
            <!-- Populated by JavaScript from cart localStorage -->
        </div>
        <div class="summary-list">
            <div class="summary-line">
                <span>Subtotal</span>
                <span class="mono" data-checkout-subtotal>Rp 0</span>
            </div>
            <div class="summary-line">
                <span>Ongkir</span>
                <span class="mono">Rp 30.000</span>
            </div>
            <div class="summary-line">
                <strong>Total</strong>
                <strong class="mono" data-checkout-total>Rp 30.000</strong>
            </div>
        </div>
        <button class="btn btn-primary" type="button" data-place-order>Konfirmasi Pesanan</button>
        <form method="POST" action="{{ route('recorda.checkout.store') }}" data-checkout-form style="display:none;">
            @csrf
            <input type="hidden" name="items" data-checkout-items-input>
            <input type="hidden" name="customer_name" data-checkout-name-input>
        </form>
    </aside>
</section>

<!-- Order Confirmation Modal -->
<div class="modal-overlay" data-order-modal style="display: none;">
    <div class="modal-content" style="background: white; border-radius: 12px; padding: 24px; max-width: 520px; text-align: left;">
        <div style="display:flex; gap:16px; align-items:center; margin-bottom: 10px;">
            <div style="font-size: 40px;">✓</div>
            <div>
                <h2 style="margin:0">Pesanan Diterima</h2>
                <p class="muted" style="margin:4px 0 0;">Nomor pesanan: <strong data-order-number></strong></p>
            </div>
        </div>
        <div style="margin-top:12px;">
            <p><strong>Status:</strong> <span data-order-status class="mono">Menunggu</span></p>
            <p><strong>Metode pembayaran:</strong> <span data-payment-method class="mono">-</span></p>
            <div style="margin-top:12px;" data-payment-instructions>
                <!-- Filled dynamically with instructions based on payment method -->
            </div>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
            <a class="btn btn-ghost" href="{{ route('recorda.home') }}">Kembali</a>
            <a class="btn btn-primary" href="{{ route('recorda.history') }}">Lihat Riwayat</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const SHIPPING_FEE = 30000;

    // Get cart from localStorage
    const getCart = () => {
        const cartJson = localStorage.getItem('recorda_cart');
        return cartJson ? JSON.parse(cartJson) : [];
    };

    const getItemQuantity = (item) => item.quantity || item.qty || 1;

    const getStoredOrders = () => {
        const ordersJson = localStorage.getItem('recorda_orders');
        if (ordersJson) {
            try {
                const parsed = JSON.parse(ordersJson);
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                return [];
            }
        }

        const legacyOrderJson = localStorage.getItem('recorda_order');
        if (legacyOrderJson) {
            try {
                const legacyOrder = JSON.parse(legacyOrderJson);
                return legacyOrder ? [legacyOrder] : [];
            } catch (error) {
                return [];
            }
        }

        return [];
    };

    // Render checkout items
    const renderCheckoutItems = () => {
        const cart = getCart();
        const itemsContainer = document.querySelector('[data-checkout-items]');
        const itemCountEl = document.querySelector('[data-checkout-item-count]');
        const subtotalEl = document.querySelector('[data-checkout-subtotal]');
        const totalEl = document.querySelector('[data-checkout-total]');

        if (!itemsContainer) return;

        // Clear container
        itemsContainer.innerHTML = '';

        if (cart.length === 0) {
            itemsContainer.innerHTML = '<p class="muted">Keranjang Anda kosong</p>';
            if (itemCountEl) itemCountEl.textContent = '0 item';
            if (subtotalEl) subtotalEl.textContent = 'Rp 0';
            if (totalEl) totalEl.textContent = 'Rp ' + (SHIPPING_FEE).toLocaleString('id-ID');
            return;
        }

        // Calculate totals
        let subtotal = 0;
        
        // Render each item
        cart.forEach(item => {
            const itemQuantity = getItemQuantity(item);
            const itemTotal = item.price * itemQuantity;
            subtotal += itemTotal;

            const itemEl = document.createElement('div');
            itemEl.className = 'summary-item';
            itemEl.innerHTML = `
                <div class="summary-thumb"${item.image ? ` style="background-image:url('${item.image}'); background-size:cover; background-position:center;"` : ''}></div>
                <div>
                    <p>${item.name}</p>
                    <p class="muted" style="font-size: 12px;">Qty: ${itemQuantity}</p>
                </div>
                <span class="mono">Rp ${itemTotal.toLocaleString('id-ID')}</span>
            `;
            itemsContainer.appendChild(itemEl);
        });

        // Update summary
        const total = subtotal + SHIPPING_FEE;

        if (itemCountEl) {
            itemCountEl.textContent = `${cart.length} item${cart.length !== 1 ? 's' : ''}`;
        }
        if (subtotalEl) {
            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
        if (totalEl) {
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    };

    // Handle place order
    const placeOrderBtn = document.querySelector('[data-place-order]');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', () => {
            const firstName = document.querySelector('[data-checkout-first-name]')?.value;
            const lastName = document.querySelector('[data-checkout-last-name]')?.value;
            const address = document.querySelector('[data-checkout-address]')?.value;
            const city = document.querySelector('[data-checkout-city]')?.value;
            const postal = document.querySelector('[data-checkout-postal]')?.value;
            const phone = document.querySelector('[data-checkout-phone]')?.value;

            // Validate
            if (!firstName || !lastName || !address || !city || !postal || !phone) {
                alert('Harap isi semua data pengiriman');
                return;
            }

            const cart = getCart();
            if (cart.length === 0) {
                alert('Keranjang Anda kosong');
                return;
            }

            // Build payload untuk dikirim ke server (membuat order di database).
            const items = cart.map((item) => ({
                slug: item.slug || '',
                name: item.name,
                price: item.price,
                qty: item.quantity || item.qty || 1
            }));

            const form = document.querySelector('[data-checkout-form]');
            const itemsInput = document.querySelector('[data-checkout-items-input]');
            const nameInput = document.querySelector('[data-checkout-name-input]');

            if (form && itemsInput) {
                itemsInput.value = JSON.stringify(items);
                if (nameInput) {
                    nameInput.value = `${firstName} ${lastName}`.trim();
                }

                // Kosongkan keranjang lokal lalu submit ke server.
                localStorage.removeItem('recorda_cart');
                form.submit();
            }
        });
    }

    // Render on page load
    renderCheckoutItems();

    // Payment option handler
    const paymentOptions = document.querySelectorAll('[data-payment-option]');
    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            paymentOptions.forEach(o => o.classList.remove('is-active'));
            option.classList.add('is-active');
        });
    });
});
</script>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
</style>
@endsection
