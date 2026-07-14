<?php

namespace Tests\Unit;

use App\Support\BurmeseColors;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BurmeseColorsTest extends TestCase
{
    public static function officialColors(): array
    {
        return [
            ['Соболиный (sable)', 'sable'],
            ['Шампань (champagne)', 'champagne'],
            ['Голубой (blue)', 'blue'],
            ['Платиновый (platinum)', 'platinum'],
            ['Коричневый (brown)', 'brown'],
            ['Голубой европейский (blue)', 'european-blue'],
            ['Шоколадный (chocolate)', 'chocolate'],
            ['Лиловый (lilac)', 'lilac'],
            ['Красный (red)', 'red'],
            ['Кремовый (cream)', 'cream'],
            ['Коричневый черепаховый (brown tortie)', 'brown-tortie'],
            ['Голубой черепаховый (blue tortie)', 'blue-tortie'],
            ['Шоколадный черепаховый (chocolate tortie)', 'chocolate-tortie'],
            ['Лиловый черепаховый (lilac tortie)', 'lilac-tortie'],
        ];
    }

    #[DataProvider('officialColors')]
    public function test_every_official_admin_color_has_a_swatch(string $color, string $expectedSwatch): void
    {
        $this->assertSame($expectedSwatch, BurmeseColors::swatchKey($color));
    }

    public function test_admin_cannot_offer_an_official_color_without_a_swatch(): void
    {
        $css = file_get_contents(dirname(__DIR__, 2).'/public/css/site.css');

        foreach (BurmeseColors::official() as $colors) {
            foreach (array_keys($colors) as $color) {
                $swatch = BurmeseColors::swatchKey($color);

                $this->assertNotSame('unknown', $swatch, $color);
                $this->assertStringContainsString(".kitten-card-color-swatch--{$swatch}", $css, $color);
            }
        }
    }

    public function test_legacy_and_custom_colors_have_safe_presentations(): void
    {
        $this->assertSame('lilac', BurmeseColors::swatchKey('лиловый'));
        $this->assertSame('sable', BurmeseColors::swatchKey('соболиный'));
        $this->assertSame('unknown', BurmeseColors::swatchKey('нестандартный окрас'));
        $this->assertSame('шоколадный', BurmeseColors::label('Шоколадный (chocolate)'));
        $this->assertSame('Шоколадный', BurmeseColors::filterLabel('chocolate'));
    }
}
