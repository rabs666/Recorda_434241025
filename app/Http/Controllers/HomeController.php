<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

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

    public function catalog()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.catalog', compact('products'));
    }
}
