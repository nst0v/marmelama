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
            @php($imageUrl = \App\Support\MediaUrl::url($image->image_path))
            @if($imageUrl)
                <a class="gallery-item" href="{{ $imageUrl }}">
                    <img src="{{ $imageUrl }}" alt="{{ $image->alt ?: $image->title ?: 'Фото МарМелАма' }}">
                    @if($image->title)<span>{{ $image->title }}</span>@endif
                </a>
            @endif
        @endforeach
    </div>
</section>
@endsection
