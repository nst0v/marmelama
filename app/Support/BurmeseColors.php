<?php

namespace App\Support;

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
