<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-intro">
            <a class="footer-brand" href="{{ route('home') }}" aria-label="МарМелАма — на главную">
                <span class="footer-brand-mark" aria-hidden="true">
                    <img src="{{ asset('images/brand/logo.png') }}" alt="" width="196" height="196">
                </span>
                <span class="footer-brand-copy">
                    <span class="brand-name">{{ $site['name'] }}</span>
                    <span class="brand-subtitle">{{ $site['description'] }}</span>
                </span>
            </a>
            <p class="footer-text">Бурманские котята в Омске. Помогаем выбрать малыша и сопровождаем владельцев после переезда.</p>
        </div>

        <nav class="footer-navigation" aria-label="Навигация в подвале">
            <div class="footer-column">
                <h3>Разделы</h3>
                <a href="{{ route('home') }}">Главная</a>
                <a href="{{ route('content.show', 'about') }}">О питомнике</a>
                <a href="{{ route('litters.index') }}">Пометы</a>
                <a href="{{ route('reviews') }}">Отзывы</a>
                <a href="{{ route('delivery') }}">Доставка</a>
                <a href="{{ route('kittens.index') }}">Продажа котят</a>
            </div>
            <div class="footer-column">
                <h3>Питомник</h3>
                <a href="{{ route('parents.index', '1') }}">Наши коты</a>
                <a href="{{ route('parents.index', '0') }}">Наши кошки</a>
                <a href="{{ route('gallery') }}">Галерея</a>
                <a href="{{ route('archive') }}">Архив котят</a>
                <a href="{{ route('contacts') }}">Контакты</a>
            </div>
        </nav>

        <div class="footer-contact">
            <h3>Всегда на связи</h3>
            <p>Позвоните или выберите удобный мессенджер — ответим на вопросы о котятах и питомнике.</p>
            <div class="footer-contact-links">
                <a class="footer-phone" href="tel:{{ $site['phone_href'] }}" data-analytics-goal="phone_click">{{ $site['phone'] }}</a>
                <a class="footer-email" href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
            </div>
            <div class="footer-messengers" role="group" aria-label="Мессенджеры питомника">
                <a class="footer-messenger" href="{{ $site['max'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в MAX" data-footer-messenger="max" data-analytics-goal="max_click">
                    <img src="{{ asset('images/messengers/max.png') }}" alt="" width="192" height="192" aria-hidden="true">
                    <span>MAX</span>
                </a>
                <a class="footer-messenger" href="{{ $site['telegram'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в Telegram" data-footer-messenger="telegram" data-analytics-goal="telegram_click">
                    <img src="{{ asset('images/messengers/telegram.svg') }}" alt="" width="496" height="496" aria-hidden="true">
                    <span>Telegram</span>
                </a>
                <a class="footer-messenger" href="{{ $site['whatsapp'] }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в WhatsApp" data-footer-messenger="whatsapp" data-analytics-goal="whatsapp_click">
                    <img src="{{ asset('images/messengers/whatsapp.svg') }}" alt="" width="448" height="448" aria-hidden="true">
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>© {{ date('Y') }} МарМелАма</span>
        <a class="footer-developer" href="https://max.ru/u/f9LHodD0cOJDqN_zS_D2YQqZoU_FK0wjb0ejeJUjZlesoCXwVEDair7LHHg" target="_blank" rel="noopener noreferrer">
            Разработаем сайт для вас
            <span aria-hidden="true">↗</span>
        </a>
        <div class="footer-legal">
            @if((int) config('services.yandex_metrika.id') > 0)
                <button class="footer-cookie-settings" type="button" data-analytics-consent-settings>Настройки cookie</button>
            @endif
            <a class="footer-politics" href="{{ route('politics') }}">Политика конфиденциальности</a>
        </div>
    </div>
</footer>
