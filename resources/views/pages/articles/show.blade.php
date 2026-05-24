@extends('layouts.site')

@section('title', ($article->h1 ?: $article->title).' - МарМелАма')
@section('description', $article->meta_description)

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <p class="eyebrow">{{ $article->category?->title ?: 'Статья' }}</p>
        <h1>{{ $article->h1 ?: $article->title }}</h1>
        @if($article->published_at)
            <p class="lead">{{ $article->published_at->format('d.m.Y') }}</p>
        @endif
    </div>
</section>

<section class="section section-tight">
    <div class="container narrow prose-card card">
        @php($imageUrl = \App\Support\MediaUrl::url($article->image))
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $article->title }}">
        @endif

        @if($article->content)
            {!! $article->content !!}
        @else
            {!! $article->excerpt !!}
        @endif
    </div>
</section>
@endsection
