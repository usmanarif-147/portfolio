<?php

namespace App\Http\Controllers;

use App\Services\ResumeDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    /**
     * Public resume download. Renders the same template the admin Resume Builder
     * uses, sourcing data from ResumeDataService so the public PDF stays in sync
     * with the owner's featured projects and resume-marked experiences in the
     * Portfolio admin.
     *
     * Each download is logged via VisitorTrackingService for the admin analytics
     * dashboard (owner sessions and bots are skipped inside the service).
     */
    public function download(Request $request, ResumeDataService $resumeData)
    {
        $data = $resumeData->gather();

        $pdf = Pdf::loadView('resume.templates.builder', $data)->setPaper('A4', 'portrait');

        $name = trim((string) ($data['header']['name'] ?? ''));
        $filename = $name !== '' ? Str::slug($name).'.pdf' : 'resume.pdf';

        $response = $pdf->download($filename);

        app(\App\Services\VisitorTrackingService::class)->logResumeDownload($request, null);

        return $response;
    }
}
