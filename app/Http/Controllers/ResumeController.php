<?php

namespace App\Http\Controllers;

use App\Services\ResumeDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    /**
     * Public resume download. Renders the same template the admin Resume Builder
     * uses, sourcing data from ResumeDataService so the public PDF stays in sync
     * with whatever the owner has marked is_for_resume in the Portfolio admin.
     */
    public function download(ResumeDataService $resumeData)
    {
        $data = $resumeData->gather();

        $pdf = Pdf::loadView('resume.templates.builder', $data)->setPaper('A4', 'portrait');

        $name = trim((string) ($data['header']['name'] ?? ''));
        $filename = $name !== '' ? Str::slug($name).'.pdf' : 'resume.pdf';

        return $pdf->download($filename);
    }
}
