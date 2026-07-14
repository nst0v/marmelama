<?php

namespace App\Support;

final class RichText
{
    public static function forPage(?string $html, string $title, bool $removeLeadingHeading = false): string
    {
        $content = self::stripLeadingEmptyBlocks(trim((string) $html));

        if (preg_match('/^\s*<h([1-6])\b[^>]*>(.*?)<\/h\1>\s*/isu', $content, $heading)) {
            $headingIsPageTitle = self::normalize($heading[2]) === self::normalize($title);

            if ($removeLeadingHeading || $headingIsPageTitle) {
                $content = substr($content, strlen($heading[0]));
                $content = self::stripLeadingEmptyBlocks($content);
            }
        }

        // The page template owns the only H1. Headings added in the admin panel
        // remain useful sections, but cannot compete visually or semantically with it.
        $content = preg_replace('/<h1\b[^>]*>/iu', '<h2>', $content) ?? $content;
        $content = preg_replace('/<\/h1>/iu', '</h2>', $content) ?? $content;
        $content = preg_replace('/<h([2-6])\b[^>]*>/iu', '<h$1>', $content) ?? $content;

        return trim($content);
    }

    public static function plain(?string $html): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\u{00A0}", ' ', $text);

        return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
    }

    public static function multiline(?string $html): string
    {
        $text = preg_replace('/<br\s*\/?>/iu', "\n", (string) $html) ?? (string) $html;
        $text = preg_replace('/<\/(p|div|li)>/iu', "\n", $text) ?? $text;
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\r\n", "\r", "\u{00A0}"], ["\n", "\n", ' '], $text);
        $text = preg_replace('/[\t ]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/ *\n */u', "\n", $text) ?? $text;

        return trim(preg_replace('/\n{3,}/u', "\n\n", $text) ?? $text);
    }

    private static function stripLeadingEmptyBlocks(string $html): string
    {
        $pattern = '/^\s*<(p|h[1-6])\b[^>]*>(?:\s|&nbsp;|&#160;|<br\s*\/?>)*<\/\1>\s*/iu';

        while (preg_match($pattern, $html)) {
            $html = preg_replace($pattern, '', $html, 1) ?? $html;
        }

        return $html;
    }

    private static function normalize(string $value): string
    {
        $value = mb_strtolower(self::plain($value));

        return preg_replace('/[^\pL\pN]+/u', '', $value) ?? $value;
    }
}
