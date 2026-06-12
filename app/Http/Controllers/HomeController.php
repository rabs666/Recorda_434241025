<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $featured = Product::where('is_active', true)
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $articles = Article::where('status', 'publish')
            ->orderByDesc('published_at')
            ->take(2)
            ->get();

        return view('pages.home', compact('featured', 'articles'));
    }

    public function catalog(Request $request)
    {
        $query = Product::where('is_active', true);

        // Pencarian nama album / artis.
        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('artist', 'like', "%{$search}%");
            });
        }

        // Filter Kategori (format/badge). Bisa pilih lebih dari satu (checkbox).
        $categories = (array) $request->input('kategori', []);
        if ($categories) {
            $query->where(function ($q) use ($categories) {
                foreach ($categories as $cat) {
                    $cat = strtolower($cat);
                    if ($cat === 'vinyl') {
                        $q->orWhere('format', 'like', 'Vinyl%');
                    } elseif ($cat === 'cd') {
                        $q->orWhere('format', 'CD');
                    } elseif ($cat === 'kaset') {
                        $q->orWhere('format', 'Kaset');
                    } elseif ($cat === 'limited') {
                        $q->orWhere('badge', 'Limited')->orWhere('badge', 'Collector');
                    }
                }
            });
        }

        // Filter Genre (boleh banyak).
        $genres = array_filter((array) $request->input('genre', []));
        if ($genres) {
            $query->whereIn('genre', $genres);
        }

        // Filter rentang harga.
        if (is_numeric($request->input('min'))) {
            $query->where('price', '>=', (int) $request->input('min'));
        }
        if (is_numeric($request->input('max'))) {
            $query->where('price', '<=', (int) $request->input('max'));
        }

        // Urutan.
        switch ($request->input('sort')) {
            case 'termurah':
                $query->orderBy('price'); break;
            case 'termahal':
                $query->orderByDesc('price'); break;
            case 'nama':
                $query->orderBy('name'); break;
            default: // terbaru
                $query->orderByDesc('id'); break;
        }

        $products = $query->get();

        // Daftar genre unik untuk membangun filter di sidebar.
        $genreOptions = Product::where('is_active', true)
            ->whereNotNull('genre')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        return view('pages.catalog', compact('products', 'genreOptions'));
    }
}
