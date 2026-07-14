<header class="site-header">
    <div class="container header-inner">
        <a class="brand header-brand" href="{{ route('home') }}" aria-label="МарМелАма — на главную">
            <span class="brand-mark" aria-hidden="true">
                <img class="brand-logo" src="{{ asset('images/brand/logo.png') }}" alt="" width="196" height="196">
            </span>
            <span class="brand-copy">
                <span class="brand-name">{{ $site['name'] }}</span>
                <span class="brand-subtitle">{{ $site['description'] }}</span>
            </span>
        </a>

        <a class="mobile-call-badge" href="tel:{{ $site['phone_href'] }}" aria-label="Позвонить по номеру {{ $site['phone'] }}">
            <span class="mobile-call-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M6.62 10.79a15.46 15.46 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2Z"/>
                </svg>
            </span>
            <span class="mobile-call-number">{{ $site['phone'] }}</span>
        </a>

        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Открыть меню">
            <span></span><span></span><span></span>
        </button>

        <div class="header-actions">
            <div class="header-contact-row">
                <a class="button small header-booking" href="{{ route('contacts') }}#contact-form">Забронировать</a>
                <a class="phone-link" href="tel:{{ $site['phone_href'] }}" aria-label="Позвонить по номеру {{ $site['phone'] }}">
                    <span class="mobile-call-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.46 15.46 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2Z"/>
                        </svg>
                    </span>
                    <span class="mobile-call-number">{{ $site['phone'] }}</span>
                </a>
                <div class="messenger-links" role="group" aria-label="Написать нам в мессенджере">
                    <a class="messenger-link messenger-link--max" href="{{ $site['max'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в MAX" title="MAX" data-messenger="max">
                        <img src="{{ asset('images/messengers/max.png') }}" alt="" width="192" height="192" aria-hidden="true">
                        <span class="messenger-label">MAX</span>
                    </a>
                    <a class="messenger-link messenger-link--telegram" href="{{ $site['telegram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в Telegram" title="Telegram" data-messenger="telegram">
                        <img src="{{ asset('images/messengers/telegram.svg') }}" alt="" width="496" height="496" aria-hidden="true">
                        <span class="messenger-label">Telegram</span>
                    </a>
                    <a class="messenger-link messenger-link--whatsapp" href="{{ $site['whatsapp'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в WhatsApp" title="WhatsApp" data-messenger="whatsapp">
                        <img src="{{ asset('images/messengers/whatsapp.svg') }}" alt="" width="448" height="448" aria-hidden="true">
                        <span class="messenger-label">WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="site-nav" id="site-nav">
            <a @class(['is-active' => request()->routeIs('home')]) href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Главная</a>
            <a @class(['site-nav-kittens', 'is-active' => request()->routeIs('kittens.*')]) href="{{ route('kittens.index') }}" @if(request()->routeIs('kittens.*')) aria-current="page" @endif>Наши котята</a>
            <a @class(['is-active' => request()->routeIs('content.show') && request()->route('slug') === 'about']) href="{{ route('content.show', 'about') }}" @if(request()->routeIs('content.show') && request()->route('slug') === 'about') aria-current="page" @endif>О питомнике</a>
            <a @class(['is-active' => request()->routeIs('litters.*')]) href="{{ route('litters.index') }}" @if(request()->routeIs('litters.*')) aria-current="page" @endif>Пометы</a>
            <a @class(['is-active' => request()->routeIs('reviews')]) href="{{ route('reviews') }}" @if(request()->routeIs('reviews')) aria-current="page" @endif>Отзывы</a>
            <a @class(['is-active' => request()->routeIs('delivery')]) href="{{ route('delivery') }}" @if(request()->routeIs('delivery')) aria-current="page" @endif>Доставка</a>
        </nav>
    </div>
</header>
