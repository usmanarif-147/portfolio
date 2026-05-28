<?php

namespace App\Livewire\Admin\Portfolio\Experiences;

use App\Models\Experience\Experience;
use App\Services\ExperienceService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ExperienceIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $activeFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActiveFilter(): void
    {
        $this->resetPage();
    }

    public function delete(ExperienceService $service, int $id): void
    {
        $service->delete(Experience::findOrFail($id));
        session()->flash('success', 'Experience deleted successfully.');
    }

    public function toggleForResume(int $id): void
    {
        $experience = Experience::findOrFail($id);

        if (! $experience->is_for_resume && Experience::query()->forResume()->count() >= 3) {
            session()->flash('error', 'Resume already has 3 experiences. Turn one off first.');

            return;
        }

        $experience->update(['is_for_resume' => ! $experience->is_for_resume]);
    }

    public function render()
    {
        $query = Experience::query()->ordered();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('role', 'like', '%'.$this->search.'%')
                    ->orWhere('company', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->activeFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->activeFilter === 'inactive') {
            $query->where('is_active', false);
        }

        return view('livewire.admin.portfolio.experiences.index', [
            'experiences' => $query->paginate(10),
        ]);
    }
}
