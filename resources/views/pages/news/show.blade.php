@extends('layouts.site')

@section('title', $post->title.' - МарМелАма')

@section('content')
@php($imageUrl = \App\Support\MediaUrl::url($post->image))
<section class="page-hero">
    <div class="container narrow">
        <p class="eyebrow">Новость @if($post->published_at) · {{ $post->published_at->format('d.m.Y') }} @endif</p>
        <h1>{{ $post->title }}</h1>
    </div>
</section>
<section class="section section-tight">
    <div class="container narrow prose-card card">
        @if($imageUrl)<img class="prose-image" src="{{ $imageUrl }}" alt="{{ $post->title }}">@endif
        {!! $post->content ?: $post->excerpt !!}
    </div>
</section>
@endsection
