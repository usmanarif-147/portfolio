<?php

use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use Illuminate\Support\Facades\Route;

Route::middleware(['admin'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        Route::get('/update-profile', [ProfileController::class, 'index'])->name('update.profile');

        // Education
        Route::get('/educations', [EducationController::class, 'index'])->name('educations');
        Route::get('/education-create', [EducationController::class, 'create'])->name('education.create');
        Route::post('/education-store', [EducationController::class, 'store'])->name('education.store');
        Route::get('/education/{education_id}/edit', [EducationController::class, 'edit'])->name('education.edit');
        Route::post('/education/{education_id}/update', [EducationController::class, 'update'])->name('education.update');
        Route::get('/education/{education_id}/delete', [EducationController::class, 'delete'])->name('education.delete');

        // Experience
        Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences');
        Route::post('/experience-store', [ExperienceController::class, 'store'])->name('experience.store');
        Route::post('/experience/{experience_id}/update', [ExperienceController::class, 'update'])->name('experience.update');
        Route::get('/experience/{experience_id}/delete', [ExperienceController::class, 'delete'])->name('experience.delete');

        // Skill
        Route::get('/skills', [SkillController::class, 'index'])->name('skills');
        Route::post('/skill-store', [SkillController::class, 'store'])->name('skill.store');
        Route::post('/skill/{skill_id}/update', [SkillController::class, 'update'])->name('skill.update');
        Route::get('/skill/{skill_id}/delete', [SkillController::class, 'delete'])->name('skill.delete');

        // Project
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
        Route::post('/project-store', [ProjectController::class, 'store'])->name('project.store');
        Route::post('/project/{project_id}/update', [ProjectController::class, 'update'])->name('project.update');
        Route::get('/project/{project_id}/delete', [ProjectController::class, 'delete'])->name('project.delete');
    });
});
