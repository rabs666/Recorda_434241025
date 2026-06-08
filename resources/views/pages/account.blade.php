@extends('layouts.recorda')

@section('title', 'Recorda - Akun Saya')
@section('body_class', 'page-account')

@section('content')
<section class="account-header reveal">
    <div>
        <p class="eyebrow">Akun Saya</p>
        <h1>Kelola profil dan preferensi kamu.</h1>
        <p class="lead">Update informasi pribadi, alamat, dan notifikasi.</p>
    </div>
    <a class="btn btn-ghost" href="{{ route('recorda.home') }}">Kembali ke beranda</a>
</section>

<section class="account-container reveal">
    <div class="account-sidebar">
        <nav class="account-nav">
            <a class="account-nav-item is-active" href="#profil">👤 Profil</a>
            <a class="account-nav-item" href="#alamat">📍 Alamat</a>
            <a class="account-nav-item" href="#notifikasi">🔔 Notifikasi</a>
            <a class="account-nav-item" href="{{ route('recorda.changePassword') }}">🔐 Kata Sandi</a>
        </nav>
    </div>

    <div class="account-content">
        <!-- Profil Section -->
        <div class="account-section is-active" id="profil">
            <div class="section-head">
                <h2>Informasi Profil</h2>
            </div>
            <form class="form-stack">
                <div class="form-row">
                    <label class="form-control">
                        <span>Nama Lengkap</span>
                        <input class="input" type="text" value="Pengguna Recorda" required>
                    </label>
                    <label class="form-control">
                        <span>Nama Pengguna</span>
                        <input class="input" type="text" value="pengguna.recorda" required>
                    </label>
                </div>
                <label class="form-control">
                    <span>Email</span>
                    <input class="input" type="email" value="user@recorda.com" required>
                </label>
                <label class="form-control">
                    <span>Nomor Telepon</span>
                    <input class="input" type="tel" placeholder="+62 812 3456 7890">
                </label>
                <label class="form-control">
                    <span>Tanggal Lahir</span>
                    <input class="input" type="date">
                </label>
                <label class="form-control">
                    <span>Jenis Kelamin</span>
                    <select class="input">
                        <option>Pilih jenis kelamin</option>
                        <option selected>Laki-laki</option>
                        <option>Perempuan</option>
                        <option>Lainnya</option>
                    </select>
                </label>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    <button class="btn btn-ghost" type="reset">Batalkan</button>
                </div>
            </form>
        </div>

        <!-- Alamat Section -->
        <div class="account-section" id="alamat">
            <div class="section-head">
                <div>
                    <h2>Alamat Pengiriman</h2>
                    <p class="muted">Kelola daftar alamat untuk pengiriman pesanan.</p>
                </div>
                <button class="btn btn-primary btn-small" type="button">+ Tambah Alamat</button>
            </div>
            <div class="address-list">
                <div class="address-card">
                    <div class="address-header">
                        <h3>Alamat Utama</h3>
                        <label class="checkbox">
                            <input type="radio" name="default-address" checked>
                            <span>Jadikan utama</span>
                        </label>
                    </div>
                    <p>Jl. Merdeka No. 123, Jakarta Selatan 12345</p>
                    <p class="muted">Telepon: +62 812 3456 7890</p>
                    <div class="address-actions">
                        <button class="text-link" type="button">Edit</button>
                        <button class="text-link" type="button" style="color: var(--terracotta-500);">Hapus</button>
                    </div>
                </div>
                <div class="address-card">
                    <div class="address-header">
                        <h3>Kantor</h3>
                        <label class="checkbox">
                            <input type="radio" name="default-address">
                            <span>Jadikan utama</span>
                        </label>
                    </div>
                    <p>Jl. Sudirman No. 456, Jakarta Pusat 12345</p>
                    <p class="muted">Telepon: +62 812 9876 5432</p>
                    <div class="address-actions">
                        <button class="text-link" type="button">Edit</button>
                        <button class="text-link" type="button" style="color: var(--terracotta-500);">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi Section -->
        <div class="account-section" id="notifikasi">
            <div class="section-head">
                <h2>Preferensi Notifikasi</h2>
            </div>
            <div class="notification-settings">
                <div class="notification-item">
                    <div>
                        <h3>Email Notifikasi Pesanan</h3>
                        <p class="muted">Terima update tentang status pesanan kamu</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span></span>
                    </label>
                </div>
                <div class="notification-item">
                    <div>
                        <h3>Email Promosi & Penawaran</h3>
                        <p class="muted">Dapatkan notifikasi tentang diskon dan koleksi terbaru</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span></span>
                    </label>
                </div>
                <div class="notification-item">
                    <div>
                        <h3>Email Wishlist</h3>
                        <p class="muted">Notifikasi saat item favorit kamu kembali stok</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span></span>
                    </label>
                </div>
                <div class="notification-item">
                    <div>
                        <h3>Newsletter Mingguan</h3>
                        <p class="muted">Artikel musik dan review album setiap minggu</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span></span>
                    </label>
                </div>
                <div class="form-actions" style="margin-top: 24px;">
                    <button class="btn btn-primary" type="submit">Simpan Preferensi</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.account-container {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 32px;
    max-width: 1000px;
    margin: 48px auto;
    padding: 0 24px;
}

