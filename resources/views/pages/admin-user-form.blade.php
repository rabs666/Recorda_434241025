@extends('layouts.admin')

@php($isEdit = $user->exists)

@section('title', 'Recorda - ' . ($isEdit ? 'Edit Pengguna' : 'Tambah Pengguna'))
@section('page_title', $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="panel">
    <form method="POST" action="{{ $isEdit ? route('recorda.users.update', $user) : route('recorda.users.store') }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-grid">
            <label class="form-control">
                <span>Nama</span>
                <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </label>
            <label class="form-control">
                <span>Email</span>
                <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Role</span>
                <select class="input" name="role" required>
                    @foreach(['user' => 'User', 'admin' => 'Admin'] as $value => $labelText)
                        <option value="{{ $value }}" @selected(old('role', $user->role ?? 'user') === $value)>{{ $labelText }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span>Status</span>
                <select class="input" name="status" required>
                    @foreach(['aktif' => 'Aktif (bisa login)', 'nonaktif' => 'Nonaktif (diblokir login)'] as $value => $labelText)
                        <option value="{{ $value }}" @selected(old('status', $user->status ?? 'aktif') === $value)>{{ $labelText }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Password{{ $isEdit ? ' (kosongkan jika tidak diubah)' : '' }}</span>
                <input class="input" type="password" name="password" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password">
            </label>
            <label class="form-control">
                <span>Konfirmasi Password</span>
                <input class="input" type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password">
            </label>
        </div>
        <p class="muted" style="font-size:12px;">Minimal 8 karakter. Pengguna login dengan email & password ini, dan statusnya harus Aktif.</p>

        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
            <a class="btn btn-ghost" href="{{ route('recorda.manageUsers') }}">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}</button>
        </div>
    </form>
</div>
@endsection
