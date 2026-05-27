<?php

namespace App\Livewire\Admin\Portfolio\ResumeBuilder;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ResumeBuilderIndex extends Component
{
    private const ITEM_LIMITS = [
        'jobs' => 3,
        'projects' => 3,
        'skill_groups' => 7,
        'educations' => 2,
        'job_bullets' => 5,
        'skill_tags' => 10,
    ];

    private const FIELD_LIMITS = [
        'name' => [5, 40],
        'tagline' => [9, 70],
        'phone' => [null, 25],
        'email' => [null, 50],
        'location' => [4, 30],
        'linkedin' => [null, 50],
        'github' => [null, 50],
        'profile' => [null, 650],
        'job.company' => [5, 40],
        'job.role' => [7, 50],
        'job.start' => [null, 20],
        'job.end' => [null, 20],
        'job.bullet' => [null, 200],
        'project.title' => [null, 70],
        'project.url' => [null, 60],
        'project.description' => [null, 250],
        'project.tech' => [null, 120],
        'skill.category' => [4, 30],
        'skill.tag' => [3, 25],
        'education.degree' => [9, 70],
        'education.institution' => [9, 70],
        'education.start' => [1, 9],
        'education.end' => [1, 9],
    ];

    public ?string $openModal = null;

    public array $header = [];

    public string $profile = '';

    public array $experiences = [];

    public array $projects = [];

    public array $skillGroups = [];

    public array $educations = [];

    public array $form = [];

    public function openSection(string $section): void
    {
        $this->openModal = $section;
        $this->form = $this->initialFormFor($section);
    }

    public function closeSection(): void
    {
        $this->openModal = null;
        $this->form = [];
    }

    public function addRow(string $key): void
    {
        if ($this->isRowKeyAtCap($key)) {
            return;
        }
        $this->form[$key][] = $this->blankRowFor($this->openModal, $key);
    }

    public function removeRow(string $key, int $index): void
    {
        if (isset($this->form[$key][$index])) {
            array_splice($this->form[$key], $index, 1);
        }
    }

    public function addBulletToJob(int $jobIndex): void
    {
        if (count($this->form['jobs'][$jobIndex]['bullets'] ?? []) >= self::ITEM_LIMITS['job_bullets']) {
            return;
        }
        $this->form['jobs'][$jobIndex]['bullets'][] = '';
    }

    public function removeBulletFromJob(int $jobIndex, int $bulletIndex): void
    {
        if (isset($this->form['jobs'][$jobIndex]['bullets'][$bulletIndex])) {
            array_splice($this->form['jobs'][$jobIndex]['bullets'], $bulletIndex, 1);
        }
    }

    public function addTagToGroup(int $groupIndex): void
    {
        if (count($this->form['groups'][$groupIndex]['tags'] ?? []) >= self::ITEM_LIMITS['skill_tags']) {
            return;
        }
        $this->form['groups'][$groupIndex]['tags'][] = '';
    }

    public function removeTagFromGroup(int $groupIndex, int $tagIndex): void
    {
        if (isset($this->form['groups'][$groupIndex]['tags'][$tagIndex])) {
            array_splice($this->form['groups'][$groupIndex]['tags'], $tagIndex, 1);
        }
    }

    public function save(): void
    {
        if (! $this->isFormValid()) {
            return;
        }

        match ($this->openModal) {
            'header' => $this->header = $this->form,
            'profile' => $this->profile = (string) ($this->form['summary'] ?? ''),
            'work' => $this->experiences = array_values($this->form['jobs'] ?? []),
            'projects' => $this->projects = array_values($this->form['projects'] ?? []),
            'skills' => $this->skillGroups = array_values($this->form['groups'] ?? []),
            'education' => $this->educations = array_values($this->form['entries'] ?? []),
            default => null,
        };

        $this->closeSection();
    }

    public function isFormValid(): bool
    {
        if ($this->openModal === null) {
            return true;
        }

        return match ($this->openModal) {
            'header' => $this->checkFields([
                'name' => $this->form['name'] ?? '',
                'tagline' => $this->form['tagline'] ?? '',
                'phone' => $this->form['phone'] ?? '',
                'email' => $this->form['email'] ?? '',
                'location' => $this->form['location'] ?? '',
                'linkedin' => $this->form['linkedin'] ?? '',
                'github' => $this->form['github'] ?? '',
            ]),
            'profile' => $this->checkFields([
                'profile' => $this->form['summary'] ?? '',
            ]),
            'work' => $this->checkJobs(),
            'projects' => $this->checkProjects(),
            'skills' => $this->checkSkills(),
            'education' => $this->checkEducation(),
            default => true,
        };
    }

    private function checkFields(array $fields): bool
    {
        foreach ($fields as $limitKey => $value) {
            if ($this->isValueOverLimit((string) $value, self::FIELD_LIMITS[$limitKey] ?? [null, null])) {
                return false;
            }
        }

        return true;
    }

    private function checkJobs(): bool
    {
        foreach ($this->form['jobs'] ?? [] as $job) {
            $ok = $this->checkFields([
                'job.company' => $job['company'] ?? '',
                'job.role' => $job['role'] ?? '',
                'job.start' => $job['start'] ?? '',
                'job.end' => $job['end'] ?? '',
            ]);
            if (! $ok) {
                return false;
            }
            foreach ($job['bullets'] ?? [] as $bullet) {
                if ($this->isValueOverLimit((string) $bullet, self::FIELD_LIMITS['job.bullet'])) {
                    return false;
                }
            }
        }

        return true;
    }

