@extends('layouts.admin')

@php($isEdit = $product->exists)

@section('title', 'Recorda - ' . ($isEdit ? 'Edit Produk' : 'Tambah Produk'))
@section('page_title', $isEdit ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="panel">
    <form method="POST" action="{{ $isEdit ? route('recorda.products.update', $product) : route('recorda.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="form-grid">
            <label class="form-control">
                <span>Nama Produk</span>
                <input class="input" type="text" name="name" value="{{ old('name', $product->name) }}" required>
            </label>
            <label class="form-control">
                <span>Artis</span>
                <input class="input" type="text" name="artist" value="{{ old('artist', $product->artist) }}">
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Format</span>
                <select class="input" name="format" required>
                    @foreach(['Vinyl 12"', 'CD', 'Kaset'] as $fmt)
                        <option value="{{ $fmt }}" @selected(old('format', $product->format) === $fmt)>{{ $fmt }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span>Genre</span>
                <input class="input" type="text" name="genre" value="{{ old('genre', $product->genre) }}">
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Harga (Rp)</span>
                <input class="input" type="number" name="price" value="{{ old('price', $product->price ?? 0) }}" min="0" required>
            </label>
            <label class="form-control">
                <span>Stok</span>
                <input class="input" type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required>
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Tahun</span>
                <input class="input" type="number" name="year" value="{{ old('year', $product->year) }}" min="1900" max="2100">
            </label>
            <label class="form-control">
                <span>Label</span>
                <input class="input" type="text" name="label" value="{{ old('label', $product->label) }}">
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Kondisi</span>
                <select class="input" name="condition">
                    @foreach(['New', 'Mint', 'Good', 'Used'] as $cond)
                        <option value="{{ $cond }}" @selected(old('condition', $product->condition ?? 'New') === $cond)>{{ $cond }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span>Cover</span>
                <select class="input" name="cover" required>
                    @foreach(['cover-a', 'cover-b', 'cover-c', 'cover-d'] as $cv)
                        <option value="{{ $cv }}" @selected(old('cover', $product->cover ?? 'cover-a') === $cv)>{{ $cv }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="form-grid">
            <label class="form-control">
                <span>Badge (opsional)</span>
                <input class="input" type="text" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Limited / New / Ready">
            </label>
            <label class="form-control">
                <span>Status</span>
                <select class="input" name="is_active">
                    <option value="1" @selected(old('is_active', $product->is_active ?? true))>Aktif (tampil di katalog)</option>
                    <option value="0" @selected(!old('is_active', $product->is_active ?? true))>Nonaktif (disembunyikan)</option>
                </select>
            </label>
        </div>

        <div class="form-control">
            <span>Foto Produk</span>
            @if($product->imageUrl())
                <div style="margin:8px 0;">
                    <img src="{{ $product->imageUrl() }}" alt="Foto produk" style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                    <p class="muted" style="font-size:12px;">Foto saat ini. Unggah / isi URL baru untuk mengganti.</p>
                </div>
            @endif
            <input class="input" type="file" name="image" accept="image/*">
            <p class="muted" style="font-size:12px; margin:6px 0;">atau tempel URL gambar:</p>
            <input class="input" type="text" name="image_url" value="{{ old('image_url') }}" placeholder="https://contoh.com/cover.jpg">
            <p class="muted" style="font-size:12px;">Kosongkan keduanya jika tidak ingin mengubah foto. Jika tidak ada foto, cover warna di bawah dipakai.</p>
        </div>

        <div class="form-control">
            <span>Foto Detail Tambahan (Galeri)</span>
            @if(count($product->galleryUrls()))
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin:8px 0;">
                    @foreach($product->galleryUrls() as $g)
                        <img src="{{ $g }}" alt="Galeri" style="width:72px; height:72px; object-fit:cover; border-radius:8px;">
                    @endforeach
                </div>
            @endif
            <input class="input" type="file" name="gallery_files[]" accept="image/*" multiple>
            <p class="muted" style="font-size:12px; margin:6px 0;">atau tempel beberapa URL gambar (satu per baris):</p>
            <textarea class="input" name="gallery_urls" rows="3" placeholder="https://contoh.com/foto1.jpg&#10;https://contoh.com/foto2.jpg"></textarea>
            <p class="muted" style="font-size:12px;">Mengisi salah satu akan <b>mengganti</b> seluruh galeri. Kosongkan untuk mempertahankan galeri lama. Foto ini tampil sebagai thumbnail di halaman detail produk.</p>
        </div>

        <label class="form-control">
            <span>Deskripsi</span>
            <textarea class="input" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        </label>

        <label class="form-control">
            <span>Tracklist (satu lagu per baris, format: Judul | 3:20)</span>
            <textarea class="input" name="tracklist_raw" rows="5">{{ old('tracklist_raw', collect($product->tracklist ?? [])->map(fn($t) => trim(($t['title'] ?? '') . ' | ' . ($t['duration'] ?? '')))->implode("\n")) }}</textarea>
        </label>

        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
            <a class="btn btn-ghost" href="{{ route('recorda.manageProducts') }}">Batal</a>
            <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
        </div>
    </form>
</div>
@endsection
