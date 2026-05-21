@php
    $available = $litter->kittens->where('status', 'available')->count();
    $boys = $litter->kittens->where('sex', 'male')->count();
    $girls = $litter->kittens->where('sex', 'female')->count();
@endphp

<article class="litter-card card">
    <div class="card-body">
        <div class="litter-top">
            <p class="eyebrow">Помет {{ $litter->letter ?: $litter->title }}</p>
            <span class="status {{ $available > 0 ? 'available' : 'sold' }}">{{ $available > 0 ? 'Есть свободные' : 'Архив' }}</span>
        </div>
        <h3><a href="{{ route('litters.show', $litter->slug) }}">{{ $litter->title }}</a></h3>
        <dl class="meta-list">
            @if($litter->born_on)<div><dt>Дата рождения</dt><dd>{{ $litter->born_on->format('d.m.Y') }}</dd></div>@endif
            <div><dt>Котята</dt><dd>{{ $boys }} мальч. / {{ $girls }} дев.</dd></div>
            @if($litter->father)<div><dt>Отец</dt><dd>{{ $litter->father->name }}</dd></div>@endif
            @if($litter->mother)<div><dt>Мама</dt><dd>{{ $litter->mother->name }}</dd></div>@endif
        </dl>
        @if($litter->description)
            <div class="card-text">{!! \Illuminate\Support\Str::limit(strip_tags($litter->description), 140) !!}</div>
        @endif
        <a class="button secondary full" href="{{ route('litters.show', $litter->slug) }}">Подробнее</a>
    </div>
</article>
