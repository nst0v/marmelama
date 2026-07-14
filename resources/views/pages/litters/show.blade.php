@extends('layouts.site')

@section('title', $litter->title.' - МарМелАма')

@section('content')
@php
    $pageTitle = $litter->letter ? 'Помёт '.$litter->letter : $litter->title;
    $rawTitle = \App\Support\RichText::plain($litter->title);
    $titleLooksGenerated = (bool) preg_match('/\d{2}\.\d{2}\.\d{4}/u', $rawTitle)
        || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($rawTitle), ['помет', 'помёт']);
    $subtitle = $litter->letter && ! $titleLooksGenerated && $rawTitle !== $pageTitle ? $rawTitle : null;
    $description = \App\Support\RichText::plain($litter->description);
    $content = \App\Support\RichText::forPage($litter->content, $pageTitle, removeLeadingHeading: true);
@endphp

<section class="page-hero">
    <div class="container split">
        <div>
            <h1>{{ $pageTitle }}</h1>
            @if($litter->born_on || $subtitle || $description)
                <p class="lead">
                    @if($litter->born_on)Дата рождения: {{ $litter->born_on->format('d.m.Y') }}.@endif
                    @if($subtitle) {{ $subtitle }}@endif
                    @if($description) {{ $description }}@endif
                </p>
            @endif
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

@if($content)
<section class="section section-tight">
    <div class="container narrow prose-card rich-text card">
        {!! $content !!}
    </div>
</section>
@endif

<section class="section section--soft">
    <div class="container">
        <div class="section-title"><h2>Котята этого помёта</h2></div>
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
