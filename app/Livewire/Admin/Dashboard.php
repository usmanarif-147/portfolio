<?php

namespace App\Livewire\Admin;

use App\Models\Profile;
use App\Models\Project\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $profile = Profile::query()->first();

        return view('livewire.admin.dashboard', [
            'projectsCount' => Project::query()->count(),
            'visitorsCount' => (int) ($profile?->visitors_count ?? 0),
            'resumeDownloadsCount' => (int) ($profile?->resume_downloads_count ?? 0),
        ]);
    }
}
