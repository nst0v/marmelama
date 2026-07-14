@extends('layouts.site')

@section('title', 'Котята европейской бурмы — питомник МарМелАма')

@section('content')
@php
    $statusFilters = [
        'available' => ['label' => 'Ищут семью', 'hot' => true],
        'all' => ['label' => 'Все анкеты', 'hot' => false],
        'reserved' => ['label' => 'Забронированы', 'hot' => false],
        'sold' => ['label' => 'Уже нашли хозяев', 'hot' => false],
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
                    @foreach($statusFilters as $key => $filterItem)
                        @continue(in_array($key, ['reserved', 'sold'], true) && (int) $statusCounts->get($key, 0) === 0 && $status !== $key)
                        @php
                            $filterParams = array_filter([
                                'status' => $key,
                                'sex' => $sex,
                                'color' => $color,
                            ], fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <a class="{{ $status === $key ? 'active' : '' }} {{ $filterItem['hot'] ? 'kitten-filter-hot' : '' }}" href="{{ route('kittens.index', $filterParams) }}" @if($status === $key) aria-current="page" @endif>
                            @if($filterItem['hot'])
                                <span class="kitten-filter-fire-badge" aria-hidden="true">
                                    <svg class="kitten-filter-fire" viewBox="0 0 32 32">
                                        <defs>
                                            <linearGradient id="kitten-fire-outer" x1="8" y1="5" x2="24" y2="29" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#ffad45"/>
                                                <stop offset=".48" stop-color="#f36a25"/>
                                                <stop offset="1" stop-color="#bd3519"/>
                                            </linearGradient>
                                            <linearGradient id="kitten-fire-core" x1="13" y1="18" x2="19" y2="28" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#fff3a6"/>
                                                <stop offset="1" stop-color="#ffc13d"/>
                                            </linearGradient>
                                        </defs>
                                        <path class="kitten-filter-fire-outer" d="M17.4 2.7c.6 5.8-3.9 8.5-3.9 13.1 0 1.9 1 3.5 2.7 4.6-.2-3.4 1.6-5.9 4.4-8.3 1.1 2.2 4.2 4.8 4.2 8.8 0 5-4 9-9 9s-9-4-9-9c0-6.2 3.9-12.1 10.6-18.2Z"/>
                                        <path class="kitten-filter-fire-core" d="M15.8 28c-2.5 0-4.5-2-4.5-4.5 0-2 1.1-3.7 3.2-5.7-.1 1.9.7 3.2 1.9 4.1-.1-2.1 1.1-3.6 2.9-5.2 1 1.7 1.6 3.6 1.6 5.6 0 3.2-2.1 5.7-5.1 5.7Z"/>
                                        <path class="kitten-filter-fire-shine" d="M18.6 6.5c.1 2.2-.7 4-1.7 5.5"/>
                                    </svg>
                                </span>
                            @endif
                            <span>{{ $filterItem['label'] }}</span>
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
                        <a class="button" href="{{ route('contacts') }}#contact-form">Узнать о новых помётах</a>
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
