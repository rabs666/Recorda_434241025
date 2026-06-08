<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $index = 0;

        foreach ($this->albums() as $slug => $album) {
            $badge = $this->badgeFor($album);
            $image = \App\Support\CoverImage::forProduct($slug, $album['name'], $album['artist'] ?? null, $index);
            $index++;

            // firstOrCreate: hanya membuat jika belum ada, TIDAK menimpa produk
            // yang sudah diedit admin (foto/galeri/harga tetap aman saat re-seed).
            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $album['name'],
                    'artist' => $album['artist'],
                    'year' => $album['year'],
                    'format' => $album['format'],
                    'genre' => $album['genre'],
                    'price' => $album['price'],
                    'stock' => $album['stock'],
                    'label' => $album['label'],
                    'condition' => $album['condition'],
                    'description' => $album['description'],
                    'cover' => $album['covers'][0] ?? 'cover-a',
                    'image' => $image,
                    'covers' => $album['covers'],
                    'badge' => $badge,
                    'tracklist' => $album['tracklist'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function badgeFor(array $album): string
    {
        if (($album['condition'] ?? '') === 'Mint') {
            return 'Collector';
        }

        if (($album['year'] ?? 0) >= 2024) {
            return 'New';
        }

        if (($album['format'] ?? '') === 'Vinyl 12"') {
            return 'Limited';
        }

        return 'Ready';
    }

    private function albums(): array
    {
        return [
            'abbey-road' => [
                'name' => 'Abbey Road', 'artist' => 'The Beatles', 'year' => 1969, 'format' => 'Vinyl 12"', 'price' => 450000, 'stock' => 12, 'label' => 'Apple Records', 'condition' => 'Mint', 'genre' => 'Rock', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Pressing klasik dengan detail suara yang jernih. Cocok untuk kolektor baru maupun lama.',
                'tracklist' => [
                    ['title' => 'Come Together', 'duration' => '4:20'],
                    ['title' => 'Something', 'duration' => '3:03'],
                    ['title' => "Maxwell's Silver Hammer", 'duration' => '3:27'],
                    ['title' => "Oh! Darling", 'duration' => '3:26'],
                    ['title' => 'Here Comes the Sun', 'duration' => '3:05'],
                ],
            ],
            'rumours' => [
                'name' => 'Rumours', 'artist' => 'Fleetwood Mac', 'year' => 1977, 'format' => 'CD', 'price' => 250000, 'stock' => 15, 'label' => 'Warner Bros', 'condition' => 'New', 'genre' => 'Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Salah satu album klasik pop rock terbaik sepanjang masa.',
                'tracklist' => [
                    ['title' => 'Second Hand News', 'duration' => '3:45'],
                    ['title' => 'Dreams', 'duration' => '4:16'],
                    ['title' => "Don't Stop", 'duration' => '3:10'],
                    ['title' => 'Go Your Own Way', 'duration' => '3:38'],
                    ['title' => 'Songbird', 'duration' => '3:20'],
                ],
            ],
            'blue-train' => [
                'name' => 'Blue Train', 'artist' => 'John Coltrane', 'year' => 1958, 'format' => 'Vinyl 12"', 'price' => 520000, 'stock' => 5, 'label' => 'Blue Note Records', 'condition' => 'Mint', 'genre' => 'Jazz', 'covers' => ['cover-c', 'cover-a', 'cover-b', 'cover-d'],
                'description' => 'Masterpiece jazz album dengan Coltrane di puncak kreativitasnya.',
                'tracklist' => [
                    ['title' => 'Blue Train', 'duration' => '5:23'],
                    ['title' => "Moment's Notice", 'duration' => '5:50'],
                    ['title' => 'Locomotion', 'duration' => '4:56'],
                    ['title' => "I've Got Your Number", 'duration' => '3:37'],
                    ['title' => 'Lazy Afternoon', 'duration' => '5:12'],
                ],
            ],
            'after-hours' => [
                'name' => 'After Hours', 'artist' => 'The Weeknd', 'year' => 2020, 'format' => 'Kaset', 'price' => 185000, 'stock' => 7, 'label' => 'XO / Republic Records', 'condition' => 'New', 'genre' => 'R&B', 'covers' => ['cover-d', 'cover-b', 'cover-c', 'cover-a'],
                'description' => 'Album terbaru The Weeknd dengan hit singles yang memorable.',
                'tracklist' => [
                    ['title' => 'Alone Again', 'duration' => '5:10'],
                    ['title' => 'Too Late', 'duration' => '4:25'],
                    ['title' => 'Hardest to Love', 'duration' => '4:10'],
                    ['title' => 'Escape from LA', 'duration' => '5:32'],
                    ['title' => 'Blinding Lights', 'duration' => '3:20'],
                ],
            ],
            'amber-lights' => [
                'name' => 'Amber Lights', 'artist' => 'Fictional Act', 'year' => 2014, 'format' => 'Vinyl 12"', 'price' => 390000, 'stock' => 6, 'label' => 'Indie Records', 'condition' => 'New', 'genre' => 'Indie', 'covers' => ['cover-a', 'cover-d', 'cover-c', 'cover-b'],
                'description' => 'Album indie yang indah dengan melodi yang memikat.',
                'tracklist' => [
                    ['title' => 'Golden Hour', 'duration' => '4:12'],
                    ['title' => 'Amber Lights', 'duration' => '5:01'],
                    ['title' => 'Midnight Glow', 'duration' => '3:55'],
                    ['title' => 'Starry Night', 'duration' => '4:33'],
                    ['title' => 'Dreams of Tomorrow', 'duration' => '5:20'],
                ],
            ],
            'quiet-motion' => [
                'name' => 'Quiet Motion', 'artist' => 'Midnight City', 'year' => 2022, 'format' => 'CD', 'price' => 210000, 'stock' => 9, 'label' => 'Alternative Sounds', 'condition' => 'New', 'genre' => 'Alternative', 'covers' => ['cover-c', 'cover-b', 'cover-a', 'cover-d'],
                'description' => 'Album alternatif dengan sentuhan elektronik yang segar.',
                'tracklist' => [
                    ['title' => 'Motion', 'duration' => '4:20'],
                    ['title' => 'Quiet Nights', 'duration' => '3:45'],
                    ['title' => 'Electric Dreams', 'duration' => '4:50'],
                    ['title' => 'Silent City', 'duration' => '5:10'],
                    ['title' => 'Neon Lights', 'duration' => '4:02'],
                ],
            ],
            'ad-mare' => [
                'name' => 'AD MARE', 'artist' => 'NMIXX', 'year' => 2022, 'format' => 'CD', 'price' => 180000, 'stock' => 8, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Single album pertama NMIXX dengan title track O.O yang catchy dan energik.',
                'tracklist' => [
                    ['title' => 'TANK', 'duration' => '3:02'],
                    ['title' => 'O.O', 'duration' => '3:15'],
                    ['title' => 'O.O (Inst.)', 'duration' => '3:12'],
                ],
            ],
            'entwurf' => [
                'name' => 'ENTWURF', 'artist' => 'NMIXX', 'year' => 2022, 'format' => 'CD', 'price' => 195000, 'stock' => 6, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Single album kedua dengan title track DICE yang unik dan fresh.',
                'tracklist' => [
                    ['title' => 'COOL (Your Rainbow)', 'duration' => '3:18'],
                    ['title' => 'DICE', 'duration' => '3:25'],
                    ['title' => 'DICE (Inst.)', 'duration' => '3:22'],
                ],
            ],
            'a-midsummer-nmixxs-dream' => [
                'name' => "A Midsummer NMIXX's Dream", 'artist' => 'NMIXX', 'year' => 2023, 'format' => 'CD', 'price' => 200000, 'stock' => 10, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Single album dengan vibe summer yang menyenangkan.',
                'tracklist' => [
                    ['title' => 'Roller Coaster', 'duration' => '3:30'],
                    ['title' => "Party O'Clock", 'duration' => '3:40'],
                    ['title' => 'Roller Coaster (Inst.)', 'duration' => '3:28'],
                ],
            ],
            'expergo' => [
                'name' => 'Expérgo', 'artist' => 'NMIXX', 'year' => 2023, 'format' => 'CD', 'price' => 205000, 'stock' => 7, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'EP pertama dengan banyak track baru dan kolaborasi menarik.',
                'tracklist' => [
                    ['title' => 'Young, Dumb, Stupid', 'duration' => '3:02'],
                    ['title' => 'Love Me Like This', 'duration' => '3:18'],
                    ['title' => 'PAXXWORD', 'duration' => '3:45'],
                    ['title' => 'My Gosh', 'duration' => '3:20'],
                    ['title' => 'HOME', 'duration' => '4:12'],
                ],
            ],
            'fe3o4-break' => [
                'name' => 'Fe3O4: BREAK', 'artist' => 'NMIXX', 'year' => 2024, 'format' => 'CD', 'price' => 210000, 'stock' => 9, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Mini album dengan konsep break yang berani dan experimental.',
                'tracklist' => [
                    ['title' => 'Soñar (Breaker)', 'duration' => '3:15'],
                    ['title' => 'DASH', 'duration' => '3:42'],
                    ['title' => 'Run For Roses', 'duration' => '3:20'],
                    ['title' => 'BOOM', 'duration' => '3:38'],
                    ['title' => 'Passionfruit', 'duration' => '4:00'],
                ],
            ],
            'fe3o4-stick-out' => [
                'name' => 'Fe3O4: STICK OUT', 'artist' => 'NMIXX', 'year' => 2024, 'format' => 'CD', 'price' => 210000, 'stock' => 8, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Mini album dengan style yang bold dan standout di industri.',
                'tracklist' => [
                    ['title' => 'See That? (별별별)', 'duration' => '3:50'],
                    ['title' => 'SICKUHH', 'duration' => '3:15'],
                    ['title' => 'Red Light Sign But We Go', 'duration' => '3:30'],
                    ['title' => 'BEAT BEAT', 'duration' => '3:25'],
                    ['title' => 'Moving On', 'duration' => '3:45'],
                ],
            ],
            'fe3o4-forward' => [
                'name' => 'Fe3O4: FORWARD', 'artist' => 'NMIXX', 'year' => 2025, 'format' => 'CD', 'price' => 215000, 'stock' => 11, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Mini album terakhir dari series Fe3O4 dengan visi ke masa depan.',
                'tracklist' => [
                    ['title' => 'High Horse', 'duration' => '3:35'],
                    ['title' => 'KNOW ABOUT ME', 'duration' => '3:55'],
                    ['title' => 'Slingshot (<★)', 'duration' => '3:20'],
                    ['title' => 'Golden Recipe', 'duration' => '4:10'],
                    ['title' => 'Papillon', 'duration' => '3:48'],
                ],
            ],
            'heavy-serenade' => [
                'name' => 'Heavy Serenade', 'artist' => 'NMIXX', 'year' => 2026, 'format' => 'CD', 'price' => 220000, 'stock' => 14, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Full album terbaru NMIXX dengan title track Heavy Serenade yang épik.',
                'tracklist' => [
                    ['title' => 'Crescendo', 'duration' => '3:45'],
                    ['title' => 'Heavy Serenade', 'duration' => '4:02'],
                    ['title' => 'IDESERVEIT', 'duration' => '3:28'],
                    ['title' => 'Different Girl', 'duration' => '3:50'],
                    ['title' => 'Superior', 'duration' => '3:35'],
                    ['title' => 'LOUD', 'duration' => '3:42'],
                ],
            ],
            'born-pink' => [
                'name' => 'Born Pink', 'artist' => 'BLACKPINK', 'year' => 2022, 'format' => 'CD', 'price' => 220000, 'stock' => 20, 'label' => 'YG Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album pinky dengan hits seperti Pink Venom dan Shut Down.',
                'tracklist' => [
                    ['title' => 'Pink Venom', 'duration' => '3:03'],
                    ['title' => 'Shut Down', 'duration' => '3:32'],
                    ['title' => 'Tally', 'duration' => '3:15'],
                    ['title' => 'Hard to Love', 'duration' => '3:18'],
                    ['title' => 'Typa Girl', 'duration' => '2:52'],
                ],
            ],
            'the-album' => [
                'name' => 'The Album', 'artist' => 'BLACKPINK', 'year' => 2020, 'format' => 'CD', 'price' => 210000, 'stock' => 18, 'label' => 'YG Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album pertama BLACKPINK dengan Ice Cream dan Bet You Wanna.',
                'tracklist' => [
                    ['title' => 'Ice Cream', 'duration' => '3:00'],
                    ['title' => 'Bet You Wanna', 'duration' => '3:15'],
                    ['title' => 'Lovesick Girls', 'duration' => '3:02'],
                    ['title' => 'Hard to Love', 'duration' => '3:18'],
                    ['title' => 'Tally', 'duration' => '3:12'],
                ],
            ],
            'eyes-wide-open' => [
                'name' => 'Eyes Wide Open', 'artist' => 'TWICE', 'year' => 2020, 'format' => 'CD', 'price' => 175000, 'stock' => 16, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan konsep mata yang membuka perspektif baru.',
                'tracklist' => [
                    ['title' => 'I Got You', 'duration' => '3:20'],
                    ['title' => 'Hell in Heaven', 'duration' => '3:45'],
                    ['title' => 'Turtle', 'duration' => '3:12'],
                    ['title' => 'Rainbow', 'duration' => '3:55'],
                    ['title' => 'Polish', 'duration' => '3:30'],
                ],
            ],
            'formula-of-love' => [
                'name' => 'Formula of Love: O+T=<3', 'artist' => 'TWICE', 'year' => 2021, 'format' => 'CD', 'price' => 190000, 'stock' => 14, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album dengan tema cinta yang manis dan menyentuh hati.',
                'tracklist' => [
                    ['title' => 'Set Me Free', 'duration' => '3:35'],
                    ['title' => 'Last Waltz', 'duration' => '3:22'],
                    ['title' => 'Twicelights', 'duration' => '3:48'],
                    ['title' => 'Stuck in My Head', 'duration' => '3:15'],
                    ['title' => 'Queen of Hearts', 'duration' => '3:50'],
                ],
            ],
            'taste-of-love' => [
                'name' => 'Taste of Love', 'artist' => 'TWICE', 'year' => 2021, 'format' => 'CD', 'price' => 185000, 'stock' => 13, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album summer dengan flavor cinta yang segar dan energik.',
                'tracklist' => [
                    ['title' => 'Alcohol-Free', 'duration' => '3:08'],
                    ['title' => 'Conversation 1', 'duration' => '3:22'],
                    ['title' => 'Ketchup', 'duration' => '3:40'],
                    ['title' => 'Strawberry', 'duration' => '3:35'],
                    ['title' => 'Play it Cool', 'duration' => '3:15'],
                ],
            ],
            'set-me-free' => [
                'name' => 'Set Me Free', 'artist' => 'TWICE', 'year' => 2023, 'format' => 'CD', 'price' => 195000, 'stock' => 12, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan tema pembebasan diri dan mencari jati diri.',
                'tracklist' => [
                    ['title' => 'Set Me Free', 'duration' => '3:28'],
                    ['title' => 'Push and Pull', 'duration' => '3:45'],
                    ['title' => 'Break My Heart Again', 'duration' => '3:32'],
                    ['title' => 'Trouble', 'duration' => '3:20'],
                    ['title' => 'One Spark', 'duration' => '3:50'],
                ],
            ],
            'freeze' => [
                'name' => 'Freeze', 'artist' => 'Stray Kids', 'year' => 2023, 'format' => 'CD', 'price' => 185000, 'stock' => 10, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan konsep freeze time dan refleksi diri.',
                'tracklist' => [
                    ['title' => 'Freeze', 'duration' => '3:40'],
                    ['title' => 'S-Class', 'duration' => '3:25'],
                    ['title' => 'Scars', 'duration' => '3:35'],
                    ['title' => 'Slump', 'duration' => '3:20'],
                    ['title' => 'Space', 'duration' => '3:55'],
                ],
            ],
            'noeasy' => [
                'name' => 'Noeasy', 'artist' => 'Stray Kids', 'year' => 2021, 'format' => 'CD', 'price' => 195000, 'stock' => 9, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album dengan pesan bahwa tidak ada yang mudah dalam hidup.',
                'tracklist' => [
                    ['title' => "God's Menu", 'duration' => '2:59'],
                    ['title' => 'Cheese', 'duration' => '3:08'],
                    ['title' => 'Tortoise and the Hare', 'duration' => '3:35'],
                    ['title' => 'Road not taken', 'duration' => '3:42'],
                    ['title' => 'Blueprint', 'duration' => '3:25'],
                ],
            ],
            'go-live' => [
                'name' => 'GO生 (GO Live)', 'artist' => 'Stray Kids', 'year' => 2020, 'format' => 'CD', 'price' => 180000, 'stock' => 8, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album debut Stray Kids dengan energi yang membara.',
                'tracklist' => [
                    ['title' => 'Go Live', 'duration' => '3:02'],
                    ['title' => 'Wow', 'duration' => '3:15'],
                    ['title' => 'Victory Song', 'duration' => '3:40'],
                    ['title' => 'Voices', 'duration' => '3:28'],
                    ['title' => "God's Menu", 'duration' => '3:12'],
                ],
            ],
            'ate' => [
                'name' => 'ATE', 'artist' => 'Stray Kids', 'year' => 2024, 'format' => 'CD', 'price' => 200000, 'stock' => 11, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album terbaru dengan konsep matang dan sophisticated.',
                'tracklist' => [
                    ['title' => 'ATE', 'duration' => '3:32'],
                    ['title' => 'Racha', 'duration' => '3:18'],
                    ['title' => 'Requiem', 'duration' => '3:50'],
                    ['title' => 'Blind Spot', 'duration' => '3:25'],
                    ['title' => 'UNFORGIVEN', 'duration' => '3:40'],
                ],
            ],
            'emote' => [
                'name' => 'Emote', 'artist' => 'IVE', 'year' => 2023, 'format' => 'CD', 'price' => 190000, 'stock' => 10, 'label' => 'Starship Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan tema emosi yang mendalam dan ekspresif.',
                'tracklist' => [
                    ['title' => 'Emote', 'duration' => '3:25'],
                    ['title' => 'I AM', 'duration' => '3:45'],
                    ['title' => 'Kitsch', 'duration' => '3:18'],
                    ['title' => 'Either Way', 'duration' => '3:32'],
                    ['title' => 'Royal', 'duration' => '3:50'],
                ],
            ],
            'ive' => [
                'name' => "I've", 'artist' => 'IVE', 'year' => 2022, 'format' => 'CD', 'price' => 175000, 'stock' => 9, 'label' => 'Starship Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => "Album pertama dengan title track 'I AM' yang powerful.",
                'tracklist' => [
                    ['title' => 'I AM', 'duration' => '3:22'],
                    ['title' => 'Either Way', 'duration' => '3:35'],
                    ['title' => 'Kitsch', 'duration' => '3:18'],
                    ['title' => 'Indie', 'duration' => '3:42'],
                    ['title' => 'Takers', 'duration' => '3:28'],
                ],
            ],
            'new-jeans' => [
                'name' => 'New Jeans', 'artist' => 'NewJeans', 'year' => 2022, 'format' => 'CD', 'price' => 170000, 'stock' => 17, 'label' => 'ADOR', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album debut dengan vibe Y2K yang fresh dan modern.',
                'tracklist' => [
                    ['title' => 'New Jeans', 'duration' => '3:28'],
                    ['title' => 'Attention, Please!', 'duration' => '3:12'],
                    ['title' => 'Cookie', 'duration' => '3:08'],
                    ['title' => 'Hurt', 'duration' => '3:42'],
                    ['title' => 'Hype Boy', 'duration' => '3:25'],
                ],
            ],
            'supernova' => [
                'name' => 'SuperNova', 'artist' => 'NewJeans', 'year' => 2023, 'format' => 'CD', 'price' => 185000, 'stock' => 14, 'label' => 'ADOR', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan tema bintang dan cahaya yang membara.',
                'tracklist' => [
                    ['title' => 'Super Shy', 'duration' => '3:15'],
                    ['title' => 'Cool With You', 'duration' => '3:38'],
                    ['title' => 'Supernatural', 'duration' => '3:42'],
                    ['title' => 'After LOVE', 'duration' => '3:25'],
                    ['title' => 'Smooth Like Butter', 'duration' => '3:20'],
                ],
            ],
            'cookie' => [
                'name' => 'Cookie', 'artist' => 'NewJeans', 'year' => 2023, 'format' => 'CD', 'price' => 180000, 'stock' => 12, 'label' => 'ADOR', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album EP dengan konsep manis seperti cookies.',
                'tracklist' => [
                    ['title' => 'Cookie', 'duration' => '3:08'],
                    ['title' => 'Biscuit', 'duration' => '3:25'],
                    ['title' => 'DoubleSided Tape', 'duration' => '3:35'],
                    ['title' => 'Ditto', 'duration' => '3:12'],
                ],
            ],
            'attention-please' => [
                'name' => 'Attention, Please!', 'artist' => 'NewJeans', 'year' => 2024, 'format' => 'CD', 'price' => 195000, 'stock' => 13, 'label' => 'ADOR', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album terbaru dengan pesan perhatian yang kuat.',
                'tracklist' => [
                    ['title' => 'Attention, Please!', 'duration' => '3:28'],
                    ['title' => 'Right Now', 'duration' => '3:15'],
                    ['title' => 'Hello, Life', 'duration' => '3:40'],
                    ['title' => 'ETA', 'duration' => '3:32'],
                    ['title' => 'Sudden Shower', 'duration' => '3:25'],
                ],
            ],
            'henggarae' => [
                'name' => 'Henggarae', 'artist' => 'SEVENTEEN', 'year' => 2021, 'format' => 'Vinyl 12"', 'price' => 380000, 'stock' => 5, 'label' => 'Pledis Entertainment', 'condition' => 'Mint', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album legendaris SEVENTEEN dengan sound yang konsisten.',
                'tracklist' => [
                    ['title' => 'God of Music', 'duration' => '3:52'],
                    ['title' => 'Fearless', 'duration' => '3:18'],
                    ['title' => 'Cheers', 'duration' => '3:35'],
                    ['title' => 'Unnie, Dance More', 'duration' => '3:22'],
                    ['title' => 'My My', 'duration' => '3:48'],
                ],
            ],
            'face-the-sun' => [
                'name' => 'Face the Sun', 'artist' => 'SEVENTEEN', 'year' => 2022, 'format' => 'CD', 'price' => 200000, 'stock' => 11, 'label' => 'Pledis Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan tema menghadapi matahari dan kehidupan.',
                'tracklist' => [
                    ['title' => 'God of Music', 'duration' => '3:52'],
                    ['title' => 'Rock with you', 'duration' => '3:25'],
                    ['title' => 'Cheers', 'duration' => '3:35'],
                    ['title' => 'My My', 'duration' => '3:48'],
                    ['title' => 'Hot', 'duration' => '3:15'],
                ],
            ],
            'attacca' => [
                'name' => 'Attacca', 'artist' => 'SEVENTEEN', 'year' => 2021, 'format' => 'CD', 'price' => 185000, 'stock' => 10, 'label' => 'Pledis Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan konsep attacca (terus menerus tanpa henti).',
                'tracklist' => [
                    ['title' => 'God of Music', 'duration' => '3:52'],
                    ['title' => 'Fearless', 'duration' => '3:18'],
                    ['title' => 'Fair & Square', 'duration' => '3:40'],
                    ['title' => 'Crush', 'duration' => '3:35'],
                    ['title' => 'Darl+ing', 'duration' => '3:28'],
                ],
            ],
            'god-of-music' => [
                'name' => "God's Menu", 'artist' => 'aespa', 'year' => 2022, 'format' => 'CD', 'price' => 165000, 'stock' => 15, 'label' => 'SM Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album dengan tema menu Tuhan yang mystical dan futuristik.',
                'tracklist' => [
                    ['title' => "God's Menu", 'duration' => '3:09'],
                    ['title' => 'Savage', 'duration' => '3:18'],
                    ['title' => 'Spicy', 'duration' => '3:32'],
                    ['title' => 'Dreams Come True', 'duration' => '3:42'],
                    ['title' => "Life's Too Short", 'duration' => '3:25'],
                ],
            ],
            'savage' => [
                'name' => 'Savage', 'artist' => 'aespa', 'year' => 2022, 'format' => 'CD', 'price' => 170000, 'stock' => 14, 'label' => 'SM Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album dengan konsep buas dan primitif namun sophisticated.',
                'tracklist' => [
                    ['title' => 'Savage', 'duration' => '3:18'],
                    ['title' => 'Spicy', 'duration' => '3:32'],
                    ['title' => 'Armageddon', 'duration' => '3:45'],
                    ['title' => 'Dreams Come True', 'duration' => '3:42'],
                    ['title' => 'Iconic', 'duration' => '3:20'],
                ],
            ],
            'spicy' => [
                'name' => 'Spicy', 'artist' => 'aespa', 'year' => 2023, 'format' => 'CD', 'price' => 175000, 'stock' => 13, 'label' => 'SM Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan flavor yang pedas dan menggugah.',
                'tracklist' => [
                    ['title' => 'Spicy', 'duration' => '3:32'],
                    ['title' => 'Dreams Come True', 'duration' => '3:42'],
                    ['title' => "Life's Too Short", 'duration' => '3:25'],
                    ['title' => 'Hot & Heavy', 'duration' => '3:40'],
                    ['title' => 'Illusion', 'duration' => '3:35'],
                ],
            ],
            'armageddon' => [
                'name' => 'Armageddon', 'artist' => 'aespa', 'year' => 2023, 'format' => 'CD', 'price' => 190000, 'stock' => 12, 'label' => 'SM Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan tema apocalypse yang dramatis dan mengesankan.',
                'tracklist' => [
                    ['title' => 'Armageddon', 'duration' => '3:45'],
                    ['title' => 'Dreams Come True', 'duration' => '3:42'],
                    ['title' => "Life's Too Short", 'duration' => '3:25'],
                    ['title' => 'Loco', 'duration' => '3:18'],
                    ['title' => 'Enemy', 'duration' => '3:40'],
                ],
            ],
            'drama' => [
                'name' => 'Drama', 'artist' => 'aespa', 'year' => 2024, 'format' => 'CD', 'price' => 185000, 'stock' => 11, 'label' => 'SM Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album terbaru dengan tema drama dan teatrikal.',
                'tracklist' => [
                    ['title' => 'Drama', 'duration' => '3:35'],
                    ['title' => 'Supernova', 'duration' => '3:50'],
                    ['title' => 'Phantom', 'duration' => '3:28'],
                    ['title' => 'Retro, Futurism', 'duration' => '3:42'],
                    ['title' => 'Savage', 'duration' => '3:18'],
                ],
            ],
            'fearless' => [
                'name' => 'Fearless', 'artist' => 'LE SSERAFIM', 'year' => 2022, 'format' => 'CD', 'price' => 180000, 'stock' => 9, 'label' => 'Source Music / HYBE', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album debut dengan tema ketangguhan dan keberanian.',
                'tracklist' => [
                    ['title' => 'Good Bones', 'duration' => '3:28'],
                    ['title' => 'Fear', 'duration' => '3:15'],
                    ['title' => 'Sour Grapes', 'duration' => '3:42'],
                    ['title' => 'Betrayed Heart', 'duration' => '3:35'],
                    ['title' => 'Peaceful', 'duration' => '3:50'],
                ],
            ],
            'antifragile' => [
                'name' => 'Antifragile', 'artist' => 'LE SSERAFIM', 'year' => 2023, 'format' => 'CD', 'price' => 195000, 'stock' => 8, 'label' => 'Source Music / HYBE', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan tema ketahanan dan adaptasi yang kuat.',
                'tracklist' => [
                    ['title' => 'Good Bones', 'duration' => '3:28'],
                    ['title' => 'Antifragile', 'duration' => '3:52'],
                    ['title' => 'Crazy', 'duration' => '3:25'],
                    ['title' => 'Sorrow', 'duration' => '3:40'],
                    ['title' => 'Renaissance', 'duration' => '3:48'],
                ],
            ],
            'eve-psyche' => [
                'name' => "Eve, Psyche & The Bluebeard's Wife", 'artist' => 'LE SSERAFIM', 'year' => 2024, 'format' => 'CD', 'price' => 205000, 'stock' => 10, 'label' => 'Source Music / HYBE', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album dengan narasi kompleks dan mythologi yang dalam.',
                'tracklist' => [
                    ['title' => 'Impurities', 'duration' => '3:35'],
                    ['title' => 'Perfect Night', 'duration' => '3:22'],
                    ['title' => 'Shy Shy Shy', 'duration' => '3:45'],
                    ['title' => 'Happy', 'duration' => '3:18'],
                    ['title' => 'Paradise', 'duration' => '3:50'],
                ],
            ],
            'crazy-in-love' => [
                'name' => 'Crazy in Love', 'artist' => 'ITZY', 'year' => 2022, 'format' => 'CD', 'price' => 175000, 'stock' => 11, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-d', 'cover-a', 'cover-b', 'cover-c'],
                'description' => 'Album dengan tema cinta yang gila dan passionate.',
                'tracklist' => [
                    ['title' => 'Wannabe', 'duration' => '3:15'],
                    ['title' => 'Wild Flower', 'duration' => '3:42'],
                    ['title' => 'King Kong', 'duration' => '3:28'],
                    ['title' => 'Shady', 'duration' => '3:35'],
                    ['title' => 'D.D.', 'duration' => '3:22'],
                ],
            ],
            'checkmate' => [
                'name' => 'Checkmate', 'artist' => 'ITZY', 'year' => 2023, 'format' => 'CD', 'price' => 185000, 'stock' => 10, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-a', 'cover-b', 'cover-c', 'cover-d'],
                'description' => 'Album dengan strategi musik yang matang seperti permainan catur.',
                'tracklist' => [
                    ['title' => 'CHECKMATE', 'duration' => '3:25'],
                    ['title' => 'None of My Business', 'duration' => '3:18'],
                    ['title' => 'None of My Business (English Ver.)', 'duration' => '3:20'],
                    ['title' => 'Tung Tung', 'duration' => '3:32'],
                    ['title' => 'Swipe', 'duration' => '3:40'],
                ],
            ],
            'gold' => [
                'name' => 'Gold', 'artist' => 'ITZY', 'year' => 2023, 'format' => 'CD', 'price' => 180000, 'stock' => 9, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-b', 'cover-c', 'cover-d', 'cover-a'],
                'description' => 'Album dengan nilai dan kualitas yang emas murni.',
                'tracklist' => [
                    ['title' => 'Gold', 'duration' => '3:30'],
                    ['title' => 'Loco', 'duration' => '3:42'],
                    ['title' => 'Crown', 'duration' => '3:28'],
                    ['title' => 'Silver Lining', 'duration' => '3:35'],
                    ['title' => 'Treasure', 'duration' => '3:48'],
                ],
            ],
            'untouchable' => [
                'name' => 'Untouchable', 'artist' => 'ITZY', 'year' => 2024, 'format' => 'CD', 'price' => 195000, 'stock' => 12, 'label' => 'JYP Entertainment', 'condition' => 'New', 'genre' => 'K-Pop', 'covers' => ['cover-c', 'cover-d', 'cover-a', 'cover-b'],
                'description' => 'Album terbaru dengan tema yang tak terjangkau dan eksklusif.',
                'tracklist' => [
                    ['title' => 'UNTOUCHABLE', 'duration' => '3:35'],
                    ['title' => 'Nobody Like You', 'duration' => '3:22'],
                    ['title' => 'Blame It On Love', 'duration' => '3:48'],
                    ['title' => 'None of My Business', 'duration' => '3:18'],
                    ['title' => 'Crown', 'duration' => '3:40'],
                ],
            ],
        ];
    }
}
