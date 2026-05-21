@extends('layouts.site')

@section('title', ($sex === '1' ? 'Наши коты' : 'Наши кошки').' - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Производители</p>
        <h1>{{ $sex === '1' ? 'Наши коты' : 'Наши кошки' }}</h1>
        <p class="lead">Производители питомника МарМелАма: титулы, окрасы, происхождение и фотографии.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container grid grid-3">
        @foreach($parents as $parent)
            @include('partials.parent-card', ['parent' => $parent])
        @endforeach
    </div>
</section>
@endsection
