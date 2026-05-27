<?php

namespace App\Services;

use App\Models\Project\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectService
{
    public function __construct(private ImageUploadService $images) {}

    public function create(array $data, ?UploadedFile $coverImage = null, array $galleryImages = []): Project
    {
        return DB::transaction(function () use ($data, $coverImage, $galleryImages) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
            unset($data['cover_image']); // set after we have an id (folder = projects/{id}/images)

            $project = Project::create($data);

            if ($coverImage) {
                $project->update([
                    'cover_image' => $this->images->store($coverImage, $this->imageDir($project)),
                ]);
            }

            $this->storeGalleryImages($project, $galleryImages);

            return $project;
        });
    }

    public function update(Project $project, array $data, ?UploadedFile $coverImage = null, array $galleryImages = [], array $removedImageIds = []): Project
    {
        return DB::transaction(function () use ($project, $data, $coverImage, $galleryImages, $removedImageIds) {
            if ($data['title'] !== $project->title) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $project->id);
            }

            if ($coverImage) {
                $this->images->delete($project->cover_image);
                $data['cover_image'] = $this->images->store($coverImage, $this->imageDir($project));
            } elseif (array_key_exists('cover_image', $data) && $data['cover_image'] === null && $project->cover_image) {
                $this->images->delete($project->cover_image);
            }

            $project->update($data);

            // Remove deleted images
            $this->removeImages($project, $removedImageIds);

            // Add new gallery images
            $this->storeGalleryImages($project, $galleryImages);

            return $project;
        });
    }

    public function delete(Project $project): void
    {
        // One shot: removes cover + every gallery image for this project.
        $this->images->deleteDirectory("projects/{$project->id}");

        // project_images rows cascade on delete (FK).
        $project->delete();
    }

    private function imageDir(Project $project): string
    {
        return "projects/{$project->id}/images";
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        $query = Project::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $original.'-'.$count++;
            $query = Project::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    private function storeGalleryImages(Project $project, array $galleryImages): void
    {
        $sortOrder = $project->images()->max('sort_order') ?? 0;

        foreach ($galleryImages as $image) {
            if ($image instanceof UploadedFile) {
                $path = $this->images->store($image, $this->imageDir($project));
                $project->images()->create([
                    'image_path' => $path,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }
    }

    private function removeImages(Project $project, array $imageIds): void
    {
        $images = $project->images()->whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            $this->images->delete($image->image_path);
            $image->delete();
        }
    }
}
