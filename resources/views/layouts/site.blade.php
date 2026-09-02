<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Питомник европейской бурмы МарМелАма в Омске. Котята, пометы, производители, отзывы и доставка по России.')">
    <meta name="theme-color" content="#FAF7F2">
    <title>@yield('title', 'МарМелАма: питомник европейской бурмы')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
@php($metrikaId = (int) config('services.yandex_metrika.id'))
<body
    @if($metrikaId > 0)
        data-metrika-id="{{ $metrikaId }}"
        data-analytics-consent-version="{{ config('legal.documents.cookies.version') }}"
        data-analytics-consent-days="{{ config('legal.retention.analytics_choice_days') }}"
    @endif
    @if(session('metrika_goal')) data-metrika-goal="{{ session('metrika_goal') }}" @endif
>
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <a class="floating-messenger" href="{{ $site['max'] }}" aria-label="Написать в {{ $site['max_label'] }}" data-analytics-goal="max_click">
        <img src="{{ asset('images/messengers/max.png') }}" alt="" width="192" height="192" aria-hidden="true">
    </a>
    @if($metrikaId > 0)
        @include('partials.analytics-consent')
    @endif
    <script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
</body>
</html>
