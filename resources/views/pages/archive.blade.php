@extends('layouts.recorda')

@section('title', 'Recorda - Arsip Artikel')
@section('body_class', 'page-archive')

@section('content')
<section class="archive-head reveal">
    <div>
        <p class="eyebrow">Arsip artikel</p>
        <h1>Review, tips, dan cerita rilis baru.</h1>
        <p class="lead">Cari artikel berdasarkan topik, lalu simpan sebagai referensi.</p>
    </div>
    <div class="search-field">
        <span class="search-icon"></span>
        <input class="input" type="search" placeholder="Cari artikel..." data-search>
    </div>
</section>

<section class="filter-bar reveal">
    <button class="filter-button is-active" type="button" data-filter="all">Semua</button>
    <button class="filter-button" type="button" data-filter="review">Review</button>
    <button class="filter-button" type="button" data-filter="tips">Tips</button>
    <button class="filter-button" type="button" data-filter="rilis">Rilis</button>
    <button class="filter-button" type="button" data-filter="sejarah">Sejarah</button>
</section>

<section class="archive-list">
    @forelse($articles as $article)
    <article class="article-row reveal" data-tags="{{ strtolower($article->category) }}" data-article-key="{{ $article->slug }}">
        <div class="row-thumb {{ $article->cover }}" @if($article->imageUrl()) style="background-image:url('{{ $article->imageUrl() }}'); background-size:cover; background-position:center;" @endif></div>
        <div class="row-body">
            <p class="meta">{{ $article->author }} - {{ $article->displayDate() }} - {{ $article->category }}</p>
            <h3 data-search-text>{{ $article->title }}</h3>
            <p class="muted">{{ $article->excerpt }}</p>
            <a class="text-link" href="{{ route('recorda.article', $article->slug) }}">Baca detail</a>
        </div>
    </article>
    @empty
    <p class="muted">Belum ada artikel.</p>
    @endforelse
</section>
@endsection
