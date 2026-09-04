<?php

namespace Tests\Feature;

use App\Models\BreedingCat;
use App\Models\Kitten;
use App\Models\Litter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LitterCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_litter_without_kittens_is_not_treated_as_archive(): void
    {
        $litter = $this->createLitter('Помёт П', 'litter-p', '2026-08-13');

        $this->get(route('litters.index'))
            ->assertOk()
            ->assertSeeText($litter->title)
            ->assertSee('<span class="status available">Есть свободные</span>', false)
            ->assertDontSee('<span class="status sold">Архив</span>', false);

        $this->get(route('litters.index', ['status' => 'available']))
            ->assertOk()
            ->assertSeeText($litter->title);

        $this->get(route('litters.index', ['status' => 'archive']))
            ->assertOk()
            ->assertDontSeeText($litter->title);
    }

    public function test_status_filters_and_cards_follow_the_litter_status(): void
    {
        $available = $this->createLitter('Свободный помёт', 'available-litter', '2026-05-10');
        $archive = $this->createLitter('Завершённый помёт', 'archive-litter', '2025-05-10', 'archive');
        $planned = $this->createLitter('Будущий помёт', 'planned-litter', null, 'planned');
        $availableWithHiddenKitten = $this->createLitter('Скрытая карточка котёнка', 'hidden-availability', '2024-05-10');

        Kitten::create([
            'litter_id' => $available->id,
            'name' => 'Свободный котёнок',
            'slug' => 'available-kitten',
            'status' => 'available',
            'is_visible' => true,
        ]);
        Kitten::create([
            'litter_id' => $archive->id,
            'name' => 'Котёнок дома',
            'slug' => 'sold-kitten',
            'status' => 'sold',
            'is_visible' => true,
        ]);
        Kitten::create([
            'litter_id' => $availableWithHiddenKitten->id,
            'name' => 'Скрытый котёнок',
            'slug' => 'hidden-kitten',
            'status' => 'available',
            'is_visible' => false,
        ]);

        $this->get(route('litters.index', ['status' => 'available']))
            ->assertOk()
            ->assertSeeText($available->title)
            ->assertSeeText($availableWithHiddenKitten->title)
            ->assertDontSeeText($archive->title)
            ->assertDontSeeText($planned->title)
            ->assertSee('<span class="status available">Есть свободные</span>', false);

        $this->get(route('litters.index', ['status' => 'archive']))
            ->assertOk()
            ->assertSeeText($archive->title)
            ->assertDontSeeText($available->title)
            ->assertDontSeeText($availableWithHiddenKitten->title)
            ->assertDontSeeText($planned->title);
    }

    public function test_year_parent_search_and_sort_can_be_combined(): void
    {
        $parent = BreedingCat::create([
            'name' => 'Grafian Test',
            'slug' => 'grafian-test',
            'sex' => 'male',
            'is_visible' => true,
        ]);
        $otherParent = BreedingCat::create([
            'name' => 'Other Parent',
            'slug' => 'other-parent',
            'sex' => 'male',
            'is_visible' => true,
        ]);

        $matching = $this->createLitter('Особенный помёт', 'special-litter', '2025-03-10', fatherId: $parent->id);
        $wrongYear = $this->createLitter('Особенный новый помёт', 'special-new-litter', '2026-03-10', fatherId: $parent->id);
        $wrongParent = $this->createLitter('Особенный другой помёт', 'special-other-litter', '2025-03-10', fatherId: $otherParent->id);

        $response = $this->get(route('litters.index', [
            'q' => 'Особенный',
            'year' => 2025,
            'parent' => $parent->id,
            'sort' => 'oldest',
        ]));

        $response->assertOk();
        $response->assertSeeText($matching->title);
        $response->assertDontSeeText($wrongYear->title);
        $response->assertDontSeeText($wrongParent->title);
        $response->assertSee('value="2025" selected', false);
        $response->assertSee('value="'.$parent->id.'" selected', false);
        $response->assertSee('value="oldest" selected', false);
    }

    public function test_catalog_has_six_cards_per_page_and_keeps_filters_in_pagination(): void
    {
        foreach (range(1, 7) as $index) {
            $this->createLitter(
                'Архивный помёт '.$index,
                'archive-litter-'.$index,
                '2026-01-'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                'archive',
            );
        }

        $firstPage = $this->get(route('litters.index', [
            'status' => 'archive',
            'year' => 2026,
        ]));

        $firstPage->assertOk();
        $this->assertSame(6, substr_count($firstPage->getContent(), 'class="litter-card listing-card card"'));
        $firstPage->assertSee('page=2', false);
        $firstPage->assertSee('status=archive', false);
        $firstPage->assertSee('year=2026', false);
        $firstPage->assertSee('#litter-results', false);

        $secondPage = $this->get(route('litters.index', [
            'status' => 'archive',
            'year' => 2026,
            'page' => 2,
        ]));

        $secondPage->assertOk();
        $this->assertSame(1, substr_count($secondPage->getContent(), 'class="litter-card listing-card card"'));
        $secondPage->assertSeeText('Показаны 7–7');
    }

    private function createLitter(
        string $title,
        string $slug,
        ?string $bornOn,
        string $status = 'available',
        ?int $fatherId = null,
    ): Litter {
        return Litter::create([
            'title' => $title,
            'slug' => $slug,
            'born_on' => $bornOn,
            'father_id' => $fatherId,
            'status' => $status,
            'is_visible' => true,
        ]);
    }
}
