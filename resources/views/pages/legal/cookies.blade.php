@extends('layouts.site')

@section('title', 'Политика использования cookie — МарМелАма')
@section('description', 'Информация об обязательных cookie сайта МарМелАма и добровольной аналитике Яндекс Метрики.')

@section('content')
@php
    $legal = config('legal');
    $operator = $legal['operator'];
    $document = $legal['documents']['cookies'];
    $choiceDays = $legal['retention']['analytics_choice_days'];
@endphp

<section class="page-hero page-hero--simple">
    <div class="container page-heading">
        <p class="eyebrow">Правовая информация</p>
        <h1>Политика использования cookie</h1>
        <p class="lead">Редакция {{ $document['version'] }} от <time datetime="2026-09-02">{{ $document['effective_date'] }}</time></p>
    </div>
</section>

<section class="section content-section">
    <div class="container content-page-layout">
        <article class="content-prose rich-text card">
            <p>
                Эта Политика объясняет, как сайт <strong>мармелама.рф</strong> использует cookie, локальное хранилище браузера и похожие технологии.
                Оператор — <strong>{{ $operator['name'] }}</strong>, {{ $operator['short_status'] }}, ИНН {{ $operator['inn'] }}.
            </p>

            <h2>1. Что такое cookie</h2>
            <p>
                Cookie — небольшие текстовые фрагменты, которые браузер сохраняет на устройстве. LocalStorage — локальное хранилище браузера, которое не отправляется на сервер при каждом запросе.
            </p>

            <h2>2. Что использует Сайт</h2>
            <table>
                <thead>
                    <tr><th>Технология</th><th>Тип</th><th>Зачем нужна</th><th>Срок</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>marmelama-session</code></td>
                        <td>Обязательная</td>
                        <td>Сессия, работа формы, сохранение рекламных меток до отправки заявки</td>
                        <td>Обычно до 120 минут бездействия</td>
                    </tr>
                    <tr>
                        <td><code>XSRF-TOKEN</code></td>
                        <td>Обязательная</td>
                        <td>Защита формы от подделки запросов</td>
                        <td>В пределах сессии</td>
                    </tr>
                    <tr>
                        <td><code>marmelama.analyticsConsent</code> в localStorage</td>
                        <td>Служебная</td>
                        <td>Хранит выбор «разрешить» или «отказаться» и редакцию согласия</td>
                        <td>До {{ $choiceDays }} дней; при новой редакции выбор запрашивается заново</td>
                    </tr>
                    <tr>
                        <td><code>_ym_*</code>, <code>yabs-sid</code> и другие идентификаторы Яндекс Метрики</td>
                        <td>Аналитическая, только после согласия</td>
                        <td>Различение посетителей и визитов, оценка источников трафика и действий на Сайте</td>
                        <td>От сессии до 2 лет в зависимости от идентификатора; основные <code>_ym_uid</code> и <code>_ym_d</code> — до 1 года</td>
                    </tr>
                </tbody>
            </table>

            <h2>3. Обязательные технологии</h2>
            <p>
                Обязательные cookie нужны для защиты и отправки формы, поэтому они применяются без выбора аналитики. В серверной сессии также могут временно храниться UTM-метки, <code>yclid</code>,
                адрес страницы входа и реферер. Они переносятся в заявку, только если посетитель отправит форму.
            </p>

            <h2>4. Добровольная Яндекс Метрика</h2>
            <p>
                На Сайте используется счётчик № <strong>112180369</strong>. Код Метрики не загружается, пока посетитель сам не нажмёт «Разрешить». Отказ не ограничивает доступ к содержанию и форме.
            </p>
            <p>
                После согласия Метрика получает технические и поведенческие данные, описанные в <a href="{{ route('politics') }}">Политике обработки персональных данных</a>, и передаёт их ООО «ЯНДЕКС», 119021, г. Москва, ул. Льва Толстого, д. 16.
                Имя, телефон, email и текст формы в цели Метрики не передаются. Вебвизор не включён.
            </p>
            <p>
                Актуальные сведения: <a href="https://yandex.ru/support/metrica/ru/general/cookie-usage" target="_blank" rel="noopener noreferrer">cookie Метрики</a>,
                <a href="https://www.yandex.ru/support/metrica/ru/code/data-collected" target="_blank" rel="noopener noreferrer">собираемые данные</a>,
                <a href="https://yandex.ru/legal/metrica_termsofuse/ru/" target="_blank" rel="noopener noreferrer">условия сервиса</a> и
                <a href="https://yandex.ru/legal/confidential/ru/" target="_blank" rel="noopener noreferrer">политика Яндекса</a>.
            </p>

            <h2>5. Как изменить выбор</h2>
            <ul>
                <li>Нажмите «Настройки cookie» в нижней части любой страницы.</li>
                <li>Выберите «Разрешить» или «Отказаться». При отказе сайт прекращает новые запросы к Метрике и удаляет доступные ему аналитические cookie/localStorage.</li>
                <li>Можно также удалить cookie в настройках браузера или использовать <a href="https://yandex.ru/support/metrica/ru/general/opt-out" target="_blank" rel="noopener noreferrer">блокировщик Яндекс Метрики</a>.</li>
            </ul>
            <p>
                Блокировка всех cookie в браузере может помешать работе формы. Для отказа только от аналитики используйте настройку в футере.
            </p>

            <h2>6. Изменения и контакты</h2>
            <p>
                При изменении набора технологий или целей аналитики Политика обновляется. При существенном изменении условий согласие запрашивается заново. Вопросы можно направить на <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>.
            </p>
        </article>
    </div>
</section>
@endsection
