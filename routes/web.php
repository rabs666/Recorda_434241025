<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});

Route::get('/home', function () {
    return view('pages.home');
})->name('recorda.home');

Route::get('/arsip-artikel', function () {
    return view('pages.archive');
})->name('recorda.archive');

Route::get('/artikel', function () {
    return view('pages.article');
})->name('recorda.article');

Route::get('/login', function () {
    return view('pages.login');
})->name('recorda.login');

Route::get('/register', function () {
    return view('pages.register');
})->name('recorda.register');

Route::get('/lupa-password', function () {
    return view('pages.forgot');
})->name('recorda.forgot');
