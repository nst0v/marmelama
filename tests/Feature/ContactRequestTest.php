<?php

namespace Tests\Feature;

use App\Models\ContactRequest;
use App\Models\Kitten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class ContactRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_stores_a_request_and_marks_its_email_as_sent(): void
    {
        Mail::fake();

        $kitten = Kitten::query()->create([
            'name' => 'Лея',
            'slug' => 'leya-contact',
            'sex' => 'female',
            'status' => 'available',
            'is_visible' => true,
        ]);

        $response = $this->from(route('contacts', ['kitten' => $kitten->slug]))
            ->post(route('contacts.send'), [
                'kitten_id' => $kitten->id,
                'name' => 'Анна',
                'phone' => '+7 999 111-22-33',
                'email' => 'anna@example.test',
                'message' => 'Хочу узнать подробнее.',
                'website' => '',
            ]);

        $response
            ->assertRedirect(route('contacts', ['kitten' => $kitten->slug]))
            ->assertSessionHas('status', 'Заявка принята. Мы свяжемся с вами.');

        $request = ContactRequest::query()->sole();

        $this->assertSame($kitten->id, $request->kitten_id);
        $this->assertSame('Анна', $request->name);
        $this->assertSame('new', $request->status);
        $this->assertSame('sent', $request->mail_status);
        $this->assertNotNull($request->mail_sent_at);
        $this->assertNull($request->mail_error);
    }

    public function test_request_remains_in_database_when_email_delivery_fails(): void
    {
        Mail::shouldReceive('raw')
            ->once()
            ->andThrow(new RuntimeException('SMTP unavailable'));

        $this->post(route('contacts.send'), [
            'name' => 'Ольга',
            'phone' => '+7 999 000-00-00',
            'email' => 'olga@example.test',
            'message' => 'Позвоните мне, пожалуйста.',
            'website' => '',
        ])->assertSessionHas('status', 'Заявка принята. Мы свяжемся с вами.');

        $request = ContactRequest::query()->sole();

        $this->assertSame('failed', $request->mail_status);
        $this->assertSame('SMTP unavailable', $request->mail_error);
        $this->assertNull($request->mail_sent_at);
    }

    public function test_contact_page_connects_a_visible_kitten_to_the_form_by_slug(): void
    {
        $kitten = Kitten::query()->create([
            'name' => 'Лея',
            'slug' => 'leya-form',
            'sex' => 'female',
            'status' => 'available',
            'is_visible' => true,
        ]);

        $this->get(route('contacts', ['kitten' => $kitten->slug]))
            ->assertOk()
            ->assertSee('name="kitten_id" value="'.$kitten->id.'"', false)
            ->assertSeeText('Заявка по котёнку:')
            ->assertSeeText('Лея');
    }

    public function test_honeypot_rejects_bot_submissions_without_storing_them(): void
    {
        Mail::fake();

        $this->from(route('contacts'))->post(route('contacts.send'), [
            'name' => 'Bot',
            'phone' => '+7 999 000-00-00',
            'message' => 'Spam',
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_requests', 0);
    }
}
