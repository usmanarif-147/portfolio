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
     * Public landing page. Each visit is logged via VisitorTrackingService for
     * the admin analytics dashboard (owner sessions and bots are skipped inside
     * the service).
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
            'projects' => Project::query()->featured()->active()->ordered()->with('images')->take(3)->get(),
            'projectsHasMore' => Project::query()->active()->count() > 3,
            'blogPosts' => BlogPost::query()->published()->public()->latest('published_at')->take(3)->get(),
            'blogPostsHasMore' => BlogPost::query()->published()->public()->count() > 3,
        ]);

        app(\App\Services\VisitorTrackingService::class)->logVisit($request);

        return $response;
    }
}
