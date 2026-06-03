<?php

namespace App\Services;

use App\Models\Blog\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogPostService
{
    public function __construct(private BlockRenderer $blockRenderer) {}

    public function create(array $data, array $tags = []): BlogPost
    {
        return DB::transaction(function () use ($data, $tags) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
            $data = $this->renderBlocks($data);

            $post = BlogPost::create($data);
            $this->syncTags($post, $tags);

            return $post;
        });
    }

    public function update(BlogPost $post, array $data, array $tags = []): BlogPost
    {
        return DB::transaction(function () use ($post, $data, $tags) {
            if ($data['title'] !== $post->title) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $post->id);
            }

            $data = $this->renderBlocks($data);

            $post->update($data);
            $this->syncTags($post, $tags);

            return $post;
        });
    }

    public function delete(BlogPost $post): void
    {
        $post->delete();
    }

    public function publish(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $post;
    }

    public function unpublish(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return $post;
    }

    private function syncTags(BlogPost $post, array $tags): void
    {
        $post->tags()->delete();

        $uniqueTags = array_unique(array_filter(array_map('trim', $tags)));

        foreach ($uniqueTags as $tag) {
            $post->tags()->create(['tag' => $tag]);
        }
    }

    /**
     * Render blocks into cached HTML + table of contents and derive reading time.
     */
    private function renderBlocks(array $data): array
    {
        $rendered = $this->blockRenderer->render($data['blocks'] ?? []);
        $data['content_html'] = $rendered['html'];
        $data['toc'] = $rendered['toc'];
        $data['reading_time_minutes'] = $this->calculateReadingTime($rendered['html']);

        return $data;
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        $query = BlogPost::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $original.'-'.$count++;
            $query = BlogPost::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    private function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));

        return max(1, (int) ceil($wordCount / 200));
    }
}
