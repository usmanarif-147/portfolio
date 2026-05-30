<?php

namespace App\Services;

use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class BlockRenderer
{
    /**
     * Render an ordered list of typed blocks into HTML and a table of contents.
     *
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    public function render(array $blocks): array
    {
        $html = '';
        $toc = [];
        $usedSlugs = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';

            match ($type) {
                'heading' => $html .= $this->renderHeading($block, $toc, $usedSlugs),
                'richtext' => $html .= $this->renderRichText($block),
                'code' => $html .= $this->renderCode($block),
                default => null,
            };
        }

        return ['html' => $html, 'toc' => $toc];
    }

    /**
     * @param  array<string, mixed>  $block
     * @param  array<int, array{id: string, text: string, level: int}>  $toc
     * @param  array<string, bool>  $usedSlugs
     */
    private function renderHeading(array $block, array &$toc, array &$usedSlugs): string
    {
        $level = in_array((int) ($block['level'] ?? 2), [2, 3]) ? (int) $block['level'] : 2;
        $text = $block['text'] ?? '';

        $base = Str::slug($text) ?: 'section';
        $slug = $base;
        $i = 1;
        while (isset($usedSlugs[$slug])) {
            $slug = $base.'-'.$i++;
        }
        $usedSlugs[$slug] = true;

        $toc[] = ['id' => $slug, 'text' => $text, 'level' => $level];

        return "<h{$level} id=\"{$slug}\">".e($text)."</h{$level}>";
    }

    /**
     * @param  array<string, mixed>  $block
     */
    private function renderRichText(array $block): string
    {
        return Purify::clean($block['html'] ?? '');
    }

    /**
     * @param  array<string, mixed>  $block
     */
    private function renderCode(array $block): string
    {
        $escaped = e($block['code'] ?? '');
        $lang = $block['language'] ?? 'plaintext';
        $filename = $block['filename'] ?? '';

        $pre = "<pre><code class=\"language-{$lang}\">{$escaped}</code></pre>";

        if ($filename !== '') {
            return '<div class="code-block"><div class="code-filename">'.e($filename).'</div>'.$pre.'</div>';
        }

        return $pre;
    }
}
