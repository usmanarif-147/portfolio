<?php

namespace App\Http\Controllers;

use App\Models\Blog\BlogPost;
use App\Models\Education;
use App\Models\Experience\Experience;
use App\Models\Profile;
use App\Models\Project\Project;
use App\Models\Skill\Skill;
use App\Models\User;

class PortfolioController extends Controller
{
    public function index()
    {
        $user = User::first();
        $profile = Profile::where('user_id', $user?->id)->first() ?? new Profile;

        return view('welcome', [
            'user' => $user,
            'profile' => $profile,
            'technologies' => Skill::groupedByCategory(),
            'workExperiences' => Experience::query()->active()->ordered()->with('responsibilities')->get(),
            'education' => Education::query()->ordered()->get(),
            'projects' => Project::query()->active()->ordered()->take(3)->get(),
            'projectsHasMore' => Project::query()->active()->count() > 3,
            'blogPosts' => BlogPost::query()->published()->latest('published_at')->take(3)->get(),
            'blogPostsHasMore' => BlogPost::query()->published()->count() > 3,
        ]);
    }
}
