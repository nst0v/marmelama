<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaUrl
{
    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }

        if (str_starts_with($normalized, 'app/public/')) {
            $normalized = substr($normalized, strlen('app/public/'));
        }

        return Storage::disk('public')->exists($normalized)
            ? Storage::disk('public')->url($normalized)
            : null;
    }
}
