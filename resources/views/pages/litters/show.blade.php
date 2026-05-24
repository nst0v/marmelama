@extends('layouts.site')

@section('title', $litter->title.' - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container split">
        <div>
            <p class="eyebrow">Помет {{ $litter->letter }}</p>
            <h1>{{ $litter->title }}</h1>
            <p class="lead">
                @if($litter->born_on)Дата рождения: {{ $litter->born_on->format('d.m.Y') }}.@endif
                @if($litter->description){!! strip_tags($litter->description) !!}@endif
            </p>
        </div>
        <div class="card detail-panel">
            <dl class="meta-list detail-meta">
                @if($litter->father || $litter->father_name)<div><dt>Отец</dt><dd>{{ $litter->father?->name ?: $litter->father_name }}</dd></div>@endif
                @if($litter->mother || $litter->mother_name)<div><dt>Мама</dt><dd>{{ $litter->mother?->name ?: $litter->mother_name }}</dd></div>@endif
                <div><dt>Котят</dt><dd>{{ $litter->kittens->count() }}</dd></div>
                <div><dt>Свободны</dt><dd>{{ $litter->kittens->where('status', 'available')->count() }}</dd></div>
            </dl>
            <a class="button full" href="{{ route('contacts') }}#contact-form">Уточнить по помету</a>
        </div>
    </div>
</section>

@if($litter->content)
<section class="section section-tight">
    <div class="container narrow prose-card card">
        {!! $litter->content !!}
    </div>
</section>
@endif

<section class="section section--soft">
    <div class="container">
        <div class="section-title"><p class="eyebrow">Котята</p><h2>Котята этого помета</h2></div>
        <div class="grid grid-3">
            @forelse($litter->kittens as $kitten)
                @include('partials.kitten-card', ['kitten' => $kitten])
            @empty
                <div class="empty-state card">Котята этого помета пока не добавлены.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
