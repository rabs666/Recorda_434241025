<?php

namespace App\Support;

use Illuminate\Support\Str;

class Media
{
    /**
     * Resolusi nilai kolom gambar menjadi URL siap pakai.
     * - null/'' => null (view memakai cover placeholder)
     * - http(s)://... => dikembalikan apa adanya (URL eksternal)
     * - selain itu => dianggap path di disk "public" => asset('storage/...')
     */
    public static function url(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        // Gambar bawaan yang disimpan di folder public (mis. public/images/...).
        if (Str::startsWith($value, 'images/')) {
            return asset(ltrim($value, '/'));
        }

        // Selain itu dianggap hasil upload di disk "public" (storage/app/public).
        return asset('storage/' . ltrim($value, '/'));
    }
}
