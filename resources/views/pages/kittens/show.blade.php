@extends('layouts.site')

@section('title', $kitten->name.' - котенок МарМелАма')

@section('content')
@php
    $images = is_array($kitten->images) ? $kitten->images : [];
    $status = ['available' => 'Свободен', 'reserved' => 'Бронь', 'sold' => 'Продан'][$kitten->status] ?? 'Статус уточняется';
@endphp

<section class="page-hero detail-hero">
    <div class="container detail-grid">
        <div class="gallery-grid">
            @forelse($images as $image)
                <a href="{{ asset('storage/'.$image) }}"><img src="{{ asset('storage/'.$image) }}" alt="{{ $kitten->image_alt ?: $kitten->name }}"></a>
            @empty
                <span class="image-placeholder">МарМелАма</span>
            @endforelse
        </div>
        <div class="detail-panel card">
            <p class="eyebrow">Котенок</p>
            <h1>{{ $kitten->name }}</h1>
            <span class="status {{ $kitten->status }}">{{ $status }}</span>
            <dl class="meta-list detail-meta">
                @if($kitten->sex !== 'unknown')<div><dt>Пол</dt><dd>{{ $kitten->sex === 'male' ? 'Мальчик' : 'Девочка' }}</dd></div>@endif
                @if($kitten->color)<div><dt>Окрас</dt><dd>{{ $kitten->color }}</dd></div>@endif
                @if($kitten->born_on)<div><dt>Дата рождения</dt><dd>{{ $kitten->born_on->format('d.m.Y') }}</dd></div>@endif
                @if($kitten->litter)<div><dt>Помет</dt><dd><a href="{{ route('litters.show', $kitten->litter->slug) }}">{{ $kitten->litter->title }}</a></dd></div>@endif
                @if($kitten->litter?->father)<div><dt>Отец</dt><dd>{{ $kitten->litter->father->name }}</dd></div>@endif
                @if($kitten->litter?->mother)<div><dt>Мама</dt><dd>{{ $kitten->litter->mother->name }}</dd></div>@endif
            </dl>
            <div class="button-row vertical">
                <a class="button full" href="{{ route('contacts') }}?kitten={{ urlencode($kitten->name) }}#contact-form">Узнать цену</a>
                <a class="button secondary full" href="{{ route('contacts') }}#contact-form">Забронировать котенка</a>
                <a class="button ghost full" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
            </div>
        </div>
    </div>
</section>

@if($kitten->content || $kitten->description)
<section class="section section-tight">
    <div class="container narrow prose-card card">
        <h2>О котенке</h2>
        {!! $kitten->content ?: $kitten->description !!}
    </div>
</section>
@endif

<section class="section section--soft">
    <div class="container grid grid-3">
        <article class="trust-card card"><h3>Что входит при передаче</h3><p>Консультация по уходу, рекомендации по адаптации и помощь с доставкой.</p></article>
        <article class="trust-card card"><h3>Доставка</h3><p>Самовывоз из Омска или организованная доставка по России.</p><a href="{{ route('delivery') }}">Подробнее о доставке</a></article>
        <article class="trust-card card"><h3>Связь</h3><p>Расскажем о характере, родителях и условиях покупки.</p><a href="{{ route('contacts') }}">Контакты</a></article>
    </div>
</section>

@if($otherKittens->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-title"><p class="eyebrow">Еще малыши</p><h2>Другие свободные котята</h2></div>
        <div class="grid grid-3">
            @foreach($otherKittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
