<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function archive()
    {
        $articles = Article::where('status', 'publish')
            ->orderByDesc('published_at')
            ->get();

        return view('pages.archive', compact('articles'));
    }

    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'publish')
            ->firstOrFail();

        $related = Article::where('status', 'publish')
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->take(2)
            ->get();

        return view('pages.article', compact('article', 'related'));
    }
}
