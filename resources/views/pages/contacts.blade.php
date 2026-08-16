@extends('layouts.site')

@section('title', 'Контакты - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Контакты</p>
        <h1>Связаться с МарМелАма</h1>
        <p class="lead">Напишите нам, если хотите узнать о свободных котятах, цене, бронировании или доставке.</p>
    </div>
</section>

<section class="section section-tight">
    <div class="container contact-grid">
        <div class="contact-card card">
            <h2>Контакты</h2>
            <dl class="meta-list detail-meta">
                <div><dt>Телефон</dt><dd><a href="tel:{{ $site['phone_href'] }}">{{ $site['phone'] }}</a></dd></div>
                <div><dt>{{ $site['max_label'] }}</dt><dd><a href="{{ $site['max'] }}">Написать в {{ $site['max_label'] }}</a></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></dd></div>
                <div><dt>Город</dt><dd>{{ $site['city'] }}</dd></div>
                <div><dt>ВК</dt><dd><a href="{{ $site['vk'] }}">vk.com/marmelama.omsk</a></dd></div>
            </dl>
        </div>

        <form class="contact-form card" id="contact-form" method="post" action="{{ route('contacts.send') }}">
            @csrf
            <h2>Написать нам</h2>
            @if(session('status'))<div class="form-status">{{ session('status') }}</div>@endif
            @if($selectedKitten)
                <input type="hidden" name="kitten_id" value="{{ $selectedKitten->id }}">
                <div class="form-status">Заявка по котёнку: <strong>{{ $selectedKitten->display_name }}</strong></div>
            @endif
            <label aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">Ваш сайт<input name="website" tabindex="-1" autocomplete="off"></label>
            <label>Ваше имя<input name="name" value="{{ old('name') }}" required></label>
            <label>Телефон<input name="phone" value="{{ old('phone') }}" required></label>
            <label>Email<input name="email" type="email" value="{{ old('email') }}"></label>
            <label>Сообщение<textarea name="message" rows="6" required>{{ old('message', $selectedKitten ? 'Здравствуйте! Хочу узнать подробнее про котёнка '.$selectedKitten->display_name : '') }}</textarea></label>
            @if($errors->any())<div class="form-errors">Проверьте заполнение полей.</div>@endif
            <button class="button" type="submit">Отправить сообщение</button>
        </form>
    </div>
</section>
@endsection
