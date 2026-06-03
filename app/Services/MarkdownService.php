<?php

namespace App\Services;

use Illuminate\Support\Str;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Stevebauman\Purify\Facades\Purify;

/**
 * Converts a blog post's raw Markdown into display-ready, sanitized HTML and a
 * table of contents. Heading IDs are added AFTER sanitizing so HTMLPurifier
 * never strips them. Fenced code keeps its `language-*` class for highlight.js.
 */
class MarkdownService
{
    /**
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    public function render(?string $markdown): array
    {
        $markdown = $markdown ?? '';

        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        $rawHtml = (string) $converter->convert($markdown);
        $cleanHtml = Purify::clean($rawHtml);

        return $this->addHeadingAnchors($cleanHtml);
    }

    /**
     * Assign slug ids to every h2/h3 and collect them into a table of contents.
     *
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    private function addHeadingAnchors(string $html): array
    {
        if (trim($html) === '') {
            return ['html' => '', 'toc' => []];
        }

        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>'.$html,
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $toc = [];
        $used = [];

        $xpath = new \DOMXPath($dom);
        foreach ($xpath->query('//h2 | //h3') as $node) {
            $text = trim($node->textContent);
            if ($text === '') {
                continue;
            }

            $base = Str::slug($text) ?: 'section';
            $slug = $base;
            $i = 1;
            while (isset($used[$slug])) {
                $slug = $base.'-'.$i++;
            }
            $used[$slug] = true;

            $node->setAttribute('id', $slug);
            $toc[] = [
                'id' => $slug,
                'text' => $text,
                'level' => (int) substr($node->nodeName, 1),
            ];
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $inner = '';
        if ($body) {
            foreach ($body->childNodes as $child) {
                $inner .= $dom->saveHTML($child);
            }
        }

        return ['html' => $inner, 'toc' => $toc];
    }
}
