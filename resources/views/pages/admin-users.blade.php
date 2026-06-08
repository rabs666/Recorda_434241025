@extends('layouts.admin')

@section('title', 'Recorda - Kelola Pengguna')
@section('page_title', 'Kelola Pengguna')

@section('admin_top_actions')
    <span class="admin-count">Total: {{ $users->count() }} pengguna</span>
    <a class="btn btn-primary btn-compact" href="{{ route('recorda.users.create') }}">+ Tambah Pengguna</a>
@endsection

@section('content')
<div class="admin-tools">
    <input class="input" type="search" placeholder="Cari nama / email..." data-admin-search>
</div>

<div class="panel">
    <div class="admin-table-scroll">
    <div class="table" data-admin-table>
        <div class="table-row table-head columns-6">
            <div>Nama</div>
            <div>Email</div>
            <div>Tgl Daftar</div>
            <div>Role</div>
            <div>Status</div>
            <div>Aksi</div>
        </div>
        @forelse($users as $user)
        <div class="table-row columns-6" data-table-row>
            <div class="user-cell"><span class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span> <span data-search-text>{{ $user->name }}</span></div>
            <div>{{ $user->email }}</div>
            <div>{{ optional($user->created_at)->translatedFormat('d M Y') }}</div>
            <div>
                <form method="POST" action="{{ route('recorda.users.updateRole', $user) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <select class="input" name="role" onchange="this.form.submit()" style="padding:4px 8px;">
                        <option value="user" @selected($user->role === 'user')>User</option>
                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                    </select>
                </form>
            </div>
            <div>
                <span class="status-badge {{ $user->status === 'aktif' ? 'is-success' : 'is-danger' }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
            <div class="table-actions">
                <a class="btn btn-light btn-compact" href="{{ route('recorda.users.edit', $user) }}">Edit</a>
                <form method="POST" action="{{ route('recorda.users.toggleStatus', $user) }}" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-light btn-compact" type="submit">{{ $user->status === 'aktif' ? 'Nonaktif' : 'Aktifkan' }}</button>
                </form>
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('recorda.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-compact" type="submit">Hapus</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="table-row"><div>Belum ada pengguna.</div></div>
        @endforelse
    </div>
    </div>
</div>
@endsection
