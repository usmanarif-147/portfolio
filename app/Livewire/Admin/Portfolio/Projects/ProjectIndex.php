<?php

namespace App\Livewire\Admin\Portfolio\Projects;

use App\Models\Project\Project;
use App\Services\ProjectService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ProjectIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $activeFilter = 'all';

    #[Url]
    public string $featuredFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActiveFilter(): void
    {
        $this->resetPage();
    }

    public function updatingFeaturedFilter(): void
    {
        $this->resetPage();
    }

    public function delete(ProjectService $service, int $id): void
    {
        $service->delete(Project::findOrFail($id));
        session()->flash('success', 'Project deleted successfully.');
    }

    public function toggleFeatured(int $id): void
    {
        $project = Project::findOrFail($id);

        if (! $project->is_featured && Project::query()->featured()->count() >= 3) {
            $this->dispatch('toast', type: 'error', message: 'Only 3 projects can be featured at a time. Turn one off first.');

            return;
        }

        $project->update(['is_featured' => ! $project->is_featured]);

        $this->dispatch('toast', type: 'success', message: $project->is_featured ? 'Project marked as featured.' : 'Project removed from featured.');
    }

    public function toggleActive(int $id): void
    {
        $project = Project::findOrFail($id);
        $project->update(['is_active' => ! $project->is_active]);

        $this->dispatch('toast', type: 'success', message: $project->is_active ? 'Project activated.' : 'Project deactivated.');
    }

    public function render()
    {
        $query = Project::query()->ordered();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('short_description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->activeFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->activeFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if ($this->featuredFilter === 'featured') {
            $query->where('is_featured', true);
        } elseif ($this->featuredFilter === 'not_featured') {
            $query->where('is_featured', false);
        }

        return view('livewire.admin.portfolio.projects.index', [
            'projects' => $query->paginate(10),
        ]);
    }
}
