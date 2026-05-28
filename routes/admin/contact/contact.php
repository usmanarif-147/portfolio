<?php

use App\Livewire\Admin\Contact\ContactIndex;
use Illuminate\Support\Facades\Route;

Route::get('/contact', ContactIndex::class)->name('admin.contact.index');
