<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Landing -> halaman beranda
Route::get('/', [HomeController::class, 'home']);
Route::get('/home', [HomeController::class, 'home'])->name('recorda.home');

Route::get('/run-seed', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return 'Database refreshed and seeded!';
});

// Katalog & produk
Route::get('/katalog', [HomeController::class, 'catalog'])->name('recorda.catalog');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('recorda.product');

// Artikel publik
Route::get('/arsip-artikel', [ArticleController::class, 'archive'])->name('recorda.archive');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('recorda.article');

// Keranjang (boleh diakses tamu; isi keranjang disimpan di browser)
Route::view('/keranjang', 'pages.cart')->name('recorda.cart');

// Midtrans: webhook notifikasi (server-to-server) & halaman selesai pembayaran
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('recorda.midtrans.notification');
Route::get('/pembayaran/selesai', [MidtransController::class, 'finish'])->name('recorda.payment.finish');

// ---- Autentikasi ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('recorda.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('recorda.register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('recorda.logout');
Route::view('/lupa-password', 'pages.forgot')->name('recorda.forgot');

// ---- Area pengguna (login wajib) ----
Route::middleware('auth')->group(function () {
    Route::get('/pembayaran', [CheckoutController::class, 'index'])->name('recorda.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('recorda.checkout.store');
    Route::get('/history-transaksi', [AccountController::class, 'history'])->name('recorda.history');
    Route::get('/akun', [AccountController::class, 'account'])->name('recorda.account');
    Route::put('/akun', [AccountController::class, 'updateAccount'])->name('recorda.account.update');
    Route::get('/ubah-password', [AccountController::class, 'changePassword'])->name('recorda.changePassword');
    Route::put('/ubah-password', [AccountController::class, 'updatePassword'])->name('recorda.changePassword.update');
});

// ---- Area admin (login + role admin) ----
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('recorda.dashboard');

    // Produk
    Route::get('/kelola-produk', [AdminProductController::class, 'index'])->name('recorda.manageProducts');
    Route::get('/kelola-produk/tambah', [AdminProductController::class, 'create'])->name('recorda.products.create');
    Route::post('/kelola-produk', [AdminProductController::class, 'store'])->name('recorda.products.store');
    Route::get('/kelola-produk/{product}/edit', [AdminProductController::class, 'edit'])->name('recorda.products.edit');
    Route::put('/kelola-produk/{product}', [AdminProductController::class, 'update'])->name('recorda.products.update');
    Route::delete('/kelola-produk/{product}', [AdminProductController::class, 'destroy'])->name('recorda.products.destroy');

    // Artikel
    Route::get('/kelola-artikel', [AdminArticleController::class, 'index'])->name('recorda.manageArticles');
    Route::get('/kelola-artikel/tambah', [AdminArticleController::class, 'create'])->name('recorda.articles.create');
    Route::post('/kelola-artikel', [AdminArticleController::class, 'store'])->name('recorda.articles.store');
    Route::get('/kelola-artikel/{article}/edit', [AdminArticleController::class, 'edit'])->name('recorda.articles.edit');
    Route::put('/kelola-artikel/{article}', [AdminArticleController::class, 'update'])->name('recorda.articles.update');
    Route::delete('/kelola-artikel/{article}', [AdminArticleController::class, 'destroy'])->name('recorda.articles.destroy');

    // Pengguna
    Route::get('/kelola-pengguna', [AdminUserController::class, 'index'])->name('recorda.manageUsers');
    Route::get('/kelola-pengguna/tambah', [AdminUserController::class, 'create'])->name('recorda.users.create');
    Route::post('/kelola-pengguna', [AdminUserController::class, 'store'])->name('recorda.users.store');
    Route::get('/kelola-pengguna/{user}/edit', [AdminUserController::class, 'edit'])->name('recorda.users.edit');
    Route::put('/kelola-pengguna/{user}', [AdminUserController::class, 'update'])->name('recorda.users.update');
    Route::patch('/kelola-pengguna/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('recorda.users.toggleStatus');
    Route::patch('/kelola-pengguna/{user}/role', [AdminUserController::class, 'updateRole'])->name('recorda.users.updateRole');
    Route::delete('/kelola-pengguna/{user}', [AdminUserController::class, 'destroy'])->name('recorda.users.destroy');

    // Transaksi
    Route::get('/kelola-transaksi', [AdminTransactionController::class, 'index'])->name('recorda.manageTransactions');
    Route::patch('/kelola-transaksi/{order}/status', [AdminTransactionController::class, 'updateStatus'])->name('recorda.transactions.updateStatus');
});

Route::get('/clear-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cache Laravel berhasil dihapus!';
});