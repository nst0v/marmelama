<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <a class="brand footer-brand" href="{{ route('home') }}">
                <span class="brand-name">{{ $site['name'] }}</span>
                <span class="brand-subtitle">{{ $site['description'] }}</span>
            </a>
            <p class="footer-text">Бурманские котята в Омске. Помогаем выбрать малыша и сопровождаем владельцев после переезда.</p>
        </div>
        <div>
            <h3>Навигация</h3>
            <a href="{{ route('kittens.index') }}">Котята</a>
            <a href="{{ route('litters.index') }}">Пометы</a>
            <a href="{{ route('content.show', 'about') }}">О питомнике</a>
            <a href="{{ route('reviews') }}">Отзывы</a>
            <a href="{{ route('delivery') }}">Доставка</a>
            <a href="{{ route('contacts') }}">Контакты</a>
        </div>
        <div>
            <h3>Покупателям</h3>
            <a href="{{ route('home') }}#how-to-buy">Как купить котенка</a>
            <a href="{{ route('delivery') }}">Доставка</a>
            <a href="{{ route('archive') }}">Архив котят</a>
            <a href="{{ route('gallery') }}">Галерея</a>
        </div>
        <div>
            <h3>Контакты</h3>
            <a href="tel:{{ $site['phone_href'] }}">{{ $site['phone'] }}</a>
            <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
            <div class="social-row">
                <a href="{{ $site['instagram'] }}" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1"/></svg>
                </a>
                <a href="{{ $site['vk'] }}">VK</a>
                <a href="{{ $site['max'] }}">{{ $site['max_label'] }}</a>
            </div>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>© {{ date('Y') }} МарМелАма</span>
        <a href="{{ route('politics') }}">Политика конфиденциальности</a>
    </div>
</footer>
