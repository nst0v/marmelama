<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $settings = SiteSetting::query()->pluck('value', 'key');

            $phone = $settings->get('phone', '+7 (913) 645-31-18');
            $phoneDigits = preg_replace('/\D+/', '', $phone) ?: '79136453118';

            $view->with('site', [
                'name' => 'МарМелАма',
                'description' => 'Питомник европейской бурмы',
                'city' => 'Омск',
                'phone' => $phone,
                'phone_href' => '+'.$phoneDigits,
                'email' => $settings->get('admin_email', 'balovatskaya@mail.ru'),
                'address' => $settings->get('address', ''),
                'message' => $settings->get('message', ''),
                'callback_enabled' => (bool) $settings->get('zvonok', true),
                'awards_enabled' => (bool) $settings->get('nagrada', false),
                'vk' => 'https://vk.com/marmelama.omsk',
                'max' => 'https://max.ru/u/f9LHodD0cOLonF7huHOgSikdjxmHiKhHhZntjhsg1BAWXZG3I4hzBkl4RtY',
                'max_label' => 'MAX',
                'telegram' => 'https://t.me/@MarMelAma_Omsk',
                'whatsapp' => 'https://wa.me/+79136453118',
            ]);
        });
    }
}
