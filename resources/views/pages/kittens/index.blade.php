@extends('layouts.site')

@section('title', 'Котята европейской бурмы — питомник МарМелАма')

@section('content')
@php
    $statusFilters = [
        'available' => 'Ищут семью',
        'all' => 'Все анкеты',
        'reserved' => 'Забронированы',
        'sold' => 'Уже нашли хозяев',
    ];
    $hasAdditionalFilters = $sex !== null || $color !== null;
@endphp

<section class="page-hero kittens-catalog-hero">
    <div class="container page-heading catalog-heading">
        <h1>Котята бурмы</h1>

        <div class="kitten-filter-panel">
            <div class="kitten-filter-bar">
                <span class="kitten-filter-label">Статус</span>
                <nav class="filter-tabs kitten-filter-tabs" aria-label="Статус котёнка">
                    @foreach($statusFilters as $key => $label)
                        @continue(in_array($key, ['reserved', 'sold'], true) && (int) $statusCounts->get($key, 0) === 0 && $status !== $key)
                        @php
                            $filterParams = array_filter([
                                'status' => $key,
                                'sex' => $sex,
                                'color' => $color,
                            ], fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <a class="{{ $status === $key ? 'active' : '' }}" href="{{ route('kittens.index', $filterParams) }}" @if($status === $key) aria-current="page" @endif>
                            <span>{{ $label }}</span>
                            <span class="kitten-filter-count" aria-label="Количество: {{ $statusCounts->get($key, 0) }}">{{ $statusCounts->get($key, 0) }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <form class="kitten-filter-controls" action="{{ route('kittens.index') }}" method="get">
                <input type="hidden" name="status" value="{{ $status }}">

                <label class="kitten-filter-field" for="kitten-sex-filter">
                    <span>Пол</span>
                    <select id="kitten-sex-filter" name="sex">
                        <option value="">Любой пол</option>
                        <option value="male" @selected($sex === 'male')>Мальчики</option>
                        <option value="female" @selected($sex === 'female')>Девочки</option>
                    </select>
                </label>

                @if($colorOptions->isNotEmpty())
                    <label class="kitten-filter-field" for="kitten-color-filter">
                        <span>Окрас</span>
                        <select id="kitten-color-filter" name="color">
                            <option value="">Любой окрас</option>
                            @foreach($colorOptions as $key => $label)
                                <option value="{{ $key }}" @selected($color === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif

                <div class="kitten-filter-actions">
                    <button class="button kitten-filter-submit" type="submit">Применить</button>
                    @if($hasAdditionalFilters)
                        <a class="kitten-filter-reset" href="{{ route('kittens.index', ['status' => $status]) }}">Сбросить</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<section class="section kittens-list-section">
    <div class="container">
        <p class="kitten-results">Найдено: <strong>{{ $kittens->total() }}</strong></p>

        <div class="grid grid-3 kitten-catalog-grid">
            @forelse($kittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten, 'eager' => $loop->first])
            @empty
                <div class="kitten-empty-state">
                    <h2>По этому фильтру котят нет</h2>
                    <p>Посмотрите другие категории или напишите нам — расскажем о ближайших помётах.</p>
                    <div class="button-row">
                        <a class="button" href="{{ route('contacts') }}#contact-form" data-analytics-goal="contact_form_open">Узнать о новых помётах</a>
                        @if($hasAdditionalFilters)
                            <a class="button secondary" href="{{ route('kittens.index', ['status' => $status]) }}">Сбросить фильтры</a>
                        @else
                            <a class="button secondary" href="{{ route('kittens.index', ['status' => 'all']) }}">Показать все анкеты</a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        {{ $kittens->links('partials.pagination') }}
    </div>
</section>
@endsection
