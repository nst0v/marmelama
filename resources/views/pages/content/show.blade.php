@extends('layouts.site')

@section('title', ($page->meta_title ?: $page->title).' - МарМелАма')
@section('description', $page->meta_description ?: 'Страница питомника МарМелАма.')

@section('content')
@php
    $pageTitle = $page->h1 ?: $page->title;
    $pageContent = \App\Support\RichText::forPage($page->content, $pageTitle);
@endphp

<section class="page-hero page-hero--simple">
    <div class="container page-heading">
        <h1>{{ $pageTitle }}</h1>
    </div>
</section>

<section class="section content-section content-section--before-cta">
    <div class="container content-page-layout">
        <article class="content-prose rich-text card">
            {!! $pageContent ?: '<p class="empty-copy">Страница будет дополнена.</p>' !!}
        </article>
    </div>
</section>

@include('partials.cta')
@endsection
