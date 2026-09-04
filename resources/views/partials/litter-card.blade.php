@php
    $total = $litter->kittens->count();
    $boys = $litter->kittens->where('sex', 'male')->count();
    $girls = $litter->kittens->where('sex', 'female')->count();
    $available = $litter->kittens->where('status', 'available')->count();
    $status = [
        'planned' => ['Планируется', 'reserved'],
        'available' => ['Есть свободные', 'available'],
        'reserved' => ['Все в брони', 'reserved'],
        'archive' => ['Архив', 'sold'],
    ][$litter->status] ?? ['Статус уточняется', 'sold'];
    $displayTitle = $litter->letter ? 'Помёт '.$litter->letter : $litter->title;
    $rawTitle = \App\Support\RichText::plain($litter->title);
    $titleLooksGenerated = (bool) preg_match('/\d{2}\.\d{2}\.\d{4}/u', $rawTitle)
        || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($rawTitle), ['помет', 'помёт']);
    $subtitle = $litter->letter && ! $titleLooksGenerated && $rawTitle !== $displayTitle ? $rawTitle : null;
    $description = \App\Support\RichText::plain($litter->description);
    $fatherName = $litter->father?->name ?: $litter->father_name;
    $motherName = $litter->mother?->name ?: $litter->mother_name;
@endphp

<article class="litter-card listing-card card">
    <div class="card-body">
        <div class="litter-top">
            @if($litter->born_on)
                <time datetime="{{ $litter->born_on->toDateString() }}">{{ $litter->born_on->format('d.m.Y') }}</time>
            @else
                <span class="litter-date">Дата уточняется</span>
            @endif
            <span class="status {{ $status[1] }}">{{ $status[0] }}</span>
        </div>

        <div class="card-heading">
            <h2 class="card-title"><a href="{{ route('litters.show', $litter->slug) }}">{{ $displayTitle }}</a></h2>
            @if($subtitle)<p class="litter-subtitle">{{ $subtitle }}</p>@endif
        </div>

        @if($total > 0 || $fatherName || $motherName)
        <dl class="card-facts litter-facts">
            @if($total > 0)
                <div>
                    <dt>Котята</dt>
                    <dd>{{ $total }} всего@if($boys || $girls) · {{ $boys }} мальч. · {{ $girls }} дев.@endif</dd>
                </div>
            @endif
            @if($available > 0)<div><dt>Свободны</dt><dd>{{ $available }}</dd></div>@endif
            @if($fatherName)<div><dt>Отец</dt><dd>{{ $fatherName }}</dd></div>@endif
            @if($motherName)<div><dt>Мама</dt><dd>{{ $motherName }}</dd></div>@endif
        </dl>
        @endif

        @if($description)
            <p class="card-text">{{ \Illuminate\Support\Str::limit($description, 180) }}</p>
        @endif

        <a class="card-link" href="{{ route('litters.show', $litter->slug) }}">Смотреть помёт <span aria-hidden="true">→</span></a>
    </div>
</article>
