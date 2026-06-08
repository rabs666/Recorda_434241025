<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderByDesc('published_at')->get();

        return view('pages.admin-articles', compact('articles'));
    }

    public function create()
    {
        return view('pages.admin-article-form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['image'] = $this->resolveImage($request, null);

        Article::create($data);

        return redirect()->route('recorda.manageArticles')->with('success', 'Artikel ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('pages.admin-article-form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validateData($request);
        $data['image'] = $this->resolveImage($request, $article->image);

        $article->update($data);

        return redirect()->route('recorda.manageArticles')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('recorda.manageArticles')->with('success', 'Artikel dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'cover' => ['required', 'string', 'max:20'],
            'author' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:publish,draft'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function resolveImage(Request $request, ?string $current): ?string
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            return $request->file('image')->store('articles', 'public');
        }

        if ($request->filled('image_url')) {
            return trim($request->input('image_url'));
        }

        return $current;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'artikel';
        $slug = $base;
        $i = 2;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
