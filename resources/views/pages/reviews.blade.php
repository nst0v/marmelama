@extends('layouts.site')

@section('title', 'Отзывы владельцев - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Отзывы</p>
        <h1>Отзывы владельцев</h1>
        <p class="lead">Истории семей, в которые переехали выпускники питомника МарМелАма.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container grid grid-3">
        @foreach($reviews as $review)
            @include('partials.review-card', ['review' => $review])
        @endforeach
    </div>
</section>
@include('partials.cta')
@endsection
