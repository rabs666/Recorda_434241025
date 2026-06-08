<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug', 'name', 'artist', 'year', 'format', 'genre', 'price', 'stock',
        'label', 'condition', 'description', 'cover', 'image', 'gallery', 'covers', 'badge',
        'tracklist', 'is_active',
    ];

    protected $casts = [
        'covers' => 'array',
        'gallery' => 'array',
        'tracklist' => 'array',
        'is_active' => 'boolean',
        'price' => 'integer',
        'stock' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Status stok untuk badge admin: Habis / Rendah / Aktif.
     */
    public function stockStatus(): string
    {
        if ($this->stock <= 0) {
            return 'Habis';
        }

        if ($this->stock <= 5) {
            return 'Rendah';
        }

        return 'Aktif';
    }

    public function stockBadgeClass(): string
    {
        return match ($this->stockStatus()) {
            'Habis' => 'is-danger',
            'Rendah' => 'is-warning',
            default => 'is-success',
        };
    }

    /**
     * URL gambar produk: dukung URL eksternal maupun file upload (storage).
     * Mengembalikan null jika belum ada gambar (view akan pakai cover placeholder).
     */
    public function imageUrl(): ?string
    {
        return \App\Support\Media::url($this->image);
    }

    /**
     * Daftar URL foto galeri (foto detail tambahan), sudah diresolusi.
     */
    public function galleryUrls(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($g) => \App\Support\Media::url($g))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Tag pencarian/filter katalog (format + genre + badge), lowercase.
     */
    public function tags(): string
    {
        return strtolower(trim(($this->format ?? '') . ' ' . ($this->genre ?? '') . ' ' . ($this->badge ?? '')));
    }
}
