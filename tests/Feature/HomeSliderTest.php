<?php

namespace Tests\Feature;

use App\Models\Slide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeSliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_hero_uses_visible_home_slides_with_existing_images_only(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/slides/visible-a.jpg', 'slide a');
        Storage::disk('public')->put('media/slides/visible-b.jpg', 'slide b');
        Storage::disk('public')->put('media/slides/hidden.jpg', 'hidden slide');
        Storage::disk('public')->put('media/slides/other-page.jpg', 'other page slide');

        Slide::create([
            'title' => 'Первый видимый слайд',
            'placement' => 'home',
            'caption' => 'Главный экран питомника',
            'alt' => 'Первый котенок',
            'image' => 'media/slides/visible-a.jpg',
            'sort_order' => 20,
            'is_visible' => true,
        ]);

        Slide::create([
            'title' => 'Второй видимый слайд',
            'placement' => 'home',
            'image' => 'media/slides/visible-b.jpg',
            'sort_order' => 10,
            'is_visible' => true,
        ]);

        Slide::create([
            'title' => 'Скрытый слайд',
            'placement' => 'home',
            'image' => 'media/slides/hidden.jpg',
            'sort_order' => 30,
            'is_visible' => false,
        ]);

        Slide::create([
            'title' => 'Слайд другой страницы',
            'placement' => 'page-1',
            'image' => 'media/slides/other-page.jpg',
            'sort_order' => 40,
            'is_visible' => true,
        ]);

        Slide::create([
            'title' => 'Слайд без файла',
            'placement' => 'home',
            'image' => 'media/slides/missing.jpg',
            'sort_order' => 50,
            'is_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-hero-slider', false);
        $response->assertSee('visible-a.jpg');
        $response->assertSee('visible-b.jpg');
        $response->assertSee('data-hero-dot', false);
        $response->assertDontSee('data-hero-prev', false);
        $response->assertDontSee('data-hero-next', false);
        $response->assertDontSee('Главный экран питомника');
        $response->assertDontSee('Скрытый слайд');
        $response->assertDontSee('Слайд другой страницы');
        $response->assertDontSee('Слайд без файла');
    }

    public function test_slide_image_lifecycle_supports_admin_create_disable_replace_and_delete(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/slides/original.jpg', 'original');
        Storage::disk('public')->put('media/slides/replacement.jpg', 'replacement');

        $slide = Slide::create([
            'title' => 'Админский слайд',
            'placement' => 'home',
            'image' => 'media/slides/original.jpg',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->assertDatabaseHas('slides', [
            'id' => $slide->id,
            'is_visible' => true,
        ]);

        $slide->update(['is_visible' => false]);

        $this->assertFalse($slide->refresh()->is_visible);

        $slide->update(['image' => 'media/slides/replacement.jpg']);

        Storage::disk('public')->assertMissing('media/slides/original.jpg');
        Storage::disk('public')->assertExists('media/slides/replacement.jpg');

        $slide->delete();

        $this->assertDatabaseMissing('slides', ['id' => $slide->id]);
        Storage::disk('public')->assertMissing('media/slides/replacement.jpg');
    }
}
