@extends('layouts.site')

@section('title', ($page->meta_title ?: $page->title).' - МарМелАма')
@section('description', $page->meta_description ?: 'Страница питомника МарМелАма.')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">МарМелАма</p>
        <h1>{{ $page->h1 ?: $page->title }}</h1>
    </div>
</section>
<section class="section section-tight">
    <div class="container narrow prose-card card">
        {!! $page->content ?: '<p>Страница будет дополнена.</p>' !!}
    </div>
</section>
@include('partials.cta')
@endsection
