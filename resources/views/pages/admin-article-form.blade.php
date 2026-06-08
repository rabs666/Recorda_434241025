@extends('layouts.admin')

@php($isEdit = $article->exists)

@section('title', 'Recorda - ' . ($isEdit ? 'Edit Artikel' : 'Tambah Artikel'))
@section('page_title', $isEdit ? 'Edit Artikel' : 'Tambah Artikel')

@section('content')
<div class="panel">
    <form method="POST" action="{{ $isEdit ? route('recorda.articles.update', $article) : route('recorda.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <label class="form-control">
            <span>Judul Artikel</span>
            <input class="input" type="text" name="title" value="{{ old('title', $article->title) }}" required>
        </label>

        <div class="form-grid">
            <label class="form-control">
                <span>Kategori</span>
                <select class="input" name="category" required>
                    @foreach(['Review', 'Tips', 'Rilis', 'Sejarah'] as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $article->category ?? 'Review') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span>Status</span>
                <select class="input" name="status" required>
                    <option value="publish" @selected(old('status', $article->status ?? 'publish') === 'publish')>Publish</option>
                    <option value="draft" @selected(old('status', $article->status ?? 'publish') === 'draft')>Draft</option>
                </select>
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Cover</span>
                <select class="input" name="cover" required>
                    @foreach(['thumb-a', 'thumb-b', 'thumb-c', 'thumb-d', 'thumb-e', 'thumb-f'] as $cv)
                        <option value="{{ $cv }}" @selected(old('cover', $article->cover ?? 'thumb-a') === $cv)>{{ $cv }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span>Tanggal Terbit</span>
                <input class="input" type="date" name="published_at" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d')) }}">
            </label>
        </div>

        <label class="form-control">
            <span>Penulis</span>
            <input class="input" type="text" name="author" value="{{ old('author', $article->author ?? 'Admin') }}">
        </label>

        <div class="form-control">
            <span>Foto Artikel</span>
            @if($article->imageUrl())
                <div style="margin:8px 0;">
                    <img src="{{ $article->imageUrl() }}" alt="Foto artikel" style="width:200px; height:120px; object-fit:cover; border-radius:8px;">
                    <p class="muted" style="font-size:12px;">Foto saat ini. Unggah / isi URL baru untuk mengganti.</p>
                </div>
            @endif
            <input class="input" type="file" name="image" accept="image/*">
            <p class="muted" style="font-size:12px; margin:6px 0;">atau tempel URL gambar:</p>
            <input class="input" type="text" name="image_url" value="{{ old('image_url') }}" placeholder="https://contoh.com/foto.jpg">
            <p class="muted" style="font-size:12px;">Kosongkan keduanya jika tidak ingin mengubah foto.</p>
        </div>

        <label class="form-control">
            <span>Ringkasan (excerpt)</span>
            <textarea class="input" name="excerpt" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
        </label>

        <label class="form-control">
            <span>Isi Artikel (boleh HTML sederhana)</span>
            <textarea class="input" name="body" rows="10">{{ old('body', $article->body) }}</textarea>
        </label>

        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
            <a class="btn btn-ghost" href="{{ route('recorda.manageArticles') }}">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Artikel' }}</button>
        </div>
    </form>
</div>
@endsection
