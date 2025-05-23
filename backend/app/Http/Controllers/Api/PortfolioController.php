<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $profile = Profile::get();
        $education = Education::get();
        $skills = Skill::get();
        $projects = Project::get();
        $experience = Experience::get();

        return response()->json([
            'data' => [
                'profile' => $profile,
                'education' => $education,
                'skills' => $skills,
                'projects' => $projects,
                'experience' => $experience,
            ]
        ]);
    }
}
