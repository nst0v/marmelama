<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use App\Models\Kitten;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_contains_logo_and_messenger_links(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('images/brand/logo.png');
        $response->assertSee('data-messenger="max"', false);
        $response->assertSee('data-messenger="telegram"', false);
        $response->assertSee('data-messenger="whatsapp"', false);
        $response->assertSee('https://max.ru/u/f9LHodD0cOLonF7huHOgSikdjxmHiKhHhZntjhsg1BAWXZG3I4hzBkl4RtY', false);
        $response->assertSee('https://t.me/@MarMelAma_Omsk', false);
        $response->assertSee('https://wa.me/+79136453118', false);
    }

    public function test_header_contains_only_requested_sections_in_requested_order(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        preg_match('/<nav class="site-nav"[^>]*>(.*?)<\/nav>/s', $response->getContent(), $nav);
        preg_match_all('/<a[^>]*>(.*?)<\/a>/s', $nav[1] ?? '', $links);

        $this->assertSame([
            'Главная',
            'Наши котята',
            'О питомнике',
            'Пометы',
            'Отзывы',
            'Доставка',
        ], array_map(fn (string $label): string => trim(strip_tags($label)), $links[1]));
        $this->assertStringContainsString('class="site-nav-kittens"', $nav[1] ?? '');
        $this->assertStringNotContainsString('Контакты', $nav[1] ?? '');
        $response->assertDontSee('>Производители<', false);
    }

    public function test_header_marks_the_current_section_as_active(): void
    {
        ContentPage::create([
            'title' => 'О питомнике',
            'slug' => 'about',
            'content' => '<p>О питомнике</p>',
            'is_visible' => true,
        ]);

        foreach ([
            '/' => 'Главная',
            '/pets' => 'Наши котята',
            '/about' => 'О питомнике',
            '/pomet' => 'Пометы',
            '/reviews' => 'Отзывы',
            '/dostavka' => 'Доставка',
        ] as $url => $label) {
            $response = $this->get($url);

            $response->assertOk();
            preg_match('/<nav class="site-nav"[^>]*>(.*?)<\/nav>/s', $response->getContent(), $nav);
            $navHtml = $nav[1] ?? '';
            $this->assertMatchesRegularExpression(
                '/<a[^>]*class="[^"]*is-active[^"]*"[^>]*aria-current="page"[^>]*>'.preg_quote($label, '/').'<\/a>/',
                $navHtml
            );
            $this->assertSame(1, substr_count($navHtml, 'aria-current="page"'));
        }
    }

    public function test_breeding_cats_module_is_available_without_a_header_link(): void
    {
        $this->assertTrue(Schema::hasTable('breeding_cats'));
        $this->assertTrue(Schema::hasColumn('litters', 'father_id'));
        $this->assertTrue(Schema::hasColumn('litters', 'mother_id'));

        $this->get('/parents/1')->assertOk();
        $this->get('/parents/0')->assertOk();
        $this->get('/parents/0/example')->assertNotFound();
    }

    public function test_homepage_has_clear_links_to_cats_and_kittens_after_the_slider(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('home-paths-grid', false);
        $response->assertSeeInOrder([
            'images/home/tab3.png',
            'Продажа котят',
            'images/home/tab2.png',
            'Наши коты',
            'images/home/tab1.png',
            'Наши кошки',
        ]);

        $this->assertFileExists(public_path('images/home/tab1.png'));
        $this->assertFileExists(public_path('images/home/tab2.png'));
        $this->assertFileExists(public_path('images/home/tab3.png'));
    }

    public function test_homepage_offers_the_full_catalog_after_its_mobile_kitten_preview(): void
    {
        foreach (range(1, 3) as $number) {
            Kitten::create([
                'name' => 'Котёнок '.$number,
                'slug' => 'available-kitten-'.$number,
                'sex' => $number % 2 === 0 ? 'female' : 'male',
                'status' => 'available',
                'is_visible' => true,
            ]);
        }

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('class="grid grid-3 home-kittens-grid"', false);
        $response->assertSee('class="home-catalog-more"', false);
        $response->assertSeeText('Смотреть всех котят');
        $response->assertSee(route('kittens.index', ['status' => 'available']), false);
    }

    public function test_homepage_reviews_have_a_mobile_slider_and_full_list_link(): void
    {
        foreach (range(1, 3) as $number) {
            Review::create([
                'author_name' => 'Владелец '.$number,
                'body' => 'Отзыв о котёнке '.$number,
                'is_visible' => true,
            ]);
        }

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('class="grid grid-3 home-reviews-slider" data-review-slider data-review-start="1"', false);
        $this->assertSame(3, substr_count($response->getContent(), 'data-review-dot'));
        $this->assertMatchesRegularExpression(
            '/data-review-dot\s+aria-label="Показать отзыв 2"\s+aria-current="true"/',
            $response->getContent()
        );
        $response->assertSeeText('Читать все отзывы');
        $response->assertSee(route('reviews'), false);
    }

    public function test_welcome_block_follows_homepage_navigation_cards(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('class="welcome-section"', false);
        $response->assertSee('images/home/welcome.jpg', false);
        $response->assertSee('Рады знакомству с вами');
        $response->assertSee('class="lead welcome-lead"', false);
        $response->assertSee('Подробнее о питомнике');
        $this->assertFileExists(public_path('images/home/welcome.jpg'));

        $html = $response->getContent();
        $cardsPosition = strpos($html, 'class="home-paths"');
        $welcomePosition = strpos($html, 'class="welcome-section"');
        $catalogPosition = strpos($html, 'id="available"');

        $this->assertIsInt($cardsPosition);
        $this->assertIsInt($welcomePosition);
        $this->assertIsInt($catalogPosition);
        $this->assertLessThan($welcomePosition, $cardsPosition);
        $this->assertLessThan($catalogPosition, $welcomePosition);
    }

    public function test_homepage_omits_removed_promotional_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('Почему выбирают МарМелАма');
        $response->assertDontSee('Как купить котенка');
        $response->assertDontSee('Наши производители');
        $response->assertDontSee('id="how-to-buy"', false);
        $response->assertDontSee('#how-to-buy', false);
    }

    public function test_homepage_combines_delivery_and_contact_into_one_module(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('class="container home-service-module"', false);
        $response->assertSee('Подробнее о доставке');
        $response->assertSee('Поможем выбрать вашего котёнка');
        $response->assertDontSee('Уточнить маршрут');
        $response->assertDontSee('<p class="eyebrow">Доставка</p>', false);
        $response->assertDontSee('<p class="eyebrow">Связаться</p>', false);
        $response->assertDontSee('class="container cta-card"', false);
    }

    public function test_contact_cta_remains_on_inner_pages(): void
    {
        $response = $this->get(route('reviews'));

        $response->assertOk();
        $response->assertSee('class="container cta-card"', false);
        $response->assertSee('Хотите подобрать бурманского котенка?');
    }

    public function test_footer_uses_current_navigation_messengers_and_developer_link(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        preg_match('/<footer class="site-footer">(.*?)<\/footer>/s', $response->getContent(), $footer);
        $footerHtml = $footer[1] ?? '';

        $this->assertStringContainsString('images/brand/logo.png', $footerHtml);
        $this->assertStringContainsString('data-footer-messenger="max"', $footerHtml);
        $this->assertStringContainsString('data-footer-messenger="telegram"', $footerHtml);
        $this->assertStringContainsString('data-footer-messenger="whatsapp"', $footerHtml);
        $this->assertStringContainsString('images/messengers/max.png', $footerHtml);
        $this->assertStringContainsString('images/messengers/telegram.svg', $footerHtml);
        $this->assertStringContainsString('images/messengers/whatsapp.svg', $footerHtml);
        $this->assertStringContainsString('Разработаем сайт для вас', $footerHtml);
        $this->assertStringContainsString('https://max.ru/u/f9LHodD0cOJDqN_zS_D2YQqZoU_FK0wjb0ejeJUjZlesoCXwVEDair7LHHg', $footerHtml);
        $this->assertStringNotContainsString('Instagram', $footerHtml);
        $this->assertStringNotContainsString('Покупателям', $footerHtml);
        $this->assertStringNotContainsString('#how-to-buy', $footerHtml);
    }
}
