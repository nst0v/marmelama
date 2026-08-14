<?php

namespace Tests\Feature;

use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class AdminScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_schema_does_not_contain_removed_content_tables(): void
    {
        foreach (['articles', 'article_categories', 'article_comments', 'questions'] as $table) {
            $this->assertFalse(
                Schema::hasTable($table),
                "The removed [{$table}] table is still present after a fresh migration.",
            );
        }
    }

    public function test_admin_routes_only_expose_the_retained_resources(): void
    {
        $retainedRouteNames = [
            'filament.admin.resources.kittens.index',
            'filament.admin.resources.litters.index',
            'filament.admin.resources.slides.index',
            'filament.admin.resources.reviews.index',
            'filament.admin.resources.breeding-cats.index',
            'filament.admin.resources.site-settings.index',
        ];

        foreach ($retainedRouteNames as $routeName) {
            $this->assertTrue(Route::has($routeName), "Expected admin route [{$routeName}] is missing.");
        }

        $removedRouteNames = [
            'filament.admin.resources.articles.index',
            'filament.admin.resources.article-categories.index',
            'filament.admin.resources.article-comments.index',
            'filament.admin.resources.questions.index',
            'filament.admin.pages.file-manager',
            'filament.admin.resources.content-pages.index',
            'filament.admin.resources.news-posts.index',
            'filament.admin.resources.gallery-categories.index',
            'filament.admin.resources.gallery-images.index',
        ];

        foreach ($removedRouteNames as $routeName) {
            $this->assertFalse(Route::has($routeName), "Removed admin route [{$routeName}] is still registered.");
        }

        $adminUris = collect(Route::getRoutes())
            ->map(fn ($route): string => $route->uri())
            ->filter(fn (string $uri): bool => str_starts_with($uri, 'admin'));

        $removedUriPrefixes = [
            'admin/articles',
            'admin/article-categories',
            'admin/article-comments',
            'admin/questions',
            'admin/file-manager',
            'admin/content-pages',
            'admin/news',
            'admin/news-posts',
            'admin/gallery',
            'admin/gallery-categories',
            'admin/gallery-images',
        ];

        foreach ($removedUriPrefixes as $prefix) {
            $this->assertFalse(
                $adminUris->contains(
                    fn (string $uri): bool => $uri === $prefix || str_starts_with($uri, "{$prefix}/"),
                ),
                "An admin route under the removed [{$prefix}] scope is still registered.",
            );
        }
    }

    public function test_guest_is_redirected_to_login_from_a_retained_admin_resource(): void
    {
        $this->get(route('filament.admin.resources.kittens.index'))
            ->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_site_settings_admin_only_queries_approved_contact_keys(): void
    {
        SiteSetting::query()->where('key', 'phone')->update(['value' => '+7 999 000-00-00']);
        SiteSetting::query()->create([
            'group' => 'internal',
            'key' => 'arbitrary_internal_key',
            'value' => 'must not be editable',
            'type' => 'text',
            'label' => 'Служебная настройка',
        ]);

        $this->assertSame(
            ['admin_email', 'max_url', 'phone'],
            SiteSettingResource::getEloquentQuery()->orderBy('key')->pluck('key')->all(),
        );
    }

    public function test_site_settings_admin_has_no_create_route_and_hides_unapproved_records(): void
    {
        $this->assertSame(['index', 'edit'], array_keys(SiteSettingResource::getPages()));
        $this->assertFalse(Route::has('filament.admin.resources.site-settings.create'));

        $internalSetting = SiteSetting::query()->create([
            'group' => 'internal',
            'key' => 'arbitrary_internal_key',
            'value' => 'must not be editable',
            'type' => 'text',
            'label' => 'Служебная настройка',
        ]);

        $this->actingAs(User::factory()->create());

        $this->get(route('filament.admin.resources.site-settings.edit', $internalSetting))
            ->assertNotFound();
    }

    public function test_approved_site_settings_cannot_be_deleted_from_the_admin_page(): void
    {
        $this->actingAs(User::factory()->create());

        $setting = SiteSetting::query()->where('key', 'max_url')->firstOrFail();

        Livewire::test(EditSiteSetting::class, ['record' => $setting->getRouteKey()])
            ->assertActionDoesNotExist(DeleteAction::class);
    }
}
