<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/pets', [SiteController::class, 'kittens'])->name('kittens.index');
Route::get('/pets/{slug}', [SiteController::class, 'kitten'])->name('kittens.show');
Route::get('/pomet', [SiteController::class, 'litters'])->name('litters.index');
Route::get('/pomet/{slug}', [SiteController::class, 'litter'])->name('litters.show');
Route::get('/parents/{sex}', [SiteController::class, 'parents'])->name('parents.index');
Route::get('/parents/{sex}/{slug}', [SiteController::class, 'parent'])->name('parents.show');
Route::get('/reviews', [SiteController::class, 'reviews'])->name('reviews');
Route::get('/dostavka', [SiteController::class, 'delivery'])->name('delivery');
Route::get('/contacts', [SiteController::class, 'contacts'])->name('contacts');
Route::post('/contacts', [SiteController::class, 'sendContact'])->name('contacts.send');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/news', [SiteController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [SiteController::class, 'newsPost'])->name('news.show');
Route::get('/archive', [SiteController::class, 'archive'])->name('archive');
Route::get('/politics', fn () => view('pages.placeholder', [
    'title' => 'Политика конфиденциальности',
    'text' => 'Страница будет заполнена юридическим текстом перед публикацией.',
]))->name('politics');
Route::get('/articles', fn () => view('pages.placeholder', ['title' => 'Статьи', 'text' => 'Раздел будет наполнен после переноса материалов.']))->name('articles');
Route::get('/{slug}', [SiteController::class, 'page'])->where('slug', '[A-Za-z0-9_-]+')->name('content.show');
