@php
    $compact = $compact ?? false;
    $imageUrl = \App\Support\MediaUrl::url($review->image);
    $body = \App\Support\RichText::multiline($review->body);
    $response = \App\Support\RichText::multiline($review->response);
@endphp

<article class="review-card listing-card card{{ $compact ? ' is-compact' : '' }}">
    @if($imageUrl)
        <div class="review-media">
            <img src="{{ $imageUrl }}" alt="Фотография к отзыву {{ $review->author_name }}" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="card-body">
        <div class="review-head">
            <h2 class="card-title">{{ $review->author_name }}</h2>
            @if($review->reviewed_at)<time datetime="{{ $review->reviewed_at->toDateString() }}">{{ $review->reviewed_at->format('d.m.Y') }}</time>@endif
        </div>

        <blockquote class="review-text">{!! nl2br(e($compact ? \Illuminate\Support\Str::limit($body, 280) : $body)) !!}</blockquote>

        @if($response)
            <div class="owner-response">
                <strong>Ответ питомника</strong>
                <p>{!! nl2br(e($compact ? \Illuminate\Support\Str::limit($response, 140) : $response)) !!}</p>
            </div>
        @endif
    </div>
</article>
