<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_configured_metrika_counter_and_consent_controls_render_on_public_pages(): void
    {
        config()->set('services.yandex_metrika.id', 111660257);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-metrika-id="111660257"', false)
            ->assertSee('data-analytics-consent-version="2026-09-02"', false)
            ->assertSee('data-analytics-consent-days="365"', false)
            ->assertSee('data-analytics-consent', false)
            ->assertSeeText('Аналитические cookie')
            ->assertSee(route('cookies'), false)
            ->assertSeeText('Отказ не влияет на работу сайта и формы.')
            ->assertSee('js/site.js?v=', false)
            ->assertSee('data-analytics-consent-settings', false);
    }

    public function test_analytics_controls_are_absent_when_counter_is_not_configured(): void
    {
        config()->set('services.yandex_metrika.id', null);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('data-metrika-id=', false)
            ->assertDontSee('data-analytics-consent', false)
            ->assertDontSee('data-analytics-consent-settings', false);
    }

    public function test_public_contact_links_are_labeled_with_conversion_goals(): void
    {
        $this->get('/contacts')
            ->assertOk()
            ->assertSee('data-analytics-goal="phone_click"', false)
            ->assertSee('data-analytics-goal="max_click"', false)
            ->assertSee('data-analytics-goal="email_click"', false)
            ->assertSee('name="privacy_consent"', false);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-analytics-goal="max_click"', false)
            ->assertSee('data-analytics-goal="contact_form_open"', false);
    }
}
