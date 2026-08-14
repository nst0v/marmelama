<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadableListsTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_page_has_one_h1_when_admin_content_repeats_the_title(): void
    {
        ContentPage::create([
            'title' => 'О питомнике',
            'slug' => 'about',
            'content' => '<h1 style="text-align: center">О питомнике</h1><p>Текст из админки.</p>',
            'is_visible' => true,
        ]);

        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('<h1>О питомнике</h1>', false);
        $response->assertSee('Текст из админки.');
        $response->assertSee('class="container page-heading"', false);
        $response->assertSee('class="container content-page-layout"', false);
        $response->assertDontSee('class="container narrow page-heading"', false);
        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_delivery_page_is_one_clear_layout_without_repeated_card_sections(): void
    {
        ContentPage::create([
            'title' => 'Доставка',
            'slug' => 'dostavka',
            'content' => '<h1>Доставка котят по России</h1><p>Текст доставки из админки.</p>',
            'is_visible' => true,
        ]);

        $response = $this->get(route('delivery'));

        $response->assertOk();
        $response->assertSee('<h1>Доставка котят</h1>', false);
        $response->assertSee('Текст доставки из админки.');
        $response->assertSee('class="container delivery-layout"', false);
        $response->assertSeeText('Железная дорога');
        $response->assertSeeText('Что важно знать');
        $response->assertSeeText('Уточнить маршрут');
        $response->assertSee('class="container page-heading delivery-heading"', false);
        $response->assertDontSee('class="container narrow page-heading delivery-heading"', false);
        $response->assertDontSee('class="delivery-card', false);
        $response->assertDontSee('class="container cta-card"', false);
        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_litter_card_uses_admin_status_and_manual_parents_without_hidden_kittens(): void
    {
        $litter = Litter::create([
            'title' => '06.03.2026 помет N 2 котёнка',
            'slug' => 'litter-n',
            'letter' => 'N',
            'born_on' => '2026-03-06',
            'father_name' => 'Отец вручную',
            'mother_name' => 'Мама вручную',
            'status' => 'archive',
            'is_visible' => true,
        ]);

        Kitten::create([
            'litter_id' => $litter->id,
            'name' => 'Видимый котёнок',
            'slug' => 'visible-kitten',
            'sex' => 'male',
            'status' => 'sold',
            'is_visible' => true,
        ]);

        Kitten::create([
            'litter_id' => $litter->id,
            'name' => 'Скрытый котёнок',
            'slug' => 'hidden-kitten',
            'sex' => 'female',
            'status' => 'sold',
            'is_visible' => false,
        ]);

        $response = $this->get('/litters');

        $response->assertOk();
        $response->assertSee('Помёт N');
        $response->assertSee('Архив');
        $response->assertSee('Отец вручную');
        $response->assertSee('Мама вручную');
        $response->assertSee('1 всего');
        $response->assertDontSee('06.03.2026 помет N 2 котёнка');
    }

    public function test_review_list_keeps_the_full_admin_text(): void
    {
        Review::create([
            'author_name' => 'Мария',
            'body' => str_repeat('Подробная история о котёнке. ', 20).'Конец отзыва.',
            'reviewed_at' => '2026-07-14',
            'is_visible' => true,
        ]);

        $response = $this->get('/reviews');

        $response->assertOk();
        $response->assertSee('Конец отзыва.');
        $response->assertDontSee('Конец отзыва...');
    }
}
