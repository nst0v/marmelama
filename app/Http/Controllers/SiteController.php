<?php

namespace App\Http\Controllers;

use App\Models\BreedingCat;
use App\Models\ContactRequest;
use App\Models\ContentPage;
use App\Models\GalleryImage;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\NewsPost;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\Slide;
use App\Support\BurmeseColors;
use App\Support\MediaUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

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
                    'alt' => 'Бурманский котенок питомника МарМелАма',
                    'image_url' => $fallbackImageUrl,
                ]]);
            }
        }

        return view('pages.home', [
            'availableKittens' => Kitten::query()
                ->with('litter')
                ->where('is_visible', true)
                ->where('status', 'available')
                ->orderByDesc('sort_order')
                ->orderByDesc('id')
                ->limit(6)
                ->get(),
            'reviews' => Review::query()
                ->where('is_visible', true)
                ->orderByDesc('sort_order')
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(),
            'heroSlides' => $heroSlides,
        ]);
    }

    public function kittens(Request $request): View
    {
        $status = is_string($request->query('status')) ? $request->query('status') : null;
        $sex = is_string($request->query('sex')) ? $request->query('sex') : null;

        // Keep old catalogue links working while the new filters are rolled out.
        if ($status === null && is_string($request->query('filter'))) {
            $legacyFilter = $request->query('filter');

            if (in_array($legacyFilter, ['available', 'reserved', 'sold', 'all'], true)) {
                $status = $legacyFilter;
            } elseif (in_array($legacyFilter, ['male', 'female'], true)) {
                $status = 'all';
                $sex ??= $legacyFilter;
            }
        }

        $status = in_array($status, ['available', 'reserved', 'sold', 'all'], true)
            ? $status
            : 'available';
        $sex = in_array($sex, ['male', 'female'], true) ? $sex : null;

        $visibleKittens = Kitten::query()->where('is_visible', true);
        $statusCounts = (clone $visibleKittens)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($total): int => (int) $total);
        $statusCounts->put('all', $statusCounts->sum());

        $colorValues = (clone $visibleKittens)
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->distinct()
            ->pluck('color')
            ->groupBy(fn (string $color): string => BurmeseColors::swatchKey($color))
            ->except('unknown');
        $colorOptions = $colorValues
            ->keys()
            ->mapWithKeys(fn (string $key): array => [$key => BurmeseColors::filterLabel($key)])
            ->sort();
        $color = is_string($request->query('color')) && $colorValues->has($request->query('color'))
            ? $request->query('color')
            : null;

        $query = Kitten::query()
            ->with('litter')
            ->where('is_visible', true)
            ->orderByRaw("case status when 'available' then 0 when 'reserved' then 1 else 2 end")
            ->orderByDesc('sort_order')
            ->orderByDesc('id');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($sex !== null) {
            $query->where('sex', $sex);
        }

        if ($color !== null) {
            $query->whereIn('color', $colorValues->get($color)->all());
        }

        return view('pages.kittens.index', [
            'kittens' => $query->paginate(12)->withQueryString(),
            'status' => $status,
            'sex' => $sex,
            'color' => $color,
            'statusCounts' => $statusCounts,
            'colorOptions' => $colorOptions,
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
                ->with('litter')
                ->where('is_visible', true)
                ->where('status', 'available')
                ->whereKeyNot($kitten->id)
                ->limit(3)
                ->get(),
        ]);
    }

    public function litters(Request $request): View
    {
        $requestedStatus = $request->query('status', 'all');
        $status = is_string($requestedStatus)
            && in_array($requestedStatus, ['all', 'available', 'planned', 'reserved', 'archive'], true)
                ? $requestedStatus
                : 'all';

        $requestedSort = $request->query('sort', 'newest');
        $sort = is_string($requestedSort) && in_array($requestedSort, ['newest', 'oldest'], true)
            ? $requestedSort
            : 'newest';

        $requestedSearch = $request->query('q', '');
        $search = is_string($requestedSearch)
            ? mb_substr(trim($requestedSearch), 0, 80)
            : '';

        $years = Litter::query()
            ->where('is_visible', true)
            ->whereNotNull('born_on')
            ->orderByDesc('born_on')
            ->get(['born_on'])
            ->map(fn (Litter $litter): int => (int) $litter->born_on->format('Y'))
            ->unique()
            ->values();

        $requestedYear = $request->query('year');
        $year = is_string($requestedYear) && preg_match('/^\d{4}$/', $requestedYear)
            ? (int) $requestedYear
            : null;
        $year = $year !== null && $years->containsStrict($year) ? $year : null;

        $parents = BreedingCat::query()
            ->where('is_visible', true)
            ->where(fn ($query) => $query
                ->whereHas('fatherLitters', fn ($litters) => $litters->where('is_visible', true))
                ->orWhereHas('motherLitters', fn ($litters) => $litters->where('is_visible', true)))
            ->orderBy('name')
            ->get(['id', 'name']);

        $requestedParent = $request->query('parent');
        $parentId = is_string($requestedParent) && ctype_digit($requestedParent)
            ? (int) $requestedParent
            : null;
        $parentId = $parentId !== null && $parents->contains('id', $parentId) ? $parentId : null;

        $query = Litter::query()
            ->with([
                'father',
                'mother',
                'kittens' => fn ($query) => $query
                    ->where('is_visible', true)
                    ->orderByDesc('sort_order')
                    ->orderByDesc('id'),
            ])
            ->where('is_visible', true);

        match ($status) {
            'available' => $query
                ->where('status', 'available')
                ->whereHas('kittens', fn ($kittens) => $kittens
                    ->where('is_visible', true)
                    ->where('status', 'available')),
            'planned' => $query->where('status', 'planned'),
            'reserved' => $query->where('status', 'reserved'),
            'archive' => $query->where(fn ($litters) => $litters
                ->where('status', 'archive')
                ->orWhere(fn ($availableWithoutKittens) => $availableWithoutKittens
                    ->where('status', 'available')
                    ->whereDoesntHave('kittens', fn ($kittens) => $kittens
                        ->where('is_visible', true)
                        ->where('status', 'available')))),
            default => null,
        };

        if ($year !== null) {
            $query->whereYear('born_on', $year);
        }

        if ($parentId !== null) {
            $query->where(fn ($litters) => $litters
                ->where('father_id', $parentId)
                ->orWhere('mother_id', $parentId));
        }

        if ($search !== '') {
            $term = '%'.$search.'%';

            $query->where(fn ($litters) => $litters
                ->where('letter', 'like', $term)
                ->orWhere('title', 'like', $term)
                ->orWhere('father_name', 'like', $term)
                ->orWhere('mother_name', 'like', $term)
                ->orWhereHas('father', fn ($parent) => $parent->where('name', 'like', $term))
                ->orWhereHas('mother', fn ($parent) => $parent->where('name', 'like', $term)));
        }

        $query->orderByRaw('case when born_on is null then 1 else 0 end');

        if ($sort === 'oldest') {
            $query->orderBy('born_on')->orderByDesc('sort_order')->orderBy('id');
        } else {
            $query->orderByDesc('born_on')->orderByDesc('sort_order')->orderByDesc('id');
        }

        $filters = [
            'status' => $status,
            'q' => $search,
            'year' => $year,
            'parent' => $parentId,
            'sort' => $sort,
        ];

        return view('pages.litters.index', [
            'litters' => $query->paginate(6)->withQueryString()->fragment('litter-results'),
            'filters' => $filters,
            'years' => $years,
            'parents' => $parents,
            'hasActiveFilters' => $status !== 'all'
                || $search !== ''
                || $year !== null
                || $parentId !== null
                || $sort !== 'newest',
        ]);
    }

    public function litter(string $slug): View
    {
        $litter = Litter::query()
            ->with([
                'father',
                'mother',
                'kittens' => fn ($query) => $query
                    ->where('is_visible', true)
                    ->orderByDesc('sort_order')
                    ->orderByDesc('id'),
            ])
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
                ->with(['kittens' => fn ($query) => $query->where('is_visible', true)])
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
                ->orderByDesc('sort_order')
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->paginate(10),
        ]);
    }

    public function delivery(): View
    {
        return view('pages.content.delivery', [
            'page' => ContentPage::where('slug', 'dostavka')->first(),
        ]);
    }

    public function contacts(Request $request): View
    {
        $kittenSlug = trim((string) $request->query('kitten'));
        $selectedKitten = $kittenSlug !== ''
            ? Kitten::query()
                ->where('slug', $kittenSlug)
                ->where('is_visible', true)
                ->first()
            : null;

        return view('pages.contacts', compact('selectedKitten'));
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kitten_id' => [
                'nullable',
                'integer',
                Rule::exists('kittens', 'id')->where('is_visible', true),
            ],
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:3000'],
            'email' => ['nullable', 'email', 'max:160'],
            'privacy_consent' => ['accepted'],
            'website' => ['nullable', 'prohibited'],
        ], [
            'privacy_consent.accepted' => 'Подтвердите согласие на обработку персональных данных.',
        ]);

        $attribution = (array) $request->session()->get('attribution', []);

        $contactRequest = ContactRequest::query()->create([
            'kitten_id' => $data['kitten_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'message' => $data['message'],
            'privacy_consented_at' => now(),
            'privacy_consent_version' => config('legal.documents.personal_data_consent.version'),
            'utm_source' => $attribution['utm_source'] ?? null,
            'utm_medium' => $attribution['utm_medium'] ?? null,
            'utm_campaign' => $attribution['utm_campaign'] ?? null,
            'utm_content' => $attribution['utm_content'] ?? null,
            'utm_term' => $attribution['utm_term'] ?? null,
            'yclid' => $attribution['yclid'] ?? null,
            'landing_url' => $attribution['landing_url'] ?? null,
            'referrer_url' => $attribution['referrer_url'] ?? null,
            'status' => 'new',
            'mail_status' => 'pending',
        ]);

        $contactRequest->load('kitten');
        $to = $this->site()['email'] ?: config('mail.from.address');
        $kittenLabel = $contactRequest->kitten?->display_name;
        $subject = $kittenLabel
            ? "Заявка по котёнку {$kittenLabel} — МарМелАма"
            : 'Сообщение с сайта МарМелАма';
        $body = implode("\n", array_filter([
            "Заявка №{$contactRequest->id}",
            $kittenLabel ? "Котёнок: {$kittenLabel}" : null,
            "Имя: {$data['name']}",
            "Телефон: {$data['phone']}",
            'Email: '.($data['email'] ?? 'не указан'),
            'Источник: '.$contactRequest->source_label,
            filled($contactRequest->utm_campaign) ? 'Кампания: '.$contactRequest->utm_campaign : null,
            filled($contactRequest->utm_term) ? 'Поисковый запрос: '.$contactRequest->utm_term : null,
            '',
            $data['message'],
        ], fn (?string $line): bool => $line !== null));

        try {
            Mail::raw($body, function ($message) use ($data, $subject, $to): void {
                $message->to($to)->subject($subject);

                if (filled($data['email'] ?? null)) {
                    $message->replyTo($data['email'], $data['name']);
                }
            });

            $contactRequest->update([
                'mail_status' => 'sent',
                'mail_sent_at' => now(),
                'mail_error' => null,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            $contactRequest->update([
                'mail_status' => 'failed',
                'mail_error' => Str::limit($exception->getMessage(), 2000, ''),
            ]);
        }

        return back()
            ->with('status', 'Заявка принята. Мы свяжемся с вами.')
            ->with('metrika_goal', 'contact_request_sent');
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
        abort_unless(in_array($slug, ['about', 'video'], true), 404);

        $page = ContentPage::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('pages.content.show', compact('page'));
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
