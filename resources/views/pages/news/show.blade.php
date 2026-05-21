@extends('layouts.site')

@section('title', $post->title.' - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container narrow">
        <p class="eyebrow">Новость @if($post->published_at) · {{ $post->published_at->format('d.m.Y') }} @endif</p>
        <h1>{{ $post->title }}</h1>
    </div>
</section>
<section class="section section-tight">
    <div class="container narrow prose-card card">
        @if($post->image)<img class="prose-image" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}">@endif
        {!! $post->content ?: $post->excerpt !!}
    </div>
</section>
@endsection
