<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\BreedingCat;
use App\Models\ContentPage;
use App\Models\GalleryImage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\NewsPost;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\Slide;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $heroSlides = Slide::query()
            ->where('is_visible', true)
            ->where('placement', 'home')
            ->orderByDesc('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Slide $slide): array => [
                'title' => $slide->title,
                'caption' => $slide->caption,
                'url' => $slide->url,
                'alt' => $slide->alt,
                'image_url' => MediaUrl::url($slide->image),
            ])
            ->filter(fn (array $slide): bool => $slide['image_url'] !== null)
            ->values();

        if ($heroSlides->isEmpty()) {
            $fallbackImage = $this->firstImage(
                Kitten::query()->where('is_visible', true)->whereNotNull('images')->latest('id')->first()
            );
            $fallbackImageUrl = MediaUrl::url($fallbackImage);

            if ($fallbackImageUrl !== null) {
                $heroSlides = collect([[
                    'title' => 'Бурманские котята МарМелАма',
                    'caption' => 'Питомник европейской бурмы в Омске. Поможем выбрать котенка по характеру, расскажем об уходе и организуем доставку по России.',
                    'url' => route('kittens.index'),
                    'alt' => 'Бурманский котенок питомника МарМелАма',
                    'image_url' => $fallbackImageUrl,
                ]]);
            }
        }

        return view('pages.home', [
            'availableKittens' => Kitten::query()
                ->where('is_visible', true)
                ->where('status', 'available')
                ->orderByDesc('sort_order')
                ->orderByDesc('id')
                ->limit(6)
                ->get(),
            'parents' => BreedingCat::query()
                ->where('is_visible', true)
                ->where('is_active', true)
                ->orderByDesc('sort_order')
                ->orderBy('name')
                ->limit(4)
                ->get(),
            'reviews' => Review::query()
                ->where('is_visible', true)
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(),
            'heroSlides' => $heroSlides,
        ]);
    }

    public function kittens(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $query = Kitten::query()
            ->with('litter')
            ->where('is_visible', true)
            ->orderByRaw("case status when 'available' then 0 when 'reserved' then 1 else 2 end")
            ->orderByDesc('sort_order')
            ->orderByDesc('id');

        match ($filter) {
            'male' => $query->where('sex', 'male'),
            'female' => $query->where('sex', 'female'),
            'available' => $query->where('status', 'available'),
            'reserved' => $query->where('status', 'reserved'),
            'sold' => $query->where('status', 'sold'),
            default => null,
        };

        return view('pages.kittens.index', [
            'kittens' => $query->get(),
            'filter' => $filter,
        ]);
    }

    public function kitten(string $slug): View
    {
        $kitten = Kitten::query()
            ->with('litter.father', 'litter.mother')
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('pages.kittens.show', [
            'kitten' => $kitten,
            'otherKittens' => Kitten::query()
                ->where('is_visible', true)
                ->where('status', 'available')
                ->whereKeyNot($kitten->id)
                ->limit(3)
                ->get(),
        ]);
    }

    public function litters(): View
    {
        return view('pages.litters.index', [
            'litters' => Litter::query()
                ->with('father', 'mother', 'kittens')
                ->where('is_visible', true)
                ->orderByDesc('born_on')
                ->orderByDesc('sort_order')
                ->get(),
        ]);
    }

    public function litter(string $slug): View
    {
        $litter = Litter::query()
            ->with('father', 'mother', 'kittens')
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('pages.litters.show', compact('litter'));
    }

    public function parents(string $sex): View
    {
        abort_unless(in_array($sex, ['0', '1'], true), 404);

        return view('pages.parents.index', [
            'sex' => $sex,
            'parents' => BreedingCat::query()
                ->where('is_visible', true)
                ->where('sex', $sex === '1' ? 'male' : 'female')
                ->orderByDesc('is_active')
                ->orderByDesc('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function parent(string $sex, string $slug): View
    {
        abort_unless(in_array($sex, ['0', '1'], true), 404);

        $parent = BreedingCat::query()
            ->where('slug', $slug)
            ->where('sex', $sex === '1' ? 'male' : 'female')
            ->where('is_visible', true)
            ->firstOrFail();

        return view('pages.parents.show', [
            'parent' => $parent,
            'litters' => Litter::query()
                ->where('is_visible', true)
                ->where(fn ($query) => $query
                    ->where('father_id', $parent->id)
                    ->orWhere('mother_id', $parent->id))
                ->orderByDesc('born_on')
                ->get(),
        ]);
    }

    public function reviews(): View
    {
        return view('pages.reviews', [
            'reviews' => Review::query()
                ->where('is_visible', true)
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function delivery(): View
    {
        return view('pages.content.delivery', [
            'page' => ContentPage::where('slug', 'dostavka')->first(),
        ]);
    }

    public function contacts(): View
    {
        return view('pages.contacts');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:3000'],
            'email' => ['nullable', 'email', 'max:160'],
        ]);

        $to = $this->site()['email'] ?: config('mail.from.address');

        Mail::raw(
            "Имя: {$data['name']}\nТелефон: {$data['phone']}\nEmail: ".($data['email'] ?? 'не указан')."\n\n{$data['message']}",
            fn ($message) => $message->to($to)->subject('Сообщение с сайта МарМелАма')
        );

        return back()->with('status', 'Сообщение отправлено. Мы свяжемся с вами.');
    }

    public function gallery(): View
    {
        return view('pages.gallery', [
            'images' => GalleryImage::query()
                ->where('is_visible', true)
                ->where(fn ($query) => $query->whereNull('category')->orWhere('category', '!=', 'slider'))
                ->orderBy('category')
                ->orderByDesc('sort_order')
                ->get(),
        ]);
    }

    public function news(): View
    {
        return view('pages.news.index', [
            'posts' => NewsPost::query()
                ->where('is_visible', true)
                ->orderByDesc('published_at')
                ->get(),
        ]);
    }

    public function newsPost(string $slug): View
    {
        return view('pages.news.show', [
            'post' => NewsPost::query()
                ->where('is_visible', true)
                ->where(fn ($query) => $query
                    ->where('slug', $slug)
                    ->when(is_numeric($slug), fn ($query) => $query->orWhere('old_id', (int) $slug)))
                ->firstOrFail(),
        ]);
    }

    public function page(string $slug): View
    {
        $page = ContentPage::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('pages.content.show', compact('page'));
    }

    public function archive(): View
    {
        return view('pages.archive', [
            'kittens' => Kitten::query()
                ->with('litter')
                ->where('is_visible', true)
                ->where('status', 'sold')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function articles(): View
    {
        return view('pages.articles.index', [
            'categories' => ArticleCategory::query()
                ->withCount('articles')
                ->orderByDesc('sort_order')
                ->orderBy('title')
                ->get(),
            'articles' => Article::query()
                ->with('category')
                ->where('is_visible', true)
                ->orderByDesc('published_at')
                ->orderByDesc('sort_order')
                ->get(),
        ]);
    }

    public function article(string $slug): View
    {
        return view('pages.articles.show', [
            'article' => Article::query()
                ->with('category')
                ->where('is_visible', true)
                ->where(fn ($query) => $query
                    ->where('slug', $slug)
                    ->when(is_numeric($slug), fn ($query) => $query->orWhere('old_id', (int) $slug)))
                ->firstOrFail(),
        ]);
    }

    private function firstImage(?object $model): ?string
    {
        $images = $model?->images;

        return is_array($images) ? ($images[0] ?? null) : null;
    }

    private function site(): array
    {
        $settings = SiteSetting::query()->pluck('value', 'key');

        return [
            'email' => $settings->get('admin_email', 'balovatskaya@mail.ru'),
        ];
    }
}
