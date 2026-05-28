<?php

namespace App\Http\Controllers;

use App\Models\Project\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::query()->active()->ordered()->with('images')->paginate(12),
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::query()->active()->where('slug', $slug)->with('images')->firstOrFail();

        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
