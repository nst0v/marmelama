@extends('layouts.site')

@section('title', 'Реквизиты — МарМелАма')
@section('description', 'Реквизиты самозанятой Баловатской Елены Александровны, питомник европейской бурмы МарМелАма.')

@section('content')
@php
    $legal = config('legal');
    $operator = $legal['operator'];
    $document = $legal['documents']['requisites'];
@endphp

<section class="page-hero page-hero--simple">
    <div class="container page-heading">
        <p class="eyebrow">Правовая информация</p>
        <h1>Реквизиты</h1>
        <p class="lead">Актуально с <time datetime="2026-09-02">{{ $document['effective_date'] }}</time></p>
    </div>
</section>

<section class="section content-section">
    <div class="container content-page-layout">
        <article class="content-prose rich-text card">
            <h2>Продавец и владелец сайта</h2>
            <dl class="legal-details">
                <div>
                    <dt>ФИО</dt>
                    <dd><strong>{{ $operator['name'] }}</strong></dd>
                </div>
                <div>
                    <dt>Статус</dt>
                    <dd>{{ $operator['status'] }}</dd>
                </div>
                <div>
                    <dt>ИНН</dt>
                    <dd><strong>{{ $operator['inn'] }}</strong></dd>
                </div>
                <div>
                    <dt>Контактный город</dt>
                    <dd>г. {{ $operator['city'] }}, Российская Федерация</dd>
                </div>
                <div>
                    <dt>Сайт</dt>
                    <dd><a href="{{ route('home') }}">мармелама.рф</a></dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></dd>
                </div>
                <div>
                    <dt>Телефон</dt>
                    <dd><a href="tel:{{ $site['phone_href'] }}">{{ $site['phone'] }}</a></dd>
                </div>
            </dl>

            <h2>Расчёты и чеки</h2>
            <p>
                При получении оплаты продавец формирует чек плательщика налога на профессиональный доход и передаёт его покупателю
                способом, согласованным при оформлении сделки. Банковские реквизиты для оплаты сообщаются покупателю индивидуально после согласования условий.
            </p>

            <h2>Информация на сайте</h2>
            <p>
                Размещённые на сайте сведения о котятах, наличии, цене и доставке носят информационный характер и сами по себе не являются публичной офертой.
                Итоговые условия, стоимость, порядок бронирования, оплаты и передачи котёнка согласовываются сторонами отдельно.
            </p>

            <p>
                Связанные документы: <a href="{{ route('politics') }}">Политика обработки персональных данных</a>,
                <a href="{{ route('personal-data-consent') }}">Согласие на обработку персональных данных</a> и
                <a href="{{ route('cookies') }}">Политика использования cookie</a>.
            </p>
        </article>
    </div>
</section>
@endsection
