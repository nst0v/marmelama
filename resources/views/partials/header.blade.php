<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="МарМелАма">
            <span class="brand-name">{{ $site['name'] }}</span>
            <span class="brand-subtitle">{{ $site['description'] }}</span>
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" id="site-nav">
            <a href="{{ route('kittens.index') }}">Котята</a>
            <a href="{{ route('litters.index') }}">Пометы</a>
            <a href="{{ route('content.show', 'about') }}">О питомнике</a>
            <a href="{{ route('parents.index', '1') }}">Производители</a>
            <a href="{{ route('reviews') }}">Отзывы</a>
            <a href="{{ route('delivery') }}">Доставка</a>
            <a href="{{ route('contacts') }}">Контакты</a>
        </nav>

        <div class="header-actions">
            <a class="phone-link" href="tel:{{ $site['phone_href'] }}">{{ $site['phone'] }}</a>
            <a class="icon-link" href="{{ $site['max'] }}">{{ $site['max_label'] }}</a>
            <a class="button small" href="{{ route('contacts') }}#contact-form">Забронировать</a>
        </div>
    </div>
</header>
