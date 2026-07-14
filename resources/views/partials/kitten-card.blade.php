@php
    $image = is_array($kitten->images) ? ($kitten->images[0] ?? null) : null;
    $imageUrl = \App\Support\MediaUrl::url($image);
    $status = [
        'available' => ['Ищет семью', 'available'],
        'reserved' => ['Забронирован', 'reserved'],
        'sold' => ['Уже нашли хозяев', 'sold'],
    ][$kitten->status] ?? ['Статус уточняется', 'sold'];
    $displayName = $kitten->display_name;
    $litterName = $kitten->litter?->is_visible
        ? ($kitten->litter->letter ? 'Помёт «'.$kitten->litter->letter.'»' : $kitten->litter->title)
        : null;
    $birthLabel = $kitten->born_on
        ? ($kitten->sex === 'female' ? 'Родилась ' : ($kitten->sex === 'male' ? 'Родился ' : 'Дата рождения ')).$kitten->born_on->isoFormat('D MMMM YYYY')
        : null;
@endphp

<article class="kitten-card">
    <a class="kitten-card-media" href="{{ route('kittens.show', $kitten->slug) }}" aria-label="Открыть анкету котёнка {{ $displayName }}">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $kitten->image_alt ?: $displayName }}" @if($kitten->image_title) title="{{ $kitten->image_title }}" @endif loading="{{ ($eager ?? false) ? 'eager' : 'lazy' }}" @if($eager ?? false) fetchpriority="high" @endif decoding="async">
        @else
            <span class="kitten-card-placeholder">МарМелАма</span>
        @endif

        <span class="kitten-card-status {{ $status[1] }}">{{ $status[0] }}</span>
    </a>

    <div class="kitten-card-content">
        <header class="kitten-card-heading">
            <h2><a href="{{ route('kittens.show', $kitten->slug) }}">{{ $displayName }}</a></h2>
            @include('partials.kitten-attributes', ['kitten' => $kitten, 'attributesClass' => 'kitten-card-attributes'])
        </header>

        @if($birthLabel || $litterName)
            <p class="kitten-card-details">
                @if($birthLabel)<span>{{ $birthLabel }}</span>@endif
                @if($litterName)<span>{{ $litterName }}</span>@endif
            </p>
        @endif

        <footer class="kitten-card-footer">
            <a class="kitten-card-action" href="{{ route('kittens.show', $kitten->slug) }}">
                Смотреть анкету
                <span aria-hidden="true">→</span>
            </a>
        </footer>
    </div>
</article>
