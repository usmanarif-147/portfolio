<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience\Experience;
use App\Models\Profile;
use App\Models\Skill\Skill;
use App\Models\Strength;
use App\Models\User;
use App\Support\DummyBlogData;
use App\Support\DummyProjectData;

class PortfolioController extends Controller
{
    public function index()
    {
        $user = User::first();
        $profile = Profile::where('user_id', $user->id)->first();

        return view('welcome', [
            'user' => $user,
            'profile' => $profile,
            'skills' => Strength::query()->active()->ordered()->get(),
            'technologies' => Skill::groupedByCategory(),
            'workExperiences' => Experience::query()->active()->ordered()->with('responsibilities')->get(),
            'education' => Education::query()->ordered()->get(),
            // BACKEND: swap dummy source for the real query, e.g.
            // 'projects' => Project::query()->active()->ordered()->take(3)->get(),
            // 'projectsHasMore' => Project::query()->active()->count() > 3,
            'projects' => DummyProjectData::take(3),
            'projectsHasMore' => DummyProjectData::count() > 3,
            // BACKEND: swap dummy source for the real query, e.g.
            // 'blogPosts' => BlogPost::query()->published()->latest('published_at')->take(3)->get(),
            // 'blogPostsHasMore' => BlogPost::query()->published()->count() > 3,
            'blogPosts' => DummyBlogData::latest(3),
            'blogPostsHasMore' => DummyBlogData::count() > 3,
        ]);
    }
}
