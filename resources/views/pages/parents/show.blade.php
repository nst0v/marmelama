@extends('layouts.site')

@section('title', $parent->name.' - производитель МарМелАма')

@section('content')
@php($images = is_array($parent->images) ? $parent->images : [])
<section class="page-hero detail-hero">
    <div class="container detail-grid">
        <div class="gallery-grid">
            @forelse($images as $image)
                <a href="{{ asset('storage/'.$image) }}"><img src="{{ asset('storage/'.$image) }}" alt="{{ $parent->name }}"></a>
            @empty
                <span class="image-placeholder">МарМелАма</span>
            @endforelse
        </div>
        <div class="detail-panel card">
            <p class="eyebrow">{{ $parent->sex === 'male' ? 'Кот' : 'Кошка' }}</p>
            <h1>{{ $parent->name }}</h1>
            <dl class="meta-list detail-meta">
                @if($parent->title)<div><dt>Титулы</dt><dd>{{ $parent->title }}</dd></div>@endif
                @if($parent->color)<div><dt>Окрас</dt><dd>{{ $parent->color }}</dd></div>@endif
                @if($parent->birthday)<div><dt>Дата рождения</dt><dd>{{ $parent->birthday->format('d.m.Y') }}</dd></div>@endif
                @if($parent->father_name)<div><dt>Отец</dt><dd>{{ $parent->father_name }}</dd></div>@endif
                @if($parent->mother_name)<div><dt>Мама</dt><dd>{{ $parent->mother_name }}</dd></div>@endif
                @if($parent->breeder)<div><dt>Заводчик</dt><dd>{{ $parent->breeder }}</dd></div>@endif
            </dl>
            <a class="button full" href="{{ route('kittens.index') }}">Посмотреть котят</a>
        </div>
    </div>
</section>

@if($parent->description || $parent->content || $parent->genetic_tests)
<section class="section section-tight">
    <div class="container narrow prose-card card">
        @if($parent->genetic_tests)<h2>Генетические исследования</h2><p>{{ $parent->genetic_tests }}</p>@endif
        {!! $parent->content ?: $parent->description !!}
    </div>
</section>
@endif

@if($litters->isNotEmpty())
<section class="section section--soft">
    <div class="container">
        <div class="section-title"><p class="eyebrow">Потомство</p><h2>Пометы</h2></div>
        <div class="grid grid-3">
            @foreach($litters as $litter)
                @include('partials.litter-card', ['litter' => $litter])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
