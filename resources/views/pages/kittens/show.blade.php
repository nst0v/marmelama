@extends('layouts.site')

@section('title', ($kitten->meta_title ?: $kitten->display_name.' — котёнок МарМелАма'))

@section('content')
@php
    $images = collect(is_array($kitten->images) ? $kitten->images : [])
        ->map(fn (string $image) => \App\Support\MediaUrl::url($image))
        ->filter()
        ->values();
    $status = [
        'available' => ['Ищет семью', 'available'],
        'reserved' => ['Забронирован', 'reserved'],
        'sold' => ['Уже нашли хозяев', 'sold'],
    ][$kitten->status] ?? ['Статус уточняется', 'sold'];
    $displayName = $kitten->display_name;
    $publicLitter = $kitten->litter?->is_visible ? $kitten->litter : null;
    $sex = ['male' => 'Мальчик', 'female' => 'Девочка', 'unknown' => null][$kitten->sex] ?? null;
    $price = $kitten->price && (float) $kitten->price > 0
        ? number_format((float) $kitten->price, 0, ',', ' ').' ₽'
        : null;
    $contactUrl = route('contacts').'?kitten='.urlencode($displayName).'#contact-form';
    $offer = match ($kitten->status) {
        'available' => ['Стоимость', $price ?: 'По запросу', 'Расскажем об условиях покупки, характере котёнка и вариантах доставки.'],
        'reserved' => ['Статус', 'Котёнок забронирован', 'Можно уточнить статус брони или узнать о похожих свободных котятах.'],
        'sold' => ['Статус', 'Уже нашли хозяев', 'Познакомьтесь с другими котятами или спросите о ближайших помётах.'],
        default => ['Статус', 'Уточняется', 'Напишите нам, чтобы получить актуальную информацию.'],
    };
    $storySource = $kitten->content ?: $kitten->description;
    $storyHtml = $storySource
        ? (str_contains($storySource, '<')
            ? \App\Support\RichText::forPage($storySource, $displayName, true)
            : '<p>'.e($storySource).'</p>')
        : null;
@endphp

<section class="kitten-profile-hero">
    <div class="container">
        <a class="kitten-back-link" href="{{ route('kittens.index') }}"><span aria-hidden="true">←</span> Все котята</a>

        <div class="kitten-profile-layout">
            <div class="kitten-profile-main">
                <div class="kitten-profile-gallery {{ $images->count() <= 1 ? 'is-single' : '' }}">
                    @forelse($images as $index => $image)
                        <a class="{{ $index === 0 ? 'kitten-gallery-main' : 'kitten-gallery-thumb' }}" href="{{ $image }}" aria-label="Открыть фото {{ $index + 1 }} котёнка {{ $displayName }}">
                            <img src="{{ $image }}" alt="{{ $kitten->image_alt ?: $displayName }} — фото {{ $index + 1 }}" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif decoding="async">
                        </a>
                    @empty
                        <span class="kitten-profile-placeholder">МарМелАма</span>
                    @endforelse
                </div>

                @if($storyHtml)
                    <section class="kitten-profile-story" aria-labelledby="kitten-story-title">
                        <div class="kitten-profile-story-heading">
                            <p class="eyebrow">Знакомство ближе</p>
                            <h2 id="kitten-story-title">Характер и особенности</h2>
                        </div>
                        <div class="kitten-profile-story-content">
                            {!! $storyHtml !!}
                        </div>
                    </section>
                @endif
            </div>

            <aside class="kitten-profile-panel">
                <div class="kitten-profile-status-row">
                    <p class="eyebrow">Котёнок питомника МарМелАма</p>
                    <span class="kitten-card-status {{ $status[1] }}">{{ $status[0] }}</span>
                </div>

                <div class="kitten-profile-heading">
                    <h1>{{ $displayName }}</h1>
                    @include('partials.kitten-attributes', ['kitten' => $kitten, 'attributesClass' => 'kitten-profile-attributes'])
                </div>

                <div class="kitten-profile-offer">
                    <span>{{ $offer[0] }}</span>
                    <strong>{{ $offer[1] }}</strong>
                    <p>{{ $offer[2] }}</p>
                </div>

                <dl class="kitten-profile-facts">
                    @if($sex)<div><dt>Пол</dt><dd>{{ $sex }}</dd></div>@endif
                    @if($kitten->color)<div><dt>Окрас</dt><dd>{{ $kitten->color }}</dd></div>@endif
                    @if($kitten->born_on)<div><dt>Дата рождения</dt><dd>{{ $kitten->born_on->format('d.m.Y') }}</dd></div>@endif
                    @if($publicLitter)<div><dt>Помёт</dt><dd><a href="{{ route('litters.show', $publicLitter->slug) }}">{{ $publicLitter->letter ? 'Помёт «'.$publicLitter->letter.'»' : $publicLitter->title }}</a></dd></div>@endif
                    @if($publicLitter?->father || $publicLitter?->father_name)<div><dt>Папа</dt><dd>{{ $publicLitter->father?->name ?: $publicLitter->father_name }}</dd></div>@endif
                    @if($publicLitter?->mother || $publicLitter?->mother_name)<div><dt>Мама</dt><dd>{{ $publicLitter->mother?->name ?: $publicLitter->mother_name }}</dd></div>@endif
                </dl>

                <div class="kitten-profile-actions">
                    @if($kitten->status === 'available')
                        <a class="button full" href="{{ $contactUrl }}">Обсудить</a>
                        <a class="button secondary full" href="{{ $site['max'] }}" target="_blank" rel="noopener noreferrer">Написать в {{ $site['max_label'] }}</a>
                    @elseif($kitten->status === 'reserved')
                        <a class="button full" href="{{ $contactUrl }}">Уточнить статус брони</a>
                        <a class="button secondary full" href="{{ route('kittens.index', ['status' => 'available']) }}">Смотреть свободных</a>
                    @else
                        <a class="button full" href="{{ route('kittens.index', ['status' => 'available']) }}">Смотреть свободных котят</a>
                        <a class="button secondary full" href="{{ route('contacts') }}#contact-form">Узнать о новых помётах</a>
                    @endif
                </div>

                <p class="kitten-profile-note"><span aria-hidden="true">i</span> Ответим на вопросы о здоровье, характере, документах и переезде котёнка.</p>
            </aside>
        </div>
    </div>
</section>

<section class="section kitten-assurance-section">
    <div class="container">
        <div class="section-title kitten-assurance-heading">
            <p class="eyebrow">Переезд домой</p>
            <h2>Поможем на каждом этапе</h2>
        </div>
        <div class="kitten-assurance-grid">
            <article><span>01</span><h3>Подготовка</h3><p>Расскажем об уходе, кормлении и спокойной адаптации котёнка дома.</p></article>
            <article><span>02</span><h3>Доставка</h3><p>Организуем самовывоз из Омска или доставку по России.</p><a href="{{ route('delivery') }}">Условия доставки →</a></article>
            <article><span>03</span><h3>Связь</h3><p>Остаёмся на связи и отвечаем на вопросы после переезда.</p><a href="{{ route('contacts') }}">Связаться с нами →</a></article>
        </div>
    </div>
</section>

@if($otherKittens->isNotEmpty())
<section class="section kitten-related-section">
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Ещё знакомства</p>
            <h2>Другие котята, которые ищут семью</h2>
        </div>
        <div class="grid grid-3 kitten-catalog-grid">
            @foreach($otherKittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
