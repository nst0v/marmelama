@extends('layouts.site')

@section('title', ($page?->meta_title ?: 'Доставка котят').' - МарМелАма')
@section('description', $page?->meta_description ?: 'Организуем доставку котят по России и за её пределами.')

@section('content')
@php
    $pageTitle = $page?->h1 ?: 'Доставка котят';
    $pageContent = \App\Support\RichText::forPage($page?->content, $pageTitle, removeLeadingHeading: true);
@endphp

<section class="page-hero delivery-hero">
    <div class="container page-heading delivery-heading">
        <h1>{{ $pageTitle }}</h1>

        @if($pageContent)
            <div class="delivery-hero-copy rich-text">{!! $pageContent !!}</div>
        @else
            <p class="lead">Подберём безопасный способ переезда котёнка по России или за её пределы.</p>
        @endif
    </div>
</section>

<section class="section delivery-content-section">
    <div class="container delivery-layout">
        <article class="delivery-main card">
            <header class="delivery-main-heading">
                <h2>Способы доставки</h2>
                <p>Подходящий вариант зависит от города, доступных рейсов и возраста котёнка.</p>
            </header>

            <ol class="delivery-methods">
                @foreach([
                    ['Авиа', 'Подберём удобный рейс и заранее расскажем, как котёнок будет подготовлен к перелёту.'],
                    ['Железная дорога', 'Согласуем перевозчика, станцию отправления и встречу котёнка в вашем городе.'],
                    ['Курьерская доставка', 'Работаем с проверенными сопровождающими и остаёмся на связи в пути.'],
                ] as $index => [$title, $text])
                    <li>
                        <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </article>

        <aside class="delivery-side" aria-label="Условия доставки">
            <h2>Что важно знать</h2>

            <div class="delivery-facts">
                <article>
                    <h3>Самовывоз из Омска</h3>
                    <p>Можно забрать котёнка лично по предварительной договорённости.</p>
                </article>
                <article>
                    <h3>Подготовка к дороге</h3>
                    <p>Подскажем, какая нужна переноска, корм и что подготовить к первым дням дома.</p>
                </article>
                <article>
                    <h3>Стоимость</h3>
                    <p>Рассчитывается индивидуально после выбора маршрута и перевозчика.</p>
                </article>
            </div>

            <div class="delivery-contact">
                <h3>Уточнить маршрут</h3>
                <p>Назовите ваш город — предложим подходящие варианты и рассчитаем стоимость.</p>
                <div class="delivery-actions">
                    <a class="button full" href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a>
                    <a class="button secondary full" href="tel:{{ $site['phone_href'] }}">Позвонить</a>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection
