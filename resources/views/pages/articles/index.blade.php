@extends('layouts.site')

@section('title', 'Статьи - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Полезное</p>
        <h1>Статьи</h1>
        <p class="lead">Материалы питомника о бурманских кошках, котятах, уходе и переезде в новый дом.</p>
    </div>
</section>

<section class="section section-tight">
    <div class="container">
        @if($categories->isNotEmpty())
            <div class="badge-row">
                @foreach($categories as $category)
                    <span class="badge">{{ $category->title }} · {{ $category->articles_count }}</span>
                @endforeach
            </div>
        @endif

        <div class="grid grid-3">
            @forelse($articles as $article)
                @php($imageUrl = \App\Support\MediaUrl::url($article->image))
                <article class="card">
                    @if($imageUrl)
                        <a class="card-media" href="{{ route('articles.show', $article->slug) }}">
                            <img src="{{ $imageUrl }}" alt="{{ $article->title }}">
                        </a>
                    @endif
                    <div class="card-body">
                        <p class="eyebrow">{{ $article->published_at?->format('d.m.Y') ?: $article->category?->title }}</p>
                        <h2>{{ $article->title }}</h2>
                        @if($article->excerpt)
                            <p class="card-text">{!! strip_tags($article->excerpt) !!}</p>
                        @endif
                        <a class="button secondary" href="{{ route('articles.show', $article->slug) }}">Читать статью</a>
                    </div>
                </article>
            @empty
                <div class="empty-state card">Статьи пока не добавлены.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
