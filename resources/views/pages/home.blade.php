@extends('layouts.site')

@section('title', 'МарМелАма - бурманские котята из питомника')
@section('description', 'Бурманские котята из питомника МарМелАма в Омске. Свободные котята, пометы, производители, отзывы и доставка по России.')

@section('content')
@php($heroImageUrl = \App\Support\MediaUrl::url($heroImage))
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Питомник европейской бурмы</p>
            <h1>Бурманские котята из питомника МарМелАма</h1>
            <p class="lead">Питомник европейской бурмы в Омске. Поможем выбрать котенка по характеру, расскажем об уходе и организуем доставку по России.</p>
            <div class="button-row">
                <a class="button" href="#available">Смотреть свободных котят</a>
                <a class="button secondary" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
            </div>
            <div class="badge-row">
                <span class="badge">Европейская бурма</span>
                <span class="badge">Социализированные котята</span>
                <span class="badge">Доставка по России</span>
                <span class="badge">Поддержка владельцев</span>
            </div>
        </div>
        <div class="hero-media">
            @if($heroImageUrl)
                <img src="{{ $heroImageUrl }}" alt="{{ $heroSlide?->alt ?: 'Бурманский котенок питомника МарМелАма' }}">
            @else
                <span class="image-placeholder hero-placeholder">МарМелАма</span>
            @endif
        </div>
    </div>
</section>

<section class="section" id="available">
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Каталог</p>
            <h2>Свободные котята</h2>
            <p class="lead">Посмотрите малышей, которые сейчас доступны для бронирования.</p>
        </div>
        <div class="grid grid-3">
            @forelse($availableKittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @empty
                <div class="empty-state card">Сейчас свободных котят нет. Напишите нам, и мы расскажем о ближайших пометах.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container">
        <div class="section-title center">
            <p class="eyebrow">Доверие</p>
            <h2>Почему выбирают МарМелАма</h2>
            <p class="lead">Мы бережно выращиваем котят, уделяем внимание породному типу, здоровью, социализации и будущей адаптации в новом доме.</p>
        </div>
        <div class="grid grid-3 trust-grid">
            @foreach([
                ['Племенная работа', 'Внимание к породе, подбор производителей и аккуратное разведение.'],
                ['Здоровье', 'Ветеринарный контроль и генетические исследования производителей, когда такие данные есть.'],
                ['Социализация', 'Котята растут рядом с человеком, привыкают к общению, лотку и когтеточке.'],
                ['Помощь с выбором', 'Подскажем котенка по характеру и образу жизни семьи.'],
                ['Доставка', 'Организуем доставку по России и за ее пределами.'],
                ['Поддержка после переезда', 'Даем рекомендации по уходу, кормлению и адаптации.'],
            ] as [$title, $text])
                <article class="trust-card card">
                    <h3>{{ $title }}</h3>
                    <p>{{ $text }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section" id="how-to-buy">
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Покупка</p>
            <h2>Как купить котенка</h2>
            <p class="lead">Простой путь от первого вопроса до переезда малыша в новый дом.</p>
        </div>
        <div class="steps">
            @foreach([
                ['Выберите котенка', 'Посмотрите свободных малышей или напишите нам.'],
                ['Уточните детали', 'Расскажем о характере, окрасе, родителях и условиях покупки.'],
                ['Забронируйте', 'Фиксируем выбор и готовим котенка к переезду.'],
                ['Подготовьте дом', 'Дадим рекомендации по корму, лотку, когтеточке и адаптации.'],
                ['Получите котенка', 'Самовывоз или организованная доставка.'],
            ] as $index => [$title, $text])
                <article class="step-card card">
                    <span>{{ $index + 1 }}</span>
                    <h3>{{ $title }}</h3>
                    <p>{{ $text }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Питомник</p>
            <h2>Наши производители</h2>
            <p class="lead">Коты и кошки питомника, участвующие в племенной работе МарМелАма.</p>
        </div>
        <div class="grid grid-4">
            @foreach($parents as $parent)
                @include('partials.parent-card', ['parent' => $parent])
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Отзывы</p>
            <h2>Опыт владельцев</h2>
            <p class="lead">Короткие истории семей, в которые уже переехали выпускники питомника.</p>
        </div>
        <div class="grid grid-3">
            @foreach($reviews as $review)
                @include('partials.review-card', ['review' => $review])
            @endforeach
        </div>
        <div class="section-action">
            <a class="button secondary" href="{{ route('reviews') }}">Читать все отзывы</a>
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container split">
        <div>
            <p class="eyebrow">Доставка</p>
            <h2>Доставляем котят по России и за ее пределами</h2>
            <p class="lead">Организуем доставку авиа, ж/д перевозчиками и проверенными курьерскими службами.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('delivery') }}">Подробнее о доставке</a>
            <a class="button secondary" href="{{ $site['max'] }}">Уточнить маршрут в {{ $site['max_label'] }}</a>
        </div>
    </div>
</section>

@include('partials.cta')
@endsection
