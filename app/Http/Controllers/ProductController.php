<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->genre, fn ($q) => $q->where('genre', $product->genre))
            ->orderByDesc('id')
            ->take(3)
            ->get();

        // Fallback bila genre sama tidak cukup banyak.
        if ($related->count() < 3) {
            $related = Product::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->orderByDesc('id')
                ->take(3)
                ->get();
        }

        $album = $product->toArray();
        $album['image_url'] = $product->imageUrl();
        $album['gallery_urls'] = $product->galleryUrls();

        return view('pages.product', [
            'album' => $album,
            'related' => $related,
        ]);
    }
}
