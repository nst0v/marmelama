<?php

namespace App\Support;

use Illuminate\Support\Str;

class BurmeseColors
{
    public static function forSelect(?string $currentValue = null): array
    {
        $options = self::official();
        $currentValue = trim((string) $currentValue);

        if ($currentValue !== '' && ! self::containsValue($options, $currentValue)) {
            $options['Текущее значение записи'] = [
                $currentValue => $currentValue,
            ];
        }

        return $options;
    }

    public static function official(): array
    {
        return [
            'Американская бурма (CFA Burmese)' => [
                'Соболиный (sable)' => 'Соболиный (sable)',
                'Шампань (champagne)' => 'Шампань (champagne)',
                'Голубой (blue)' => 'Голубой (blue)',
                'Платиновый (platinum)' => 'Платиновый (platinum)',
            ],
            'Европейская бурма (FIFe)' => [
                'Коричневый (brown)' => 'Коричневый (brown)',
                'Голубой европейский (blue)' => 'Голубой европейский (blue)',
                'Шоколадный (chocolate)' => 'Шоколадный (chocolate)',
                'Лиловый (lilac)' => 'Лиловый (lilac)',
                'Красный (red)' => 'Красный (red)',
                'Кремовый (cream)' => 'Кремовый (cream)',
                'Коричневый черепаховый (brown tortie)' => 'Коричневый черепаховый (brown tortie)',
                'Голубой черепаховый (blue tortie)' => 'Голубой черепаховый (blue tortie)',
                'Шоколадный черепаховый (chocolate tortie)' => 'Шоколадный черепаховый (chocolate tortie)',
                'Лиловый черепаховый (lilac tortie)' => 'Лиловый черепаховый (lilac tortie)',
            ],
        ];
    }

    public static function label(?string $color): ?string
    {
        $color = trim((string) $color);

        if ($color === '') {
            return null;
        }

        return Str::lower(Str::before($color, ' ('));
    }

    public static function swatchKey(?string $color): string
    {
        $color = Str::lower(trim((string) $color));

        return match (true) {
            Str::contains($color, ['коричневый черепаховый', 'brown tortie']) => 'brown-tortie',
            Str::contains($color, ['голубой черепаховый', 'blue tortie']) => 'blue-tortie',
            Str::contains($color, ['шоколадный черепаховый', 'chocolate tortie']) => 'chocolate-tortie',
            Str::contains($color, ['лиловый черепаховый', 'lilac tortie']) => 'lilac-tortie',
            Str::contains($color, ['собол', 'sable']) => 'sable',
            Str::contains($color, ['шампань', 'champagne']) => 'champagne',
            Str::contains($color, ['платин', 'platinum']) => 'platinum',
            Str::contains($color, ['голубой европейский']) => 'european-blue',
            Str::contains($color, ['голуб', 'blue']) => 'blue',
            Str::contains($color, ['шоколад', 'chocolate']) => 'chocolate',
            Str::contains($color, ['лилов', 'lilac']) => 'lilac',
            Str::contains($color, ['красн', 'red']) => 'red',
            Str::contains($color, ['крем', 'cream']) => 'cream',
            Str::contains($color, ['коричнев', 'brown']) => 'brown',
            default => 'unknown',
        };
    }

    public static function filterLabel(string $swatchKey): string
    {
        return [
            'sable' => 'Соболиный',
            'champagne' => 'Шампань',
            'blue' => 'Голубой',
            'platinum' => 'Платиновый',
            'brown' => 'Коричневый',
            'european-blue' => 'Голубой европейский',
            'chocolate' => 'Шоколадный',
            'lilac' => 'Лиловый',
            'red' => 'Красный',
            'cream' => 'Кремовый',
            'brown-tortie' => 'Коричневый черепаховый',
            'blue-tortie' => 'Голубой черепаховый',
            'chocolate-tortie' => 'Шоколадный черепаховый',
            'lilac-tortie' => 'Лиловый черепаховый',
        ][$swatchKey] ?? 'Другой окрас';
    }

    private static function containsValue(array $groups, string $value): bool
    {
        foreach ($groups as $options) {
            if (array_key_exists($value, $options)) {
                return true;
            }
        }

        return false;
    }
}
