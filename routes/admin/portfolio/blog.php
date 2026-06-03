<?php

use App\Http\Controllers\BlogController;
use App\Livewire\Admin\Portfolio\Blog\BlogPostForm;
use App\Livewire\Admin\Portfolio\Blog\BlogPostIndex;
use Illuminate\Support\Facades\Route;

Route::get('/blog', BlogPostIndex::class)->name('admin.blog.index');
Route::get('/blog/create', BlogPostForm::class)->name('admin.blog.create');
Route::get('/blog/{blogPost}/edit', BlogPostForm::class)->name('admin.blog.edit');
Route::get('/blog/{blogPost}/preview', [BlogController::class, 'preview'])->name('admin.blog.preview');
Route::get('/blog/comparison/create', fn () => abort(404, 'Comparison coming soon'))->name('admin.blog.comparison.create');