    private function checkProjects(): bool
    {
        foreach ($this->form['projects'] ?? [] as $project) {
            $ok = $this->checkFields([
                'project.title' => $project['title'] ?? '',
                'project.url' => $project['url'] ?? '',
                'project.description' => $project['description'] ?? '',
                'project.tech' => $project['tech'] ?? '',
            ]);
            if (! $ok) {
                return false;
            }
        }

        return true;
    }

    private function checkSkills(): bool
    {
        foreach ($this->form['groups'] ?? [] as $group) {
            if ($this->isValueOverLimit((string) ($group['category'] ?? ''), self::FIELD_LIMITS['skill.category'])) {
                return false;
            }
            foreach ($group['tags'] ?? [] as $tag) {
                if ($this->isValueOverLimit((string) $tag, self::FIELD_LIMITS['skill.tag'])) {
                    return false;
                }
            }
        }

        return true;
    }

    private function checkEducation(): bool
    {
        foreach ($this->form['entries'] ?? [] as $entry) {
            $ok = $this->checkFields([
                'education.degree' => $entry['degree'] ?? '',
                'education.institution' => $entry['institution'] ?? '',
                'education.start' => $entry['start'] ?? '',
                'education.end' => $entry['end'] ?? '',
            ]);
            if (! $ok) {
                return false;
            }
        }

        return true;
    }

    private function isValueOverLimit(string $value, array $limit): bool
    {
        [$maxWords, $maxChars] = $limit;
        if ($maxChars !== null && mb_strlen($value) > $maxChars) {
            return true;
        }
        if ($maxWords !== null && $this->wordCount($value) > $maxWords) {
            return true;
        }

        return false;
    }

    private function wordCount(string $value): int
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return 0;
        }

        return count(preg_split('/\s+/', $trimmed));
    }

    private function isRowKeyAtCap(string $key): bool
    {
        $cap = match (true) {
            $this->openModal === 'work' && $key === 'jobs' => self::ITEM_LIMITS['jobs'],
            $this->openModal === 'projects' && $key === 'projects' => self::ITEM_LIMITS['projects'],
            $this->openModal === 'skills' && $key === 'groups' => self::ITEM_LIMITS['skill_groups'],
            $this->openModal === 'education' && $key === 'entries' => self::ITEM_LIMITS['educations'],
            default => null,
        };

        return $cap !== null && count($this->form[$key] ?? []) >= $cap;
    }

    private function initialFormFor(string $section): array
    {
        return match ($section) {
            'header' => $this->header !== [] ? $this->header : [
                'name' => '',
                'tagline' => '',
                'phone' => '',
                'email' => '',
                'location' => '',
                'linkedin' => '',
                'github' => '',
            ],
            'profile' => ['summary' => $this->profile],
            'work' => ['jobs' => $this->experiences !== [] ? $this->experiences : [$this->blankJob()]],
            'projects' => ['projects' => $this->projects !== [] ? $this->projects : [$this->blankProject()]],
            'skills' => ['groups' => $this->skillGroups !== [] ? $this->skillGroups : [$this->blankSkillGroup()]],
            'education' => ['entries' => $this->educations !== [] ? $this->educations : [$this->blankEducation()]],
            default => [],
        };
    }

    private function blankRowFor(?string $section, string $key): array|string
    {
        return match (true) {
            $section === 'work' && $key === 'jobs' => $this->blankJob(),
            $section === 'projects' && $key === 'projects' => $this->blankProject(),
            $section === 'skills' && $key === 'groups' => $this->blankSkillGroup(),
            $section === 'education' && $key === 'entries' => $this->blankEducation(),
            default => '',
        };
    }

    private function blankJob(): array
    {
        return [
            'company' => '',
            'role' => '',
            'start' => '',
            'end' => '',
            'is_current' => false,
            'bullets' => [''],
        ];
    }

    private function blankProject(): array
    {
        return [
            'title' => '',
            'url' => '',
            'description' => '',
            'tech' => '',
        ];
    }

    private function blankSkillGroup(): array
    {
        return [
            'category' => '',
            'tags' => [''],
        ];
    }

    private function blankEducation(): array
    {
        return [
            'degree' => '',
            'institution' => '',
            'start' => '',
            'end' => '',
        ];
    }

    public function loadSampleData(): void
    {
        $data = require __DIR__.'/sample-data.php';

        $this->header = $data['header'] ?? [];
        $this->profile = (string) ($data['profile'] ?? '');
        $this->experiences = $data['experiences'] ?? [];
        $this->projects = $data['projects'] ?? [];
        $this->skillGroups = $data['skill_groups'] ?? [];
        $this->educations = $data['educations'] ?? [];
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('resume.templates.builder', [
            'header' => $this->header,
            'profile' => $this->profile,
            'experiences' => $this->experiences,
            'projects' => $this->projects,
            'skillGroups' => $this->skillGroups,
            'educations' => $this->educations,
        ])->setPaper('A4', 'portrait');

        $name = trim((string) ($this->header['name'] ?? ''));
        $filename = $name !== '' ? Str::slug($name).'.pdf' : 'resume.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename
        );
    }

    public function render()
    {
        return view('livewire.admin.portfolio.resume-builder.index', [
            'itemLimits' => self::ITEM_LIMITS,
            'fieldLimits' => self::FIELD_LIMITS,
        ]);
    }
}
