@extends('layouts.site')

@section('title', 'Доставка котят - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Доставка</p>
        <h1>Доставка котят</h1>
        <p class="lead">Организуем доставку котят по России и за ее пределами.</p>
    </div>
</section>

<section class="section section-tight">
    <div class="container grid grid-3">
        @foreach([
            ['Авиа', 'Поможем подобрать удобный маршрут и подготовить котенка к перелету.'],
            ['Ж/Д', 'Возможна доставка железнодорожными перевозчиками по согласованию.'],
            ['Курьеры', 'Работаем с проверенными курьерскими службами.'],
            ['Самовывоз', 'Можно забрать котенка лично в Омске.'],
            ['Подготовка', 'Расскажем, как подготовить переноску, корм и первые дни дома.'],
            ['Стоимость', 'Стоимость зависит от города, способа доставки и перевозчика.'],
        ] as [$title, $text])
            <article class="delivery-card card"><h3>{{ $title }}</h3><p>{{ $text }}</p></article>
        @endforeach
    </div>
</section>

@if($page?->content)
<section class="section section--soft">
    <div class="container narrow prose-card card">{!! $page->content !!}</div>
</section>
@endif

@include('partials.cta')
@endsection
