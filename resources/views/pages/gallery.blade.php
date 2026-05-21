@extends('layouts.site')

@section('title', 'Галерея - МарМелАма')

@section('content')
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Фото</p>
        <h1>Галерея</h1>
        <p class="lead">Фотографии питомника, котят и производителей.</p>
    </div>
</section>
<section class="section section-tight">
    <div class="container gallery-page-grid">
        @foreach($images as $image)
            <a class="gallery-item" href="{{ asset('storage/'.$image->image_path) }}">
                <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $image->alt ?: $image->title ?: 'Фото МарМелАма' }}">
                @if($image->title)<span>{{ $image->title }}</span>@endif
            </a>
        @endforeach
    </div>
</section>
@endsection
