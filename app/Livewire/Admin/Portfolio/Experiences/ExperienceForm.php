<?php

namespace App\Livewire\Admin\Portfolio\Experiences;

use App\Models\Experience\Experience;
use App\Services\ExperienceService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ExperienceForm extends Component
{
    public const MAX_RESPONSIBILITIES = 5;

    private const RESP_BULLET_LIMITS = [1 => 720, 2 => 360, 3 => 235, 4 => 180, 5 => 145];

    public ?Experience $experience = null;

    public string $role = '';

    public string $company = '';

    public ?string $start_date = null;

    public ?string $end_date = null;

    public bool $is_current = false;

    public string $description = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public bool $is_for_resume = false;

    public array $responsibilities = [];

    public function mount(?Experience $experience = null): void
    {
        if ($experience && $experience->exists) {
            $this->experience = $experience;
            $this->role = $experience->role;
            $this->company = $experience->company;
            $this->start_date = $experience->start_date?->format('Y-m-d');
            $this->end_date = $experience->end_date?->format('Y-m-d');
            $this->is_current = $experience->is_current;
            $this->description = $experience->description ?? '';
            $this->sort_order = $experience->sort_order ?? 0;
            $this->is_active = $experience->is_active;
            $this->is_for_resume = $experience->is_for_resume;

            $this->responsibilities = $experience->responsibilities()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'description' => $r->description,
                    'sort_order' => $r->sort_order,
                ])
                ->toArray();
        }
    }

    public function maxResponsibilities(): int
    {
        return self::MAX_RESPONSIBILITIES;
    }

    public function responsibilityCharLimit(): int
    {
        $n = count($this->responsibilities);
        $n = max(1, min(self::MAX_RESPONSIBILITIES, $n));

        return self::RESP_BULLET_LIMITS[$n];
    }

    public function addResponsibility(): void
    {
        if (count($this->responsibilities) >= self::MAX_RESPONSIBILITIES) {
            return;
        }

        $this->responsibilities[] = [
            'id' => null,
            'description' => '',
            'sort_order' => count($this->responsibilities),
        ];
    }

    public function removeResponsibility(int $index): void
    {
        array_splice($this->responsibilities, $index, 1);
    }

    public function save(ExperienceService $service): void
    {
        $bulletLimit = $this->responsibilityCharLimit();

        // Cap-3 guard: turning is_for_resume on must not exceed 3 (excluding self on edit).
        if ($this->is_for_resume) {
            $onCount = Experience::query()->forResume()
                ->when($this->experience, fn ($q) => $q->where('id', '!=', $this->experience->id))
                ->count();
            if ($onCount >= 3) {
                $this->addError('is_for_resume', 'Resume already has 3 experiences. Turn one off first.');

                return;
            }
        }

        $validated = $this->validate([
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'is_for_resume' => 'boolean',
            'responsibilities' => 'array|max:'.self::MAX_RESPONSIBILITIES,
            'responsibilities.*.description' => ['required', 'string', function ($attribute, $value, $fail) use ($bulletLimit) {
                if (mb_strlen($value) > $bulletLimit) {
                    $fail("Each bullet must be {$bulletLimit} characters or fewer when this job has ".count($this->responsibilities).' bullet(s).');
                }
            }],
            'responsibilities.*.sort_order' => 'integer|min:0',
        ]);

        if ($this->is_current) {
            $validated['end_date'] = null;
        }

        $experienceData = collect($validated)->except('responsibilities')->toArray();
        $responsibilities = $validated['responsibilities'] ?? [];

        if ($this->experience) {
            $service->update($this->experience, $experienceData, $responsibilities);
            $message = 'Experience updated successfully.';
        } else {
            $service->create($experienceData, $responsibilities);
            $message = 'Experience created successfully.';
        }

        session()->flash('success', $message);
        $this->redirect(route('admin.experiences.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.portfolio.experiences.form');
    }
}
