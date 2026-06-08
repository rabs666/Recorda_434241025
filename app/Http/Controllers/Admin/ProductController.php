<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();

        $stats = [
            'total' => $products->count(),
            'habis' => $products->where('stock', '<=', 0)->count(),
            'rendah' => $products->filter(fn ($p) => $p->stock > 0 && $p->stock <= 5)->count(),
        ];

        return view('pages.admin-products', compact('products', 'stats'));
    }

    public function create()
    {
        return view('pages.admin-product-form', ['product' => new Product()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['covers'] = $this->coversFrom($data['cover']);
        $data['tracklist'] = $this->parseTracklist($request->input('tracklist_raw'));
        $data['image'] = $this->resolveImage($request, null);
        $data['gallery'] = $this->resolveGallery($request, null);

        Product::create($data);

        return redirect()->route('recorda.manageProducts')->with('success', 'Produk ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('pages.admin-product-form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $data['covers'] = $this->coversFrom($data['cover']);
        $data['tracklist'] = $this->parseTracklist($request->input('tracklist_raw'));
        $data['image'] = $this->resolveImage($request, $product->image);
        $data['gallery'] = $this->resolveGallery($request, $product->gallery);

        $product->update($data);

        return redirect()->route('recorda.manageProducts')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('recorda.manageProducts')->with('success', 'Produk dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'artist' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'format' => ['required', 'string', 'max:50'],
            'genre' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'label' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'cover' => ['required', 'string', 'max:20'],
            'badge' => ['nullable', 'string', 'max:50'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    /**
     * Tentukan nilai kolom image dari upload file atau URL; jika keduanya kosong,
     * pertahankan gambar lama ($current).
     */
    private function resolveImage(Request $request, ?string $current): ?string
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            return $request->file('image')->store('products', 'public');
        }

        if ($request->filled('image_url')) {
            return trim($request->input('image_url'));
        }

        return $current;
    }

    /**
     * Galeri foto detail: gabungan file upload (gallery_files[]) dan URL (gallery_urls, satu per baris).
     * Jika keduanya kosong, pertahankan galeri lama ($current).
     */
    private function resolveGallery(Request $request, ?array $current): ?array
    {
        $request->validate([
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'max:4096'],
            'gallery_urls' => ['nullable', 'string'],
        ]);

        $items = [];

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $items[] = $file->store('products', 'public');
            }
        }

        if ($request->filled('gallery_urls')) {
            foreach (preg_split('/\r\n|\r|\n/', $request->input('gallery_urls')) as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $items[] = $line;
                }
            }
        }

        return count($items) > 0 ? $items : $current;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $i = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function coversFrom(string $cover): array
    {
        $all = ['cover-a', 'cover-b', 'cover-c', 'cover-d'];
        $rest = array_values(array_diff($all, [$cover]));

        return array_merge([$cover], $rest);
    }

    private function parseTracklist(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        $tracks = [];

        foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Format: "Judul | 3:20" atau hanya "Judul".
            $parts = array_map('trim', explode('|', $line, 2));
            $tracks[] = [
                'title' => $parts[0],
                'duration' => $parts[1] ?? '',
            ];
        }

        return $tracks;
    }
}
