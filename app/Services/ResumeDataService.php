<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Experience\Experience;
use App\Models\Profile;
use App\Models\Project\Project;
use App\Models\Skill\Skill;
use App\Models\User;

/**
 * Single source of truth for resume data.
 *
 * The admin Resume Builder preview, its PDF download, and the public
 * /resume/download endpoint all consume gather() — projects are sourced
 * from is_featured (max 3) and experiences from is_for_resume (max 3),
 * each capped via the admin form guards.
 */
class ResumeDataService
{
    /**
     * Returns the exact data shape expected by resources/views/resume/templates/_body.blade.php:
     *  - header:      [name, tagline, location, phone, email, linkedin, github]
     *  - profile:     string (the bio/summary paragraph)
     *  - experiences: [['company','role','start','end','is_current','bullets'=>[]], ...]
     *  - projects:    [['title','url','description','tech'], ...]
     *  - skillGroups: [['category','tags'=>[]], ...]
     *  - educations:  [['degree','institution','start','end'], ...]
     */
    public function gather(): array
    {
        $user = User::query()->with('profile')->first();
        $profile = $user?->profile;

        return [
            'header' => $this->header($user, $profile),
            'profile' => (string) ($profile?->bio ?? ''),
            'experiences' => $this->experiences(),
            'projects' => $this->projects(),
            'skillGroups' => $this->skillGroups(),
            'educations' => $this->educations(),
        ];
    }

    private function header(?User $user, ?Profile $profile): array
    {
        return [
            'name' => (string) ($user?->name ?? ''),
            'tagline' => (string) ($profile?->tagline ?? ''),
            'location' => (string) ($profile?->location ?? ''),
            'phone' => (string) ($profile?->phone ?? ''),
            // secondary_email is the public/contact email; fall back to login email.
            'email' => (string) ($profile?->secondary_email ?: ($user?->email ?? '')),
            'linkedin' => (string) ($profile?->linkedin_url ?? ''),
            'github' => (string) ($profile?->github_url ?? ''),
        ];
    }

    private function experiences(): array
    {
        return Experience::query()
            ->forResume()
            ->active()
            ->ordered()
            ->with(['responsibilities' => fn ($q) => $q->orderBy('sort_order')])
            ->take(3)
            ->get()
            ->map(fn (Experience $e) => [
                'company' => (string) $e->company,
                'role' => (string) $e->role,
                'start' => $e->start_date?->format('Y-m-d') ?? '',
                'end' => $e->end_date?->format('Y-m-d') ?? '',
                'is_current' => (bool) $e->is_current,
                'bullets' => $e->responsibilities
                    ->pluck('description')
                    ->filter(fn ($d) => trim((string) $d) !== '')
                    ->values()
                    ->all(),
            ])
            ->all();
    }

    private function projects(): array
    {
        return Project::query()
            ->featured()
            ->active()
            ->ordered()
            ->take(3)
            ->get()
            ->map(fn (Project $p) => [
                'title' => (string) $p->title,
                'url' => (string) ($p->demo_url ?? ''),
                'description' => (string) ($p->short_description ?? ''),
                'tech' => implode(', ', array_filter((array) ($p->tech_stack ?? []), fn ($t) => trim((string) $t) !== '')),
            ])
            ->all();
    }

    private function skillGroups(): array
    {
        return Skill::groupedByCategory()
            ->map(fn ($skills, string $category) => [
                'category' => $category,
                'tags' => $skills->pluck('title')->values()->all(),
            ])
            ->values()
            ->all();
    }

    private function educations(): array
    {
        return Education::query()
            ->ordered()
            ->get()
            ->map(fn (Education $e) => [
                'degree' => (string) $e->degree_title,
                'institution' => (string) ($e->institution ?? ''),
                'start' => $e->start_date?->format('Y-m-d') ?? '',
                'end' => $e->end_date?->format('Y-m-d') ?? '',
            ])
            ->all();
    }
}
