@php
    $sex = ['male' => 'Мальчик', 'female' => 'Девочка', 'unknown' => null][$kitten->sex] ?? null;
    $colorLabel = \App\Support\BurmeseColors::label($kitten->color);
    $colorSwatch = \App\Support\BurmeseColors::swatchKey($kitten->color);
@endphp

@if($sex || $colorLabel)
    <ul class="kitten-attributes {{ $attributesClass ?? '' }}" aria-label="Основные характеристики">
        @if($sex)
            <li class="kitten-card-sex kitten-card-sex--{{ $kitten->sex }}">
                <span class="kitten-card-sex-symbol" aria-hidden="true">
                    @if($kitten->sex === 'male')
                        <svg viewBox="0 0 20 20" fill="none" focusable="false">
                            <circle cx="7.75" cy="12.25" r="4.75"></circle>
                            <path d="M11.15 8.85 17 3m-4.75 0H17v4.75"></path>
                        </svg>
                    @else
                        <svg viewBox="0 0 20 20" fill="none" focusable="false">
                            <circle cx="10" cy="7.25" r="4.75"></circle>
                            <path d="M10 12v5.5M7.25 15h5.5"></path>
                        </svg>
                    @endif
                </span>
                <span>{{ $sex }}</span>
            </li>
        @endif
        @if($colorLabel)
            <li class="kitten-card-color">
                <span class="kitten-card-color-swatch kitten-card-color-swatch--{{ $colorSwatch }}" aria-hidden="true"></span>
                <span>Окрас: {{ $colorLabel }}</span>
            </li>
        @endif
    </ul>
@endif
