<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $body = "<p>Album ini dibuka dengan tekstur synth tebal yang langsung mengarah ke era 80an, tapi tetap punya punch modern dari drum yang presisi.</p>\n"
            . "<p>Di track kedua, vokal layered memberi ruang pada bassline yang santai, cocok untuk didengar di malam hari saat lampu kota mulai meredup.</p>\n"
            . "<p>Secara keseluruhan, mixing terasa bersih dan detail, terutama di bagian chorus yang terasa lebih luas tanpa kehilangan fokus pada vokal utama.</p>";

        $articles = [
            [
                'slug' => 'heavy-serenade-review',
                'title' => 'Review album Heavy Serenade NMIXX',
                'category' => 'Review',
                'excerpt' => 'NMIXX melakukan debut terbarunya dengan warna synth dan brass yang tebal.',
                'cover' => 'thumb-a',
                'status' => 'publish',
                'published_at' => '2026-05-12',
            ],
            [
                'slug' => 'turntable-setup',
                'title' => 'Checklist setup turntable pemula',
                'category' => 'Tips',
                'excerpt' => 'Panduan ringkas mulai dari cartridge sampai setting anti skate.',
                'cover' => 'thumb-b',
                'status' => 'publish',
                'published_at' => '2026-05-10',
            ],
            [
                'slug' => 'indie-cassette-reissue',
                'title' => 'Rilis ulang kaset indie lokal dari tahun 2000an',
                'category' => 'Rilis',
                'excerpt' => 'Recorda menghadirkan ulang kaset langka dengan jumlah press terbatas.',
                'cover' => 'thumb-c',
                'status' => 'publish',
                'published_at' => '2026-05-08',
            ],
            [
                'slug' => 'vinyl-jazz-soundstage',
                'title' => 'Soundstage vinyl jazz dan cara menikmati detailnya',
                'category' => 'Review',
                'excerpt' => 'Tips memilih pressing dan cara merawat piringan agar tetap bersih.',
                'cover' => 'thumb-d',
                'status' => 'publish',
                'published_at' => '2026-05-06',
            ],
            [
                'slug' => 'stylus-cleaning',
                'title' => 'Membersihkan stylus dengan aman di rumah',
                'category' => 'Tips',
                'excerpt' => 'Langkah sederhana agar stylus tetap tajam dan musik tetap jernih.',
                'cover' => 'thumb-e',
                'status' => 'draft',
                'published_at' => '2026-05-02',
            ],
            [
                'slug' => 'folk-pressing-review',
                'title' => 'Review pressing ulang album klasik folk 1972',
                'category' => 'Review',
                'excerpt' => 'Dibandingkan versi lama, pressing baru punya detail vokal lebih jelas.',
                'cover' => 'thumb-f',
                'status' => 'publish',
                'published_at' => '2026-04-29',
            ],
        ];

        foreach ($articles as $index => $article) {
            $image = \App\Support\CoverImage::forArticle(
                $article['slug'], $article['title'], $article['category'], $index
            );

            // firstOrCreate: jangan menimpa artikel yang sudah diedit admin.
            Article::firstOrCreate(
                ['slug' => $article['slug']],
                array_merge($article, [
                    'author' => 'Admin',
                    'body' => $body,
                    'image' => $image,
                ])
            );
        }
    }
}
