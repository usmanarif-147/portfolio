<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    /**
     * Public resume download. Renders the same template the admin Resume Builder
     * uses (resume.templates.builder).
     *
     * The Resume Builder is not persisted yet, so this currently produces an EMPTY
     * resume (for testing). Once the builder data is stored, read it here and pass
     * the real header/profile/experiences/projects/skillGroups/educations.
     */
    public function download()
    {
        $pdf = Pdf::loadView('resume.templates.builder', [
            'header' => [],
            'profile' => '',
            'experiences' => [],
            'projects' => [],
            'skillGroups' => [],
            'educations' => [],
        ])->setPaper('A4', 'portrait');

        return $pdf->download('resume.pdf');
    }
}
