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

    public function toggleForResume(int $id): void
    {
        $project = Project::findOrFail($id);

        if (! $project->is_for_resume && Project::query()->forResume()->count() >= 3) {
            session()->flash('error', 'Resume already has 3 projects. Turn one off first.');

            return;
        }

        $project->update(['is_for_resume' => ! $project->is_for_resume]);
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
