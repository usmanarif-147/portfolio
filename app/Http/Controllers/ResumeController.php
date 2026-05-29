<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
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
     * Also increments the admin's resume-download counter the first time each
     * browser hits this endpoint — deduped by a long-lived `pf_dl_resume` cookie
     * so repeated clicks don't inflate the count.
     */
    public function download(Request $request, ResumeDataService $resumeData)
    {
        $data = $resumeData->gather();

        $pdf = Pdf::loadView('resume.templates.builder', $data)->setPaper('A4', 'portrait');

        $name = trim((string) ($data['header']['name'] ?? ''));
        $filename = $name !== '' ? Str::slug($name).'.pdf' : 'resume.pdf';

        $response = $pdf->download($filename);

        if (! $request->cookie('pf_dl_resume')) {
            $userId = User::query()->value('id');
            if ($userId) {
                Profile::query()->where('user_id', $userId)->increment('resume_downloads_count');
            }
            $response->withCookie(cookie('pf_dl_resume', '1', 60 * 24 * 365));
        }

        return $response;
    }
}
