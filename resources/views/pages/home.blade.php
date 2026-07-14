@extends('layouts.site')

@section('title', 'МарМелАма - бурманские котята из питомника')
@section('description', 'Бурманские котята из питомника МарМелАма в Омске. Свободные котята, пометы, производители, отзывы и доставка по России.')

@section('content')
<section class="hero">
    <h1 class="visually-hidden">Питомник европейской бурмы МарМелАма</h1>
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
                </article>
            @empty
                <article class="hero-slide hero-slide--empty is-active" data-hero-slide aria-hidden="false">
                    <span class="image-placeholder hero-placeholder">МарМелАма</span>
                </article>
            @endforelse

            @if($heroSlides->count() > 1)
                <div class="hero-dots" aria-label="Слайды">
                    @foreach($heroSlides as $slide)
                        <button class="hero-dot{{ $loop->first ? ' is-active' : '' }}" type="button" data-hero-dot="{{ $loop->index }}" aria-label="Показать слайд {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<section class="home-paths" aria-label="Основные разделы питомника">
    <div class="container home-paths-grid">
        <a class="home-path-card home-path-card--kittens" href="{{ route('kittens.index') }}">
            <span class="home-path-visual">
                <img src="{{ asset('images/home/tab3.png') }}" alt="" width="400" height="376" loading="lazy" decoding="async">
            </span>
            <span class="home-path-body">
                <span class="home-path-title">Продажа котят</span>
                <span class="home-path-text">Предлагаем котят бурмы на продажу.</span>
            </span>
        </a>

        <a class="home-path-card home-path-card--male" href="{{ route('parents.index', '1') }}">
            <span class="home-path-visual">
                <img src="{{ asset('images/home/tab2.png') }}" alt="" width="400" height="376" loading="lazy" decoding="async">
            </span>
            <span class="home-path-body">
                <span class="home-path-title">Наши коты</span>
                <span class="home-path-text">Рассказываем о наших котах.</span>
            </span>
        </a>

        <a class="home-path-card home-path-card--female" href="{{ route('parents.index', '0') }}">
            <span class="home-path-visual">
                <img src="{{ asset('images/home/tab1.png') }}" alt="" width="400" height="376" loading="lazy" decoding="async">
            </span>
            <span class="home-path-body">
                <span class="home-path-title">Наши кошки</span>
                <span class="home-path-text">Рассказываем о наших кошках.</span>
            </span>
        </a>
    </div>
</section>

<section class="welcome-section" aria-labelledby="welcome-title" data-soft-reveal>
    <div class="container welcome-card">
        <div class="welcome-media">
            <img
                src="{{ asset('images/home/welcome.jpg') }}"
                alt="Основатель питомника МарМелАма Елена Иванова с бурманским котом и наградами"
                width="800"
                height="667"
                loading="lazy"
                decoding="async"
            >
            <span class="welcome-media-label">МарМелАма · Омск</span>
        </div>

        <div class="welcome-content">
            <p class="eyebrow">Добро пожаловать</p>
            <h2 id="welcome-title">Рады знакомству с вами</h2>
            <p class="lead welcome-lead">Меня зовут Елена Иванова, я основатель питомника европейской бурмы «МарМелАма» в Омске.</p>
            <p class="welcome-text">Мы бережно работаем с породой, уделяем внимание здоровью, характеру и ранней социализации каждого котёнка. Помогаем семье выбрать подходящего малыша и остаёмся на связи после его переезда в новый дом.</p>

            <div class="welcome-facts" aria-label="О питомнике кратко">
                <span>Европейская бурма</span>
                <span>Забота о здоровье</span>
                <span>Поддержка владельцев</span>
            </div>

            <a class="welcome-button" href="{{ route('content.show', 'about') }}">
                <span class="welcome-button-copy">
                    <span class="welcome-button-kicker">История и ценности</span>
                    <span class="welcome-button-label">Подробнее о питомнике</span>
                </span>
                <span class="welcome-button-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h13M13 7l5 5-5 5" />
                    </svg>
                </span>
            </a>
        </div>
    </div>
</section>

<section class="section" id="available" data-soft-reveal>
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Каталог</p>
            <h2>Свободные котята</h2>
            <p class="lead">Посмотрите малышей, которые сейчас доступны для бронирования.</p>
        </div>
        <div class="grid grid-3 home-kittens-grid">
            @forelse($availableKittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @empty
                <div class="empty-state card">Сейчас свободных котят нет. Напишите нам, и мы расскажем о ближайших пометах.</div>
            @endforelse
        </div>

        @if($availableKittens->count() > 2)
            <div class="home-catalog-more">
                <a class="button secondary" href="{{ route('kittens.index', ['status' => 'available']) }}">
                    Смотреть всех котят
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        @endif
    </div>
</section>

<section class="section home-reviews-section" data-soft-reveal>
    <div class="container">
        <div class="section-title">
            <p class="eyebrow">Отзывы</p>
            <h2>Опыт владельцев</h2>
            <p class="lead">Короткие истории семей, в которые уже переехали выпускники питомника.</p>
        </div>
        @php($initialReviewIndex = intdiv($reviews->count(), 2))
        <div class="grid grid-3 home-reviews-slider" data-review-slider data-review-start="{{ $initialReviewIndex }}" aria-label="Отзывы владельцев">
            @foreach($reviews as $review)
                @include('partials.review-card', ['review' => $review, 'compact' => true])
            @endforeach
        </div>

        @if($reviews->count() > 1)
            <div class="home-review-dots" aria-label="Выбор отзыва">
                @foreach($reviews as $review)
                    <button
                        type="button"
                        data-review-dot
                        aria-label="Показать отзыв {{ $loop->iteration }}"
                        @if($loop->index === $initialReviewIndex) aria-current="true" @endif
                    ></button>
                @endforeach
            </div>
        @endif

        <div class="section-action">
            <a class="button secondary" href="{{ route('reviews') }}">Читать все отзывы</a>
        </div>
    </div>
</section>

<section class="home-service-section" aria-labelledby="home-delivery-title" data-soft-reveal>
    <div class="container home-service-module">
        <div class="home-service-delivery">
            <h2 id="home-delivery-title">Доставляем котят по России и за её пределами</h2>
            <p class="lead">Организуем доставку авиа, ж/д перевозчиками и проверенными курьерскими службами.</p>
            <a class="button home-service-button" href="{{ route('delivery') }}">
                Подробнее о доставке
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <aside class="home-service-contact" aria-labelledby="home-contact-title">
            <h3 id="home-contact-title">Поможем выбрать вашего котёнка</h3>
            <p>Расскажем, кто сейчас свободен, ответим на вопросы и подберём малыша для вашей семьи.</p>
            <div class="home-service-actions">
                <a class="button" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
                <a class="button secondary" href="{{ route('contacts') }}#contact-form">Заказать звонок</a>
            </div>
        </aside>
    </div>
</section>
@endsection
