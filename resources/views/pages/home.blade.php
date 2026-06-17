@extends('layouts.site')

@section('title', 'МарМелАма - бурманские котята из питомника')
@section('description', 'Бурманские котята из питомника МарМелАма в Омске. Свободные котята, пометы, производители, отзывы и доставка по России.')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-showcase{{ $heroSlides->count() <= 1 ? ' is-single' : '' }}" data-hero-slider>
            @forelse($heroSlides as $slide)
                <article class="hero-slide{{ $loop->first ? ' is-active' : '' }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                    <img
                        src="{{ $slide['image_url'] }}"
                        alt="{{ $slide['alt'] ?: 'Бурманский котенок питомника МарМелАма' }}"
                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                        decoding="async"
                    >
                    <div class="hero-slide-content">
                        <p class="eyebrow">Питомник европейской бурмы</p>
                        @if($loop->first)
                            <h1>{{ $slide['title'] ?: 'МарМелАма: бурманские котята с характером' }}</h1>
                        @else
                            <h2>{{ $slide['title'] ?: 'Бурманские котята МарМелАма' }}</h2>
                        @endif
                        <div class="hero-slide-text">
                            @if($slide['caption'])
                                {!! $slide['caption'] !!}
                            @else
                                <p>Выращиваем котят рядом с человеком, помогаем выбрать малыша по темпераменту и сопровождаем семьи после переезда.</p>
                            @endif
                        </div>
                        <div class="button-row hero-actions">
                            <a class="button" href="#available">Выбрать котенка</a>
                            <a class="button secondary" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
                            @if($slide['url'])
                                <a class="button ghost" href="{{ $slide['url'] }}">Подробнее</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <article class="hero-slide hero-slide--empty is-active" data-hero-slide aria-hidden="false">
                    <span class="image-placeholder hero-placeholder">МарМелАма</span>
                    <div class="hero-slide-content">
                        <p class="eyebrow">Питомник европейской бурмы</p>
                        <h1>МарМелАма: бурманские котята с характером</h1>
                        <div class="hero-slide-text">
                            <p>Поможем выбрать котенка, расскажем об уходе и организуем доставку по России.</p>
                        </div>
                        <div class="button-row hero-actions">
                            <a class="button" href="#available">Выбрать котенка</a>
                            <a class="button secondary" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
                        </div>
                    </div>
                </article>
            @endforelse

            @if($heroSlides->count() > 1)
                <div class="hero-controls" aria-label="Управление слайдером">
                    <button class="hero-control" type="button" data-hero-prev aria-label="Предыдущий слайд"><span aria-hidden="true">&larr;</span></button>
                    <button class="hero-control" type="button" data-hero-next aria-label="Следующий слайд"><span aria-hidden="true">&rarr;</span></button>
                </div>
                <div class="hero-dots" aria-label="Слайды">
                    @foreach($heroSlides as $slide)
                        <button class="hero-dot{{ $loop->first ? ' is-active' : '' }}" type="button" data-hero-dot="{{ $loop->index }}" aria-label="Показать слайд {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
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
