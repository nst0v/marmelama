<article class="review-card card">
    <div class="card-body">
        <div class="review-head">
            <h3>{{ $review->author_name }}</h3>
            @if($review->reviewed_at)<time>{{ $review->reviewed_at->format('d.m.Y') }}</time>@endif
        </div>
        <p>{!! \Illuminate\Support\Str::limit(strip_tags($review->body), 260) !!}</p>
        @if($review->response)
            <div class="owner-response">Ответ питомника: {!! \Illuminate\Support\Str::limit(strip_tags($review->response), 120) !!}</div>
        @endif
    </div>
</article>
