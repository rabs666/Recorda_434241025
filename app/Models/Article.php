<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'slug', 'title', 'category', 'excerpt', 'body', 'cover', 'image',
        'author', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    /**
     * URL gambar artikel (URL eksternal atau file upload). Null bila belum ada.
     */
    public function imageUrl(): ?string
    {
        return \App\Support\Media::url($this->image);
    }

    public function isPublished(): bool
    {
        return $this->status === 'publish';
    }

    /**
     * Tanggal tampil (published_at jika ada, kalau tidak created_at).
     */
    public function displayDate(): string
    {
        $date = $this->published_at ?? $this->created_at;

        return $date ? $date->translatedFormat('d M Y') : '';
    }
}
