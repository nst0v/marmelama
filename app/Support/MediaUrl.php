<?php

namespace App\Support;

class MediaUrl
{
    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        return is_file(storage_path('app/public/'.$normalized))
            ? asset('storage/'.$normalized)
            : null;
    }
}
