<?php

namespace Tests\Unit;

use App\Support\RichText;
use PHPUnit\Framework\TestCase;

class RichTextTest extends TestCase
{
    public function test_it_removes_a_leading_heading_that_repeats_the_page_title(): void
    {
        $html = '<h1 style="text-align: center">О питомнике&nbsp;</h1><p>Основной текст.</p>';

        $result = RichText::forPage($html, 'О питомнике');

        $this->assertSame('<p>Основной текст.</p>', $result);
    }

    public function test_it_demotes_a_distinct_admin_h1_to_a_section_heading(): void
    {
        $html = '<h1 style="text-align: center">История питомника</h1><p>Основной текст.</p>';

        $result = RichText::forPage($html, 'О питомнике');

        $this->assertSame('<h2>История питомника</h2><p>Основной текст.</p>', $result);
        $this->assertStringNotContainsString('<h1', $result);
    }

    public function test_it_preserves_paragraphs_when_rich_text_is_used_as_plain_copy(): void
    {
        $result = RichText::multiline('<p>Первый абзац.</p><p>Второй<br>абзац.</p>');

        $this->assertSame("Первый абзац.\nВторой\nабзац.", $result);
    }
}