.account-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.account-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.account-nav-item {
    padding: 12px 16px;
    border-radius: 12px;
    color: var(--ink-700);
    text-decoration: none;
    transition: background var(--transition);
}

.account-nav-item:hover {
    background: rgba(59, 49, 42, 0.05);
}

.account-nav-item.is-active {
    background: var(--cream-100);
    color: var(--ink-900);
    font-weight: 600;
}

.account-content {
    position: relative;
}

.account-section {
    display: none;
    opacity: 0;
    transition: opacity var(--transition);
}

.account-section.is-active {
    display: block;
    opacity: 1;
}

.account-section .section-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.account-section h2 {
    font-size: 24px;
    margin-bottom: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn-small {
    padding: 8px 16px;
    font-size: 14px;
}

.address-list {
    display: grid;
    gap: 16px;
}

.address-card {
    padding: 16px;
    border: 1px solid rgba(59, 49, 42, 0.15);
    border-radius: 12px;
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.address-header h3 {
    margin: 0;
    font-size: 16px;
}

.address-actions {
    display: flex;
    gap: 16px;
    margin-top: 12px;
    font-size: 14px;
}

.notification-settings {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border: 1px solid rgba(59, 49, 42, 0.1);
    border-radius: 12px;
}

.notification-item h3 {
    margin: 0 0 4px 0;
    font-size: 16px;
}

.notification-item p {
    margin: 0;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
    cursor: pointer;
}

.toggle-switch input {
    display: none;
}

.toggle-switch span {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(59, 49, 42, 0.15);
    border-radius: 999px;
    transition: background var(--transition);
}

.toggle-switch span::before {
    content: '';
    position: absolute;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: white;
    top: 2px;
    left: 2px;
    transition: transform var(--transition);
}

.toggle-switch input:checked + span {
    background: var(--terracotta-500);
}

.toggle-switch input:checked + span::before {
    transform: translateX(22px);
}

@media (max-width: 768px) {
    .account-container {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .account-sidebar {
        position: relative;
        top: 0;
    }

    .account-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .notification-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.account-nav-item');
    const sections = document.querySelectorAll('.account-section');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const href = item.getAttribute('href');
            
            // Remove active class from all items and sections
            navItems.forEach(i => i.classList.remove('is-active'));
            sections.forEach(s => s.classList.remove('is-active'));
            
            // Add active class to clicked item
            item.classList.add('is-active');
            
            // Show corresponding section
            const section = document.querySelector(href);
            if (section) section.classList.add('is-active');
        });
    });
});
</script>
@endsection
