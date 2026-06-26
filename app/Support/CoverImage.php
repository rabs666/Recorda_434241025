<?php

namespace App\Support;

class CoverImage
{
    /**
     * Palet warna gradien (gelap -> terang) untuk variasi cover.
     */
    private const PALETTES = [
        ['#3b2416', '#c75b35'],
        ['#6a7a4b', '#aac08a'],
        ['#1e2c3d', '#4a90a4'],
        ['#5a341a', '#e0a48c'],
        ['#2d2438', '#9b6bbf'],
        ['#143027', '#3fa37a'],
        ['#3d1f2c', '#d36a8a'],
        ['#27303d', '#7f8c9b'],
    ];

    /**
     * Buat cover album (kotak) sebagai file SVG lokal.
     * Mengembalikan path relatif publik (mis. "images/covers/abbey-road.svg").
     */
    public static function forProduct(string $slug, string $name, ?string $artist, int $index): string
    {
        $jpgPath = 'images/covers/' . $slug . '.jpg';
        if (file_exists(public_path($jpgPath))) {
            return $jpgPath;
        }

        $pngPath = 'images/covers/' . $slug . '.png';
        if (file_exists(public_path($pngPath))) {
            return $pngPath;
        }

        [$dark, $light] = self::PALETTES[$index % count(self::PALETTES)];

        $title = self::esc(self::shorten($name, 22));
        $sub = self::esc(self::shorten($artist ?? '', 24));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$dark}"/>
      <stop offset="1" stop-color="{$light}"/>
    </linearGradient>
  </defs>
  <rect width="600" height="600" fill="url(#g)"/>
  <circle cx="300" cy="250" r="150" fill="rgba(0,0,0,0.28)"/>
  <circle cx="300" cy="250" r="52" fill="rgba(255,255,255,0.16)"/>
  <circle cx="300" cy="250" r="14" fill="rgba(0,0,0,0.45)"/>
  <text x="300" y="470" text-anchor="middle" font-family="Georgia, serif" font-size="42" font-weight="700" fill="#ffffff">{$title}</text>
  <text x="300" y="512" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" fill="rgba(255,255,255,0.82)">{$sub}</text>
</svg>
SVG;

        return self::write('images/covers', $slug, $svg);
    }

    /**
     * Buat gambar artikel (landscape) sebagai file SVG lokal.
     */
    public static function forArticle(string $slug, string $title, string $category, int $index): string
    {
        [$dark, $light] = self::PALETTES[($index + 3) % count(self::PALETTES)];

        $cat = self::esc(strtoupper($category));
        $head = self::esc(self::shorten($title, 30));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$dark}"/>
      <stop offset="1" stop-color="{$light}"/>
    </linearGradient>
  </defs>
  <rect width="800" height="500" fill="url(#g)"/>
  <rect x="48" y="48" width="120" height="34" rx="17" fill="rgba(255,255,255,0.2)"/>
  <text x="108" y="71" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#ffffff">{$cat}</text>
  <text x="48" y="300" font-family="Georgia, serif" font-size="40" font-weight="700" fill="#ffffff">{$head}</text>
  <text x="48" y="346" font-family="Arial, sans-serif" font-size="20" fill="rgba(255,255,255,0.8)">Recorda Journal</text>
</svg>
SVG;

        return self::write('images/articles', $slug, $svg);
    }

    private static function write(string $dir, string $slug, string $svg): string
    {
        $fullDir = public_path($dir);
        if (! is_dir($fullDir)) {
            @mkdir($fullDir, 0775, true);
        }

        $relative = $dir . '/' . $slug . '.svg';
        file_put_contents(public_path($relative), $svg);

        return $relative;
    }

    private static function shorten(string $text, int $max): string
    {
        $text = trim($text);

        return mb_strlen($text) > $max ? mb_substr($text, 0, $max - 1) . '…' : $text;
    }

    private static function esc(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
