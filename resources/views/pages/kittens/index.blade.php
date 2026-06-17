@extends('layouts.site')

@section('title', 'Котята бурмы. Продажа - МарМелАма')

@section('content')
<section class="page-hero kittens-hero">
    <div class="container kittens-hero-inner">
        <div>
            <p class="eyebrow">Каталог</p>
            <h1>Котята бурмы. Продажа</h1>
        </div>
        <div class="filter-tabs">
            @foreach(['all' => 'Все', 'male' => 'Мальчики', 'female' => 'Девочки', 'available' => 'Свободные', 'reserved' => 'Бронь', 'sold' => 'Архив'] as $key => $label)
                <a class="{{ $filter === $key ? 'active' : '' }}" href="{{ route('kittens.index', ['filter' => $key]) }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>

<section class="section section-tight kittens-list-section">
    <div class="container">
        <div class="grid grid-3">
            @forelse($kittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @empty
                <div class="empty-state card">По этому фильтру котят пока нет.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
