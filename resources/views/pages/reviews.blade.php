@extends('layouts.site')

@section('title', 'Отзывы владельцев - МарМелАма')

@section('content')
<section class="page-hero page-hero--simple">
    <div class="container page-heading">
        <h1>Отзывы о питомнике</h1>
        <p class="lead">Истории семей, в которые переехали выпускники питомника МарМелАма.</p>
    </div>
</section>

<section class="section content-section content-section--before-cta">
    <div class="container">
        @if($reviews->isNotEmpty())
            <div class="reviews-list">
                @foreach($reviews as $review)
                    @include('partials.review-card', ['review' => $review])
                @endforeach
            </div>
        @else
            <div class="empty-state card">
                <h2>Отзывы пока не опубликованы</h2>
                <p>Новые истории владельцев появятся здесь после публикации.</p>
            </div>
        @endif

        {{ $reviews->links('partials.pagination') }}
    </div>
</section>
@include('partials.cta')
@endsection
