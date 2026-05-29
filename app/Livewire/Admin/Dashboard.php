<?php

namespace App\Livewire\Admin;

use App\Models\Project\Project;
use App\Models\ResumeDownloadLog;
use App\Models\VisitorLog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'projectsCount' => Project::query()->count(),
            'visitorsCount' => VisitorLog::query()->count(),
            'resumeDownloadsCount' => ResumeDownloadLog::query()->count(),
        ]);
    }
}
