@extends('layouts.site')

@section('title', 'Пометы бурманских котят - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Пометы</p>
        <h1>Пометы МарМелАма</h1>
        <p class="lead">Список пометов питомника с родителями, датами рождения и котятами.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container grid grid-3">
        @foreach($litters as $litter)
            @include('partials.litter-card', ['litter' => $litter])
        @endforeach
    </div>
</section>
@include('partials.cta')
@endsection
