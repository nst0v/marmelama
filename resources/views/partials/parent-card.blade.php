@php
    $image = is_array($parent->images) ? ($parent->images[0] ?? null) : null;
    $imageUrl = \App\Support\MediaUrl::url($image);
    $sexRoute = $parent->sex === 'male' ? '1' : '0';
@endphp

<article class="parent-card card">
    <a class="card-media" href="{{ route('parents.show', [$sexRoute, $parent->slug]) }}">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $parent->name }}">
        @else
            <span class="image-placeholder">МарМелАма</span>
        @endif
    </a>
    <div class="card-body">
        <p class="eyebrow">{{ $parent->sex === 'male' ? 'Кот' : 'Кошка' }}</p>
        <h3><a href="{{ route('parents.show', [$sexRoute, $parent->slug]) }}">{{ $parent->name }}</a></h3>
        <dl class="meta-list">
            @if($parent->title)<div><dt>Титул</dt><dd>{{ $parent->title }}</dd></div>@endif
            @if($parent->color)<div><dt>Окрас</dt><dd>{{ $parent->color }}</dd></div>@endif
        </dl>
        <a class="button secondary full" href="{{ route('parents.show', [$sexRoute, $parent->slug]) }}">Подробнее</a>
    </div>
</article>
