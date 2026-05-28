<?php

namespace App\Livewire\Admin\Portfolio\Projects;

use App\Models\Project\Project;
use App\Services\ImageUploadService;
use App\Services\ProjectService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ProjectForm extends Component
{
    use WithFileUploads;

    public ?Project $project = null;

    public string $title = '';

    public string $short_description = '';

    public string $description = '';

    public array $tech_stack = [];

    public string $techInput = '';

    public string $demo_url = '';

    public string $github_url = '';

    public bool $is_featured = false;

    public bool $is_for_resume = false;

    public int $sort_order = 0;

    public bool $is_active = true;

    public ?string $completed_at = null;

    // Image handling
    public $coverImage = null;

    public ?string $existingCoverImage = null;

    public bool $removeCover = false;

    public array $galleryImages = [];

    public array $existingImages = [];

    public array $removedImageIds = [];

    // Optimization state (before/after sizes + Save gating)
    public bool $optimizing = false;

    public ?array $coverInfo = null;

    public array $galleryInfo = [];

    public function mount(?Project $project = null): void
    {
        if ($project && $project->exists) {
            $this->project = $project;
            $this->title = $project->title;
            $this->short_description = $project->short_description;
            $this->description = $project->description ?? '';
            $this->tech_stack = $project->tech_stack ?? [];
            $this->demo_url = $project->demo_url ?? '';
            $this->github_url = $project->github_url ?? '';
            $this->is_featured = $project->is_featured;
            $this->is_for_resume = $project->is_for_resume;
            $this->sort_order = $project->sort_order ?? 0;
            $this->is_active = $project->is_active;
            $this->completed_at = $project->completed_at?->format('Y-m-d');
            $this->existingCoverImage = $project->cover_image;

            $this->existingImages = $project->images()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($img) => [
                    'id' => $img->id,
                    'image_path' => $img->image_path,
                    'alt_text' => $img->alt_text,
                    'sort_order' => $img->sort_order,
                ])
                ->toArray();
        }
    }

    public function addTech(): void
    {
        if (count($this->tech_stack) >= 8) {
            return;
        }

        $tech = trim($this->techInput);

        if ($tech !== '') {
            $this->tech_stack[] = $tech;
            $this->techInput = '';
        }
    }

    public function removeTech(int $index): void
    {
        array_splice($this->tech_stack, $index, 1);
    }

    public function updatedCoverImage(): void
    {
        $this->coverInfo = null;

        if (! $this->coverImage) {
            return;
        }

        $this->validate(['coverImage' => 'image|max:8192|mimes:jpg,jpeg,png,webp']);

        $this->optimizing = true;
        $this->coverInfo = app(ImageUploadService::class)->sizeInfo($this->coverImage);
        $this->optimizing = false;
    }

    public function updatedGalleryImages(): void
    {
        $this->galleryInfo = [];

        if (empty($this->galleryImages)) {
            return;
        }

        $this->validate(['galleryImages.*' => 'image|max:8192|mimes:jpg,jpeg,png,webp']);

        $this->optimizing = true;
        $service = app(ImageUploadService::class);
        foreach ($this->galleryImages as $image) {
            $this->galleryInfo[] = $service->sizeInfo($image);
        }
        $this->optimizing = false;
    }

    public function removeCoverImage(): void
    {
        $this->coverImage = null;
        $this->existingCoverImage = null;
        $this->removeCover = true;
        $this->coverInfo = null;
    }

    public function removeExistingImage(int $id): void
    {
        $this->removedImageIds[] = $id;
        $this->existingImages = array_values(
            array_filter($this->existingImages, fn ($img) => $img['id'] !== $id)
        );
    }

    public function removeGalleryImage(int $index): void
    {
        array_splice($this->galleryImages, $index, 1);
        array_splice($this->galleryInfo, $index, 1);
    }

    public function save(ProjectService $service): void
    {
        $keptExistingCount = count($this->existingImages) - count(array_unique($this->removedImageIds));
        $totalAfterSave = $keptExistingCount + count($this->galleryImages);

        if ($totalAfterSave > 8) {
            $this->addError('galleryImages', 'Gallery can have at most 8 images. Currently selected: '.$totalAfterSave.'.');

            return;
        }

        // Cap-3 guard: turning is_for_resume on must not exceed 3 (excluding self on edit).
        if ($this->is_for_resume) {
            $onCount = Project::query()->forResume()
                ->when($this->project, fn ($q) => $q->where('id', '!=', $this->project->id))
                ->count();
            if ($onCount >= 3) {
                $this->addError('is_for_resume', 'Resume already has 3 projects. Turn one off first.');

                return;
            }
        }

        $validated = $this->validate([
            'title' => 'required|string|max:200',
            'short_description' => 'required|string|max:190',
            'description' => 'nullable|string',
            'tech_stack' => 'array|max:8',
            'demo_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
            'is_for_resume' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
            'completed_at' => 'nullable|date',
            'coverImage' => 'nullable|image|max:8192|mimes:jpg,jpeg,png,webp',
            'galleryImages' => 'array|max:8',
            'galleryImages.*' => 'image|max:8192|mimes:jpg,jpeg,png,webp',
        ]);

        $projectData = collect($validated)->except(['coverImage', 'galleryImages'])->toArray();

        if ($this->removeCover && ! $this->coverImage) {
            $projectData['cover_image'] = null;
        }

        if ($this->project) {
            $service->update(
                $this->project,
                $projectData,
                $this->coverImage,
                $this->galleryImages,
                $this->removedImageIds,
            );
            $message = 'Project updated successfully.';
        } else {
            $service->create(
                $projectData,
                $this->coverImage,
                $this->galleryImages,
            );
            $message = 'Project created successfully.';
        }

        session()->flash('success', $message);
        $this->redirect(route('admin.projects.index'), navigate: true);
    }

    public function shortDescriptionMaxLength(): int
    {
        return 190;
    }

    public function maxTechTags(): int
    {
        return 8;
    }

    public function render()
    {
        return view('livewire.admin.portfolio.projects.form');
    }
}
