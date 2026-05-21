@extends('layouts.site')

@section('title', $title.' - МарМелАма')

@section('content')
<section class="page-hero"><div class="container"><p class="eyebrow">МарМелАма</p><h1>{{ $title }}</h1><p class="lead">{{ $text }}</p></div></section>
@include('partials.cta')
@endsection
