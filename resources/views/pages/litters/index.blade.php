@extends('layouts.site')

@section('title', 'Пометы бурманских котят - МарМелАма')

@section('content')
@php
    $statusFilters = [
        'all' => 'Все помёты',
        'available' => 'Есть свободные котята',
        'planned' => 'Планируются',
        'reserved' => 'Все в брони',
        'archive' => 'Архив',
    ];
@endphp

<section class="page-hero page-hero--simple litter-catalog-hero">
    <div class="container page-heading">
        <h1>Помёты питомника</h1>
        <p class="lead">Даты рождения, родители и состав каждого помёта — всё важное без повторов.</p>

        <nav class="filter-tabs litter-status-tabs" aria-label="Статус помёта">
            @foreach($statusFilters as $key => $label)
                @php
                    $statusQuery = array_filter([
                        'status' => $key === 'all' ? null : $key,
                        'q' => $filters['q'] !== '' ? $filters['q'] : null,
                        'year' => $filters['year'],
                        'parent' => $filters['parent'],
                        'sort' => $filters['sort'] === 'newest' ? null : $filters['sort'],
                    ], fn ($value) => $value !== null && $value !== '');
                @endphp
                <a href="{{ route('litters.index', $statusQuery) }}"
                   class="{{ $filters['status'] === $key ? 'active' : '' }}"
                   @if($filters['status'] === $key) aria-current="page" @endif>
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>
</section>

<section class="section content-section content-section--before-cta">
    <div class="container">
        <form class="litter-filter-panel" method="get" action="{{ route('litters.index') }}">
            @if($filters['status'] !== 'all')
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            @endif

            <div class="litter-filter-field litter-filter-search">
                <label for="litter-search">Поиск</label>
                <input id="litter-search" type="search" name="q" value="{{ $filters['q'] }}" maxlength="80" placeholder="Литера, название или родитель">
            </div>

            <div class="litter-filter-field">
                <label for="litter-year">Год рождения</label>
                <select id="litter-year" name="year">
                    <option value="">Любой год</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected($filters['year'] === $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="litter-filter-field">
                <label for="litter-parent">Родитель</label>
                <select id="litter-parent" name="parent">
                    <option value="">Любой родитель</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected($filters['parent'] === $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="litter-filter-field">
                <label for="litter-sort">Сортировка</label>
                <select id="litter-sort" name="sort">
                    <option value="newest" @selected($filters['sort'] === 'newest')>Сначала новые</option>
                    <option value="oldest" @selected($filters['sort'] === 'oldest')>Сначала ранние</option>
                </select>
            </div>

            <div class="litter-filter-actions">
                <button class="button small" type="submit">Применить</button>
                @if($hasActiveFilters)
                    <a class="litter-filter-reset" href="{{ route('litters.index') }}">Сбросить</a>
                @endif
            </div>
        </form>

        <div id="litter-results" class="litter-results-bar">
            <p>
                Найдено помётов: <strong>{{ $litters->total() }}</strong>
                @if($litters->total() > 0)
                    <span>Показаны {{ $litters->firstItem() }}–{{ $litters->lastItem() }}</span>
                @endif
            </p>
        </div>

        <div class="grid litters-grid" aria-live="polite">
            @forelse($litters as $litter)
                @include('partials.litter-card', ['litter' => $litter])
            @empty
                <div class="litter-empty-state card">
                    @if($hasActiveFilters)
                        <h2>По выбранным условиям помётов нет</h2>
                        <p>Попробуйте изменить один из фильтров или посмотреть все помёты.</p>
                        <a class="button secondary small" href="{{ route('litters.index') }}">Показать все помёты</a>
                    @else
                        <h2>Помёты пока не добавлены</h2>
                        <p>Информация появится здесь после публикации в админ-панели.</p>
                    @endif
                </div>
            @endforelse
        </div>

        {{ $litters->onEachSide(1)->links('partials.pagination') }}
    </div>
</section>
@include('partials.cta')
@endsection
