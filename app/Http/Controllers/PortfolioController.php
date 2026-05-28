<?php

namespace App\Http\Controllers;

use App\Models\Blog\BlogPost;
use App\Models\Education;
use App\Models\Experience\Experience;
use App\Models\Profile;
use App\Models\Project\Project;
use App\Models\Skill\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Public landing page. Also increments the admin visitor counter the first
     * time each browser hits the page — deduped by a long-lived `pf_visited`
     * cookie so refreshes don't inflate the count.
     */
    public function index(Request $request)
    {
        $user = User::first();
        $profile = Profile::where('user_id', $user?->id)->first() ?? new Profile;

        $response = response()->view('welcome', [
            'user' => $user,
            'profile' => $profile,
            'technologies' => Skill::groupedByCategory(),
            'workExperiences' => Experience::query()->active()->ordered()->with('responsibilities')->get(),
            'education' => Education::query()->ordered()->get(),
            'projects' => Project::query()->active()->ordered()->with('images')->take(3)->get(),
            'projectsHasMore' => Project::query()->active()->count() > 3,
            'blogPosts' => BlogPost::query()->published()->latest('published_at')->take(3)->get(),
            'blogPostsHasMore' => BlogPost::query()->published()->count() > 3,
        ]);

        if ($user && ! $request->cookie('pf_visited')) {
            // Atomic UPDATE; no-op when the profile row is missing.
            Profile::query()->where('user_id', $user->id)->increment('visitors_count');
            $response->withCookie(cookie('pf_visited', '1', 60 * 24 * 365));
        }

        return $response;
    }
}
