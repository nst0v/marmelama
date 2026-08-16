<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CaptureAttribution
{
    private const QUERY_PARAMETERS = [
        'utm_source' => 100,
        'utm_medium' => 100,
        'utm_campaign' => 255,
        'utm_content' => 255,
        'utm_term' => 255,
        'yclid' => 255,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') || $request->is('admin*')) {
            return $next($request);
        }

        $attribution = (array) $request->session()->get('attribution', []);
        $hasCampaignParameters = false;

        foreach (self::QUERY_PARAMETERS as $parameter => $maxLength) {
            $value = $this->clean($request->query($parameter), $maxLength);

            if ($value === null) {
                continue;
            }

            $attribution[$parameter] = $value;
            $hasCampaignParameters = true;
        }

        if ($hasCampaignParameters || blank($attribution['landing_url'] ?? null)) {
            $attribution['landing_url'] = Str::limit($request->fullUrl(), 2048, '');
            $attribution['referrer_url'] = $this->clean($request->headers->get('referer'), 2048);
        }

        $request->session()->put('attribution', array_filter(
            $attribution,
            fn (mixed $value): bool => filled($value),
        ));

        return $next($request);
    }

    private function clean(mixed $value, int $maxLength): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $value = trim((string) preg_replace('/[\x00-\x1F\x7F]/u', '', (string) $value));

        return $value === '' ? null : Str::limit($value, $maxLength, '');
    }
}
