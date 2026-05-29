<?php

use App\Livewire\Admin\Analytics\AnalyticsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/analytics', AnalyticsIndex::class)->name('admin.analytics.index');
