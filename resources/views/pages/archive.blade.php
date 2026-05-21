@extends('layouts.site')

@section('title', 'Архив котят - МарМелАма')

@section('content')
<section class="page-hero"><div class="container"><p class="eyebrow">Архив</p><h1>Архив котят</h1><p class="lead">Котята, которые уже нашли свои семьи.</p></div></section>
<section class="section section-tight"><div class="container grid grid-3">@foreach($kittens as $kitten)@include('partials.kitten-card', ['kitten' => $kitten])@endforeach</div></section>
@endsection
