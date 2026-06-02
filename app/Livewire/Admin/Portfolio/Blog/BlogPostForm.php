<?php

namespace App\Livewire\Admin\Portfolio\Blog;

use App\Models\Blog\BlogPost;
use App\Services\BlogPostService;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class BlogPostForm extends Component
{
    public ?BlogPost $blogPost = null;

    public string $title = '';

    public string $excerpt = '';

    public string $status = 'draft';

    public string $visibility = 'private';

    public string $type = 'blog';

    public array $blocks = [];

    public string $meta_title = '';

    public string $meta_description = '';

    // Tags
    public array $tags = [];

    public string $tagInput = '';

    public function mount(?BlogPost $blogPost = null): void
    {
        if ($blogPost && $blogPost->exists) {
            $this->blogPost = $blogPost;
            $this->title = $blogPost->title;
            $this->excerpt = $blogPost->excerpt ?? '';
            $this->status = $blogPost->status;
            $this->visibility = $blogPost->visibility ?? 'private';
            $this->type = $blogPost->type ?? 'blog';
            $this->blocks = $blogPost->blocks ?? [];
            $this->meta_title = $blogPost->meta_title ?? '';
            $this->meta_description = $blogPost->meta_description ?? '';
            $this->tags = $blogPost->tags->pluck('tag')->toArray();
        } else {
            // Seed starter blocks for a new post
            $this->blocks = [
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'heading',
                    'level' => 2,
                    'text' => 'Introduction',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'type' => 'richtext',
                    'html' => '',
                ],
            ];
        }
    }

    // -------------------------------------------------------------------------
    // Block management
    // -------------------------------------------------------------------------

    public function addBlock(string $type): void
    {
        $defaults = match ($type) {
            'heading' => ['level' => 2, 'text' => ''],
            'richtext' => ['html' => ''],
            'code' => ['language' => 'php', 'filename' => '', 'code' => ''],
            default => [],
        };

        if ($defaults === []) {
            return;
        }

        $this->blocks[] = array_merge(['id' => (string) Str::uuid(), 'type' => $type], $defaults);
    }

    public function removeBlock(string $id): void
    {
        $this->blocks = array_values(
            array_filter($this->blocks, fn ($b) => $b['id'] !== $id)
        );
    }

    public function moveBlock(string $id, string $direction): void
    {
        $index = null;
        foreach ($this->blocks as $i => $block) {
            if ($block['id'] === $id) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return;
        }

        if ($direction === 'up' && $index > 0) {
            [$this->blocks[$index - 1], $this->blocks[$index]] = [$this->blocks[$index], $this->blocks[$index - 1]];
        } elseif ($direction === 'down' && $index < count($this->blocks) - 1) {
            [$this->blocks[$index], $this->blocks[$index + 1]] = [$this->blocks[$index + 1], $this->blocks[$index]];
        }

        $this->blocks = array_values($this->blocks);
    }

    public function updateRichText(string $id, string $html): void
    {
        foreach ($this->blocks as &$block) {
            if ($block['id'] === $id) {
                $block['html'] = $html;
                break;
            }
        }
        unset($block);
    }

    // -------------------------------------------------------------------------
    // Tag management
    // -------------------------------------------------------------------------

    public function addTag(): void
    {
        $tag = trim($this->tagInput);

        if ($tag !== '') {
            $this->tags[] = $tag;
            $this->tagInput = '';
        }
    }

    public function removeTag(int $index): void
    {
        array_splice($this->tags, $index, 1);
    }

    // -------------------------------------------------------------------------
    // Save actions
    // -------------------------------------------------------------------------

    public function save(BlogPostService $service): void
    {
        $this->savePost($service);
    }

    private function savePost(BlogPostService $service): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'blocks' => 'required|array|min:1',
            'blocks.*.type' => 'required|string|in:heading,richtext,code',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'array',
            'tags.*' => 'string|max:50',
        ]);

        // Per-type content validation
        $hasContentError = false;
        foreach ($this->blocks as $i => $block) {
            $type = $block['type'] ?? '';
            if ($type === 'heading' && empty(trim($block['text'] ?? ''))) {
                $this->addError("blocks.{$i}.text", 'Heading text is required.');
                $hasContentError = true;
            }
            if ($type === 'code' && empty(trim($block['code'] ?? ''))) {
                $this->addError("blocks.{$i}.code", 'Code content is required.');
                $hasContentError = true;
            }
            if ($type === 'richtext' && empty(trim(strip_tags($block['html'] ?? '')))) {
                $this->addError("blocks.{$i}.html", 'Rich text content is required.');
                $hasContentError = true;
            }
        }

        if ($hasContentError) {
            return;
        }

        $postData = collect($validated)->except(['tags', 'blocks'])->toArray();
        $postData['type'] = $this->type;
        // Use the full block payload — $validated['blocks'] only contains the
        // `type` key per block (it's the only ruled sub-attribute).
        $postData['blocks'] = $this->blocks;

        if ($this->blogPost) {
            // Edit: preserve existing status/visibility/published_at values.
            $service->update(
                $this->blogPost,
                $postData,
                $this->tags,
            );
            $message = 'Blog post updated successfully.';
        } else {
            // New post: publish immediately as a private post.
            $postData['status'] = 'published';
            $postData['published_at'] = now();
            $postData['visibility'] = 'private';

            $service->create(
                $postData,
                $this->tags,
            );
            $message = 'Blog post created successfully.';
        }

        session()->flash('success', $message);
        $this->redirect(route('admin.blog.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.portfolio.blog.form');
    }
}
