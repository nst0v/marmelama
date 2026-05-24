@extends('layouts.site')

@section('title', 'Новости - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container"><p class="eyebrow">Новости</p><h1>Новости питомника</h1><p class="lead">События, выставки и заметки МарМелАма.</p></div>
</section>
<section class="section section-tight">
    <div class="container grid grid-3">
        @foreach($posts as $post)
            @php($imageUrl = \App\Support\MediaUrl::url($post->image))
            <article class="card news-card">
                @if($imageUrl)<a class="card-media" href="{{ route('news.show', $post->slug) }}"><img src="{{ $imageUrl }}" alt="{{ $post->title }}"></a>@endif
                <div class="card-body">
                    @if($post->published_at)<time class="eyebrow">{{ $post->published_at->format('d.m.Y') }}</time>@endif
                    <h3><a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a></h3>
                    @if($post->excerpt)<p>{!! \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 160) !!}</p>@endif
                    <a class="button secondary full" href="{{ route('news.show', $post->slug) }}">Читать</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
