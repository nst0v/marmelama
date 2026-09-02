<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/kittens', [SiteController::class, 'kittens'])->name('kittens.index');
Route::get('/kittens/{slug}', [SiteController::class, 'kitten'])->name('kittens.show');
Route::get('/litters', [SiteController::class, 'litters'])->name('litters.index');
Route::get('/litters/{slug}', [SiteController::class, 'litter'])->name('litters.show');
Route::get('/parents/{sex}', [SiteController::class, 'parents'])->name('parents.index');
Route::get('/parents/{sex}/{slug}', [SiteController::class, 'parent'])->name('parents.show');
Route::get('/reviews', [SiteController::class, 'reviews'])->name('reviews');
Route::get('/delivery', [SiteController::class, 'delivery'])->name('delivery');
Route::get('/contacts', [SiteController::class, 'contacts'])->name('contacts');
Route::post('/contacts', [SiteController::class, 'sendContact'])
    ->middleware('throttle:5,1')
    ->name('contacts.send');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/news', [SiteController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [SiteController::class, 'newsPost'])->name('news.show');
Route::get('/archive', fn () => redirect()->route('kittens.index', [
    ...request()->query(),
    'status' => 'sold',
], 301))->name('archive');
Route::view('/politics', 'pages.legal.privacy')->name('politics');
Route::view('/personal-data-consent', 'pages.legal.personal-data-consent')->name('personal-data-consent');
Route::view('/cookies', 'pages.legal.cookies')->name('cookies');
Route::view('/requisites', 'pages.legal.requisites')->name('requisites');

Route::get('/pets', fn () => redirect()->route('kittens.index', request()->query(), 301));
Route::get('/pets/{slug}', fn (string $slug) => redirect()->route('kittens.show', [
    'slug' => $slug,
    ...request()->query(),
], 301));
Route::get('/pomet', fn () => redirect()->route('litters.index', request()->query(), 301));
Route::get('/pomet/{slug}', fn (string $slug) => redirect()->route('litters.show', [
    'slug' => $slug,
    ...request()->query(),
], 301));
Route::get('/dostavka', fn () => redirect()->route('delivery', request()->query(), 301));

Route::get('/{slug}', [SiteController::class, 'page'])
    ->whereIn('slug', ['about', 'video'])
    ->name('content.show');
