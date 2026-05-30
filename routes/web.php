<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SharedProjectController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\ProfileEdit;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index']);

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/resume/download', [ResumeController::class, 'download'])->name('resume.download');

Route::get('/shared/project/{token}', [SharedProjectController::class, 'show'])->name('shared.project.show');

Route::get('/update-database', function () {
    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh --seed');
    dd('updated');
});

Route::get('/testing', function () {
    dd('created new branch');
});

// routes
Route::prefix('admin')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));

    Route::get('/login', Login::class)
        ->middleware('guest')
        ->name('admin.login');

    Route::get('/dashboard', Dashboard::class)
        ->middleware('auth')
        ->name('admin.dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', ProfileEdit::class)->name('admin.profile.edit');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->middleware('auth')->name('admin.logout');
});
