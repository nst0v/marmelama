<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_legal_pages_are_published_with_operator_details(): void
    {
        foreach (['politics', 'personal-data-consent', 'cookies', 'requisites'] as $routeName) {
            $this->get(route($routeName))
                ->assertOk()
                ->assertSeeText('Иванова Елена Александровна')
                ->assertSeeText('550506959446')
                ->assertSeeText('2 сентября 2026 г.');
        }

        $this->get(route('politics'))
            ->assertSeeText('Налог на профессиональный доход')
            ->assertSeeText('112180369')
            ->assertDontSeeText('Страница будет заполнена юридическим текстом перед публикацией.');

        $this->get(route('requisites'))
            ->assertSeeText('самозанятая')
            ->assertSeeText('balovatskaya@mail.ru')
            ->assertSeeText('+7 (913) 645-31-18');
    }

    public function test_contact_form_uses_a_separate_unchecked_personal_data_consent(): void
    {
        $response = $this->get(route('contacts'));

        $response
            ->assertOk()
            ->assertSee(route('personal-data-consent'), false)
            ->assertSee(route('politics'), false)
            ->assertSeeText('Я даю согласие на обработку персональных данных')
            ->assertSee('name="privacy_consent"', false);

        $this->assertDoesNotMatchRegularExpression(
            '/<input[^>]+name="privacy_consent"[^>]+checked/i',
            $response->getContent(),
        );
    }

    public function test_footer_links_every_legal_document_and_identifies_the_operator(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee(route('politics'), false)
            ->assertSee(route('personal-data-consent'), false)
            ->assertSee(route('cookies'), false)
            ->assertSee(route('requisites'), false)
            ->assertSeeText('Иванова Елена Александровна')
            ->assertSeeText('ИНН 550506959446')
            ->assertDontSee('fonts.googleapis.com', false)
            ->assertDontSee('fonts.gstatic.com', false);
    }
}
