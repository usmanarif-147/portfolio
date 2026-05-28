<?php

namespace App\Livewire\Admin\Portfolio\ResumeBuilder;

use App\Services\ResumeDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * The Resume Builder is now a read-only live preview of resume data sourced
 * from the Portfolio module (see ResumeDataService). The owner controls what
 * appears in the resume via the is_for_resume toggle on Projects/Experiences
 * (capped to 3 each); skills/education/header/summary are included automatically.
 */
#[Layout('components.layouts.admin')]
class ResumeBuilderIndex extends Component
{
    public function downloadPdf(ResumeDataService $resumeData)
    {
        $data = $resumeData->gather();

        $pdf = Pdf::loadView('resume.templates.builder', $data)->setPaper('A4', 'portrait');

        $name = trim((string) ($data['header']['name'] ?? ''));
        $filename = $name !== '' ? Str::slug($name).'.pdf' : 'resume.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename
        );
    }

    public function render(ResumeDataService $resumeData)
    {
        return view('livewire.admin.portfolio.resume-builder.index', $resumeData->gather());
    }
}
