@extends('layouts.recorda')

@section('title', 'Recorda - ' . $article->title)
@section('body_class', 'page-article')

@section('content')
<section class="article-hero reveal" data-article-key="{{ $article->slug }}">
    <a class="back-link" href="{{ route('recorda.archive') }}">Kembali ke arsip</a>
    <p class="eyebrow">{{ $article->category }}</p>
    <h1>{{ $article->title }}</h1>
    <p class="meta">{{ $article->author }} - {{ $article->displayDate() }} - {{ $article->category }}</p>
    <div class="hero-image {{ $article->cover }}" @if($article->imageUrl()) style="background-image:url('{{ $article->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
</section>

<section class="article-content reveal" data-article-body>
    @if($article->excerpt)
    <p class="lead">{{ $article->excerpt }}</p>
    @endif
    {!! $article->body !!}
</section>

@if($related->count())
<section class="related reveal">
    <div class="section-head">
        <div>
            <h2>Artikel terkait</h2>
            <p>Rekomendasi bacaan lain dari Recorda.</p>
        </div>
    </div>
    <div class="related-grid">
        @foreach($related as $item)
        <article class="related-card">
            <div class="related-thumb {{ $item->cover }}" @if($item->imageUrl()) style="background-image:url('{{ $item->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
            <div>
                <p class="meta">{{ $item->category }}</p>
                <h3>{{ $item->title }}</h3>
                <a class="text-link" href="{{ route('recorda.article', $item->slug) }}">Baca detail</a>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endif
@endsection
