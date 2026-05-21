@php
    $image = is_array($kitten->images) ? ($kitten->images[0] ?? null) : null;
    $status = [
        'available' => ['Свободен', 'available'],
        'reserved' => ['Бронь', 'reserved'],
        'sold' => ['Продан', 'sold'],
    ][$kitten->status] ?? ['Статус уточняется', 'sold'];
    $sex = ['male' => 'Мальчик', 'female' => 'Девочка', 'unknown' => null][$kitten->sex] ?? null;
@endphp

<article class="kitten-card card">
    <a class="card-media" href="{{ route('kittens.show', $kitten->slug) }}">
        @if($image)
            <img src="{{ asset('storage/'.$image) }}" alt="{{ $kitten->image_alt ?: $kitten->name }}">
        @else
            <span class="image-placeholder">МарМелАма</span>
        @endif
        <span class="status {{ $status[1] }}">{{ $status[0] }}</span>
    </a>
    <div class="card-body">
        <h3><a href="{{ route('kittens.show', $kitten->slug) }}">{{ $kitten->name }}</a></h3>
        <dl class="meta-list">
            @if($sex)<div><dt>Пол</dt><dd>{{ $sex }}</dd></div>@endif
            @if($kitten->color)<div><dt>Окрас</dt><dd>{{ $kitten->color }}</dd></div>@endif
            @if($kitten->litter)<div><dt>Помет</dt><dd>{{ $kitten->litter->letter ?: $kitten->litter->title }}</dd></div>@endif
            @if($kitten->born_on)<div><dt>Дата рождения</dt><dd>{{ $kitten->born_on->format('d.m.Y') }}</dd></div>@endif
        </dl>
        @if($kitten->description)
            <div class="card-text">{!! \Illuminate\Support\Str::limit(strip_tags($kitten->description), 120) !!}</div>
        @endif
        <div class="card-actions">
            <a class="button secondary full" href="{{ route('kittens.show', $kitten->slug) }}">Подробнее</a>
            <a class="button full" href="{{ route('contacts') }}?kitten={{ urlencode($kitten->name) }}#contact-form">Узнать цену</a>
        </div>
    </div>
</article>
