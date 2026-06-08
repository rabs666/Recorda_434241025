@extends('layouts.admin')

@section('title', 'Recorda - Kelola Artikel')
@section('page_title', 'Kelola Artikel')

@section('admin_top_actions')
    <a class="btn btn-primary btn-compact" href="{{ route('recorda.articles.create') }}">+ Tambah Artikel</a>
@endsection

@section('content')
<div class="admin-tools">
    <input class="input" type="search" placeholder="Cari judul artikel..." data-admin-search>
</div>

<div class="panel">
    <div class="admin-table-scroll">
    <div class="table" data-admin-table>
        <div class="table-row table-head columns-6">
            <div>No</div>
            <div>Judul Artikel</div>
            <div>Kategori</div>
            <div>Tanggal</div>
            <div>Status</div>
            <div>Aksi</div>
        </div>
        @forelse($articles as $article)
        <div class="table-row columns-6" data-table-row>
            <div>{{ $loop->iteration }}</div>
            <div data-search-text>{{ $article->title }}</div>
            <div>{{ $article->category }}</div>
            <div>{{ $article->displayDate() }}</div>
            <div>
                <span class="status-badge {{ $article->status === 'publish' ? 'is-success' : 'is-warning' }}">
                    {{ $article->status === 'publish' ? 'Publish' : 'Draft' }}
                </span>
            </div>
            <div class="table-actions">
                <a class="btn btn-light btn-compact" href="{{ route('recorda.articles.edit', $article) }}">Edit</a>
                <form method="POST" action="{{ route('recorda.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-compact" type="submit">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="table-row"><div>Belum ada artikel.</div></div>
        @endforelse
    </div>
    </div>
</div>
@endsection
