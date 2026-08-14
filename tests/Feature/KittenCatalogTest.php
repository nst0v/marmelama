<?php

namespace Tests\Feature;

use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KittenCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_card_shows_clear_sale_information(): void
    {
        $kitten = Kitten::query()->create([
            'name' => 'девочка Лея',
            'slug' => 'leya',
            'sex' => 'female',
            'color' => 'Соболиный',
            'born_on' => '2026-05-10',
            'status' => 'available',
            'price' => 45000,
            'is_visible' => true,
        ]);

        $response = $this->get(route('kittens.index'));

        $response->assertOk();
        $response->assertSee('class="kitten-card"', false);
        $response->assertSeeText('Ищет семью');
        $response->assertSeeText('Лея');
        $response->assertDontSeeText('девочка Лея');
        $response->assertSeeText('Девочка');
        $response->assertSeeText('Окрас: соболиный');
        $response->assertSee('kitten-card-sex--female', false);
        $response->assertSee('kitten-card-color-swatch--sable', false);
        $response->assertSee('<circle cx="10" cy="7.25" r="4.75"></circle>', false);
        $response->assertSeeText('Родилась 10 мая 2026');
        $response->assertDontSeeText('45 000 ₽');
        $response->assertSeeText('Смотреть анкету');
        $response->assertSee(route('kittens.show', $kitten->slug), false);
        $response->assertSeeText('Ищут семью');
        $response->assertDontSee('kitten-filter-hot', false);
        $response->assertDontSee('kitten-filter-fire', false);
    }

    public function test_catalog_combines_status_sex_and_normalized_color_filters(): void
    {
        $match = Kitten::query()->create([
            'name' => 'Лея',
            'slug' => 'leya-filter',
            'sex' => 'female',
            'color' => 'Шоколадный (chocolate)',
            'status' => 'available',
            'is_visible' => true,
        ]);
        $wrongSex = Kitten::query()->create([
            'name' => 'Марс',
            'slug' => 'mars-filter',
            'sex' => 'male',
            'color' => 'шоколадный',
            'status' => 'available',
            'is_visible' => true,
        ]);
        $wrongColor = Kitten::query()->create([
            'name' => 'Сима',
            'slug' => 'sima-filter',
            'sex' => 'female',
            'color' => 'соболиный',
            'status' => 'available',
            'is_visible' => true,
        ]);
        $wrongStatus = Kitten::query()->create([
            'name' => 'Нора',
            'slug' => 'nora-filter',
            'sex' => 'female',
            'color' => 'шоколадный',
            'status' => 'sold',
            'is_visible' => true,
        ]);

        $response = $this->get(route('kittens.index', [
            'status' => 'available',
            'sex' => 'female',
            'color' => 'chocolate',
        ]));

        $response->assertOk();
        $response->assertSeeText($match->name);
        $response->assertDontSeeText($wrongSex->name);
        $response->assertDontSeeText($wrongColor->name);
        $response->assertDontSeeText($wrongStatus->name);
        $response->assertSee('value="female" selected', false);
        $response->assertSee('value="chocolate" selected', false);
        $response->assertSeeText('Найдено: 1');
    }

    public function test_catalog_opens_with_available_kittens_and_keeps_legacy_filter_links_working(): void
    {
        $available = Kitten::query()->create([
            'name' => 'Свободная Лея',
            'slug' => 'available-leya',
            'sex' => 'female',
            'status' => 'available',
            'is_visible' => true,
        ]);
        $sold = Kitten::query()->create([
            'name' => 'Домашняя Нора',
            'slug' => 'sold-nora',
            'sex' => 'female',
            'status' => 'sold',
            'is_visible' => true,
        ]);

        $this->get(route('kittens.index'))
            ->assertOk()
            ->assertSeeText($available->name)
            ->assertDontSeeText($sold->name)
            ->assertSeeText('Уже нашли хозяев');

        $this->get(route('kittens.index', ['filter' => 'female']))
            ->assertOk()
            ->assertSeeText($available->name)
            ->assertSeeText($sold->name);
    }

    public function test_profile_actions_follow_the_kittens_actual_status(): void
    {
        $available = Kitten::query()->create([
            'name' => 'Лея',
            'slug' => 'leya',
            'sex' => 'female',
            'color' => 'Соболиный',
            'status' => 'available',
            'is_visible' => true,
        ]);
        $sold = Kitten::query()->create([
            'name' => 'Марс',
            'slug' => 'mars',
            'sex' => 'male',
            'status' => 'sold',
            'is_visible' => true,
        ]);

        $this->get(route('kittens.show', $available->slug))
            ->assertOk()
            ->assertSeeText('Ищет семью')
            ->assertSeeText('Обсудить')
            ->assertSee('kitten-profile-attributes', false)
            ->assertSee('kitten-card-color-swatch--sable', false)
            ->assertDontSeeText('Уже нашли хозяев');

        $this->get(route('kittens.show', $sold->slug))
            ->assertOk()
            ->assertSeeText('Уже нашли хозяев')
            ->assertSeeText('Смотреть свободных котят')
            ->assertDontSeeText('Обсудить');
    }
}
