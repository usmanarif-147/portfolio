<?php

use App\Livewire\Admin\IndusGas\ComingSoon;
use App\Livewire\Admin\IndusGas\Customers\CustomerForm;
use App\Livewire\Admin\IndusGas\Customers\CustomerIndex;
use Illuminate\Support\Facades\Route;

Route::get('/indus-gas/dashboard', ComingSoon::class)
    ->defaults('feature', 'Dashboard')
    ->name('admin.indus-gas.dashboard');

Route::get('/indus-gas/customers', CustomerIndex::class)->name('admin.indus-gas.customers');
Route::get('/indus-gas/customers/create', CustomerForm::class)->name('admin.indus-gas.customers.create');
Route::get('/indus-gas/customers/{customer}/edit', CustomerForm::class)->name('admin.indus-gas.customers.edit');

Route::get('/indus-gas/bbn-plant', ComingSoon::class)
    ->defaults('feature', 'BBN Plant')
    ->name('admin.indus-gas.bbn-plant');

Route::get('/indus-gas/fas-tube', ComingSoon::class)
    ->defaults('feature', 'FAS Tube')
    ->name('admin.indus-gas.fas-tube');

Route::get('/indus-gas/expenses', ComingSoon::class)
    ->defaults('feature', 'Expenses')
    ->name('admin.indus-gas.expenses');
