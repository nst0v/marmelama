<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_schema_contains_only_supported_setting_keys(): void
    {
        $this->assertSame(
            ['admin_email', 'max_url', 'phone'],
            SiteSetting::query()->orderBy('key')->pluck('key')->all(),
        );
    }

    public function test_approved_contact_settings_are_used_by_the_public_site(): void
    {
        $this->setSetting('phone', '+7 (999) 111-22-33', 'Телефон', 'text');
        $this->setSetting('admin_email', 'owner@example.test', 'Электронная почта', 'email');
        $this->setSetting('max_url', 'https://max.ru/example', 'Ссылка MAX', 'url');

        $this->get('/contacts')
            ->assertOk()
            ->assertSee('+7 (999) 111-22-33')
            ->assertSee('tel:+79991112233', false)
            ->assertSee('owner@example.test')
            ->assertSee('https://max.ru/example', false);
    }

    public function test_empty_max_setting_keeps_the_safe_fallback_link(): void
    {
        SiteSetting::query()->where('key', 'max_url')->update(['value' => '']);

        $this->get('/')
            ->assertOk()
            ->assertSee('https://max.ru/u/f9LHodD0cOLonF7huHOgSikdjxmHiKhHhZntjhsg1BAWXZG3I4hzBkl4RtY', false);
    }

    private function setSetting(string $key, string $value, string $label, string $type): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            [
                'group' => $key === 'max_url' ? 'social' : 'contacts',
                'value' => $value,
                'type' => $type,
                'label' => $label,
            ],
        );
    }
}
