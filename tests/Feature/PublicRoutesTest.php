<?php

namespace Tests\Feature;

use App\Models\ContentPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalogues_and_delivery_use_english_urls(): void
    {
        $this->get('/kittens')->assertOk();
        $this->get('/litters')->assertOk();
        $this->get('/delivery')->assertOk();

        $this->assertSame(url('/kittens'), route('kittens.index'));
        $this->assertSame(url('/litters'), route('litters.index'));
        $this->assertSame(url('/delivery'), route('delivery'));
    }

    public function test_old_public_urls_redirect_permanently(): void
    {
        $this->get('/pets?sex=female')
            ->assertStatus(301)
            ->assertRedirect('/kittens?sex=female');
        $this->get('/pets/example')
            ->assertStatus(301)
            ->assertRedirect('/kittens/example');
        $this->get('/pomet?status=archive')
            ->assertStatus(301)
            ->assertRedirect('/litters?status=archive');
        $this->get('/pomet/example')
            ->assertStatus(301)
            ->assertRedirect('/litters/example');
        $this->get('/dostavka')
            ->assertStatus(301)
            ->assertRedirect('/delivery');
    }

    public function test_archive_redirects_to_the_sold_kittens_filter(): void
    {
        $this->get('/archive?sex=female')
            ->assertStatus(301)
            ->assertRedirect('/kittens?sex=female&status=sold');
    }

    public function test_only_approved_content_page_slugs_are_routable(): void
    {
        ContentPage::query()->create([
            'title' => 'О питомнике',
            'slug' => 'about',
            'content' => '<p>О питомнике</p>',
            'is_visible' => true,
        ]);
        ContentPage::query()->create([
            'title' => 'Случайная страница',
            'slug' => 'unexpected-page',
            'content' => '<p>Не должна иметь публичный маршрут</p>',
            'is_visible' => true,
        ]);

        $this->get('/about')->assertOk();
        $this->get('/unexpected-page')->assertNotFound();
    }

    public function test_removed_article_routes_are_not_public(): void
    {
        $this->get('/articles')->assertNotFound();
        $this->get('/article/example')->assertNotFound();
    }
}
