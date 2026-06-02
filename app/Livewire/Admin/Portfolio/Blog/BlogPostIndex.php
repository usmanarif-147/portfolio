<?php

namespace App\Livewire\Admin\Portfolio\Blog;

use App\Models\Blog\BlogPost;
use App\Services\BlogPostService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class BlogPostIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $visibilityFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingVisibilityFilter(): void
    {
        $this->resetPage();
    }

    public function delete(BlogPostService $service, int $id): void
    {
        $service->delete(BlogPost::findOrFail($id));
        session()->flash('success', 'Blog post deleted successfully.');
    }

    public function toggleVisibility(int $id): void
    {
        $post = BlogPost::findOrFail($id);

        $makePublic = $post->visibility !== 'public';

        if ($makePublic) {
            $post->update([
                'visibility' => 'public',
                'status' => 'published',
                'published_at' => $post->published_at ?? now(),
            ]);
        } else {
            $post->update(['visibility' => 'private']);
        }

        $this->dispatch('toast', type: 'success', message: $makePublic ? 'Post is now public.' : 'Post is now private.');
    }

    public function render()
    {
        $query = BlogPost::query()->with('tags')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$this->search.'%');
            });
        }

        if (in_array($this->visibilityFilter, ['public', 'private'], true)) {
            $query->where('visibility', $this->visibilityFilter);
        }

        return view('livewire.admin.portfolio.blog.index', [
            'posts' => $query->paginate(10),
        ]);
    }
}
