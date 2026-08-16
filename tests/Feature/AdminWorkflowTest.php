<?php

namespace Tests\Feature;

use App\Filament\Resources\BreedingCats\Pages\CreateBreedingCat;
use App\Filament\Resources\BreedingCats\Pages\ListBreedingCats;
use App\Filament\Resources\BreedingCats\Pages\ViewBreedingCat;
use App\Filament\Resources\ContactRequests\Pages\EditContactRequest;
use App\Filament\Resources\ContactRequests\Pages\ListContactRequests;
use App\Filament\Resources\ContactRequests\Pages\ViewContactRequest;
use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Kittens\Pages\CreateKitten;
use App\Filament\Resources\Kittens\Pages\EditKitten;
use App\Filament\Resources\Kittens\Pages\ListKittens;
use App\Filament\Resources\Kittens\Pages\ViewKitten;
use App\Filament\Resources\Litters\Pages\CreateLitter;
use App\Filament\Resources\Litters\Pages\EditLitter;
use App\Filament\Resources\Litters\Pages\ListLitters;
use App\Filament\Resources\Litters\Pages\ViewLitter;
use App\Filament\Resources\Litters\RelationManagers\KittensRelationManager;
use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Pages\ViewReview;
use App\Filament\Resources\Slides\Pages\CreateSlide;
use App\Filament\Resources\Slides\Pages\ListSlides;
use App\Filament\Widgets\AdminQuickActions;
use App\Models\BreedingCat;
use App\Models\ContactRequest;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Review;
use App\Models\Slide;
use App\Models\User;
use Filament\Actions\CreateAction as FilamentCreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_owner_can_create_a_litter_without_entering_a_slug_or_technical_fields(): void
    {
        Livewire::test(CreateLitter::class)
            ->fillForm([
                'letter' => 'N',
                'born_on' => '2026-08-14',
                'title' => null,
                'status' => 'planned',
                'is_visible' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $litter = Litter::query()->sole();

        $this->assertSame('Помёт N — 14.08.2026', $litter->title);
        $this->assertNotSame('', $litter->slug);
        $this->assertFalse($litter->is_visible);
    }

    public function test_owner_can_create_kittens_with_unique_automatic_slugs(): void
    {
        foreach (range(1, 2) as $attempt) {
            Livewire::test(CreateKitten::class)
                ->fillForm([
                    'name' => 'Лея',
                    'sex' => 'female',
                    'status' => 'available',
                    'is_visible' => false,
                ])
                ->call('create')
                ->assertHasNoFormErrors();
        }

        $this->assertSame(
            ['leia', 'leia-2'],
            Kitten::query()->orderBy('id')->pluck('slug')->all(),
        );
        $this->assertSame([false, false], Kitten::query()->orderBy('id')->pluck('is_visible')->all());
    }

    public function test_owner_can_create_a_home_slide_without_technical_placement_or_sort_fields(): void
    {
        Storage::fake('public');

        Livewire::test(CreateSlide::class)
            ->fillForm([
                'image' => UploadedFile::fake()->image('slide.jpg', 1600, 900),
                'title' => 'Летний слайд',
                'alt' => 'Бурманский котёнок на светлом фоне',
                'is_visible' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $slide = Slide::query()->sole();

        $this->assertSame('home', $slide->placement);
        $this->assertSame(0, $slide->sort_order);
        $this->assertFalse($slide->is_visible);
        Storage::disk('public')->assertExists($slide->image);
    }

    public function test_mobile_friendly_kitten_workflow_links_to_edit_and_allows_permanent_deletion(): void
    {
        $kitten = Kitten::query()->create([
            'name' => 'Котёнок для удаления',
            'slug' => 'kitten-for-admin-delete',
            'sex' => 'female',
            'status' => 'available',
            'is_visible' => false,
        ]);

        $list = Livewire::test(ListKittens::class)
            ->assertOk()
            ->assertSee('Нажмите на строку котёнка, чтобы сразу изменить информацию.')
            ->assertTableActionExists('edit', record: $kitten)
            ->assertTableActionExists('delete', record: $kitten);

        $this->assertSame(
            KittenResource::getUrl('edit', ['record' => $kitten]),
            $list->instance()->getTable()->getRecordUrl($kitten),
        );

        $edit = Livewire::test(EditKitten::class, ['record' => $kitten->getRouteKey()])
            ->assertOk()
            ->assertSee('Сохранить изменения')
            ->assertActionHasLabel('delete', 'Удалить котёнка');

        $this->assertTrue($edit->instance()->areFormActionsSticky());

        $edit->callAction('delete');

        $this->assertDatabaseMissing('kittens', ['id' => $kitten->id]);
    }

    public function test_dashboard_shortcuts_open_the_five_requested_resource_lists(): void
    {
        Livewire::test(AdminQuickActions::class)
            ->assertOk()
            ->assertSee('Разделы')
            ->assertSee('Котята')
            ->assertSee('Помёты')
            ->assertSee('Производители')
            ->assertSee('Заявки')
            ->assertSee('Слайды')
            ->assertDontSee('Добавить котёнка')
            ->assertDontSee('Создать помёт')
            ->assertDontSee('Добавить слайд');
    }

    public function test_redesigned_operational_lists_and_detail_pages_render(): void
    {
        $parent = BreedingCat::query()->create([
            'name' => 'Марс',
            'slug' => 'mars-admin-view',
            'sex' => 'male',
            'is_active' => true,
            'is_visible' => true,
        ]);
        $litter = Litter::query()->create([
            'title' => 'Помёт N',
            'slug' => 'litter-n-admin-view',
            'letter' => 'N',
            'father_id' => $parent->id,
            'status' => 'available',
            'is_visible' => true,
        ]);
        $kitten = Kitten::query()->create([
            'litter_id' => $litter->id,
            'name' => 'Лея',
            'slug' => 'leya-admin-view',
            'sex' => 'female',
            'status' => 'available',
            'price' => 45000,
            'is_visible' => true,
        ]);
        $review = Review::query()->create([
            'author_name' => 'Анна',
            'body' => 'Спасибо питомнику!',
            'response' => 'Спасибо за доверие!',
            'phone' => '+7 (999) 111-22-33',
            'email' => 'anna@example.test',
            'is_visible' => true,
        ]);
        $contactRequest = ContactRequest::query()->create([
            'kitten_id' => $kitten->id,
            'name' => 'Мария',
            'phone' => '+7 (999) 222-33-44',
            'email' => 'maria@example.test',
            'message' => 'Хочу узнать подробнее о котёнке.',
            'status' => 'new',
            'mail_status' => 'sent',
            'mail_sent_at' => now(),
        ]);

        Livewire::test(ListKittens::class)->assertOk();
        Livewire::test(ViewKitten::class, ['record' => $kitten->getRouteKey()])->assertOk();
        Livewire::test(ListLitters::class)->assertOk();
        Livewire::test(ViewLitter::class, ['record' => $litter->getRouteKey()])->assertOk();
        Livewire::test(ListBreedingCats::class)->assertOk();
        Livewire::test(ViewBreedingCat::class, ['record' => $parent->getRouteKey()])->assertOk();
        Livewire::test(ListReviews::class)->assertOk();
        Livewire::test(EditReview::class, ['record' => $review->getRouteKey()])
            ->assertOk()
            ->assertSee('Отзыв и ответ')
            ->assertSee('Автор')
            ->assertSee('Публикация и фото');
        Livewire::test(ViewReview::class, ['record' => $review->getRouteKey()])
            ->assertOk()
            ->assertSee('Отзыв клиента')
            ->assertSee('Опубликован')
            ->assertSee('Ответ добавлен')
            ->assertSee('Контакты автора')
            ->assertSee('tel:+79991112233', false)
            ->assertSee('mailto:anna@example.test', false);
        Livewire::test(ListContactRequests::class)
            ->assertOk()
            ->assertSee('Мария')
            ->assertSee('Лея');
        Livewire::test(ViewContactRequest::class, ['record' => $contactRequest->getRouteKey()])
            ->assertOk()
            ->assertSee('Хочу узнать подробнее о котёнке.')
            ->assertSee('tel:+79992223344', false)
            ->assertSee('mailto:maria@example.test', false)
            ->assertSee('Письмо отправлено');
        Livewire::test(EditContactRequest::class, ['record' => $contactRequest->getRouteKey()])
            ->assertOk()
            ->fillForm([
                'status' => 'in_progress',
                'internal_notes' => 'Перезвонить вечером.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('contact_requests', [
            'id' => $contactRequest->id,
            'status' => 'in_progress',
            'internal_notes' => 'Перезвонить вечером.',
        ]);
        Livewire::test(ListSlides::class)->assertOk();
    }

    public function test_review_detail_hides_empty_photo_and_contacts(): void
    {
        $review = Review::query()->create([
            'author_name' => 'Без контактов',
            'body' => 'Текст отзыва',
            'is_visible' => false,
        ]);

        Livewire::test(ViewReview::class, ['record' => $review->getRouteKey()])
            ->assertOk()
            ->assertSee('Черновик')
            ->assertSee('Без ответа')
            ->assertDontSee('Фото к отзыву')
            ->assertDontSee('Контакты автора');
    }

    public function test_create_another_button_is_disabled_on_every_create_page(): void
    {
        foreach ([
            CreateKitten::class,
            CreateLitter::class,
            CreateSlide::class,
            CreateReview::class,
            CreateBreedingCat::class,
        ] as $page) {
            $component = Livewire::test($page)
                ->assertOk()
                ->assertDontSee('Создать и Создать еще');

            $this->assertFalse($component->instance()->canCreateAnother());
        }
    }

    public function test_create_another_button_is_disabled_when_adding_a_kitten_from_a_litter(): void
    {
        $litter = Litter::query()->create([
            'title' => 'Помёт Q',
            'slug' => 'litter-q-create-action',
            'letter' => 'Q',
            'status' => 'planned',
            'is_visible' => false,
        ]);

        Livewire::test(KittensRelationManager::class, [
            'ownerRecord' => $litter,
            'pageClass' => EditLitter::class,
        ])->assertTableActionExists(
            'create',
            fn (FilamentCreateAction $action): bool => ! $action->canCreateAnother(),
        );
    }
}
