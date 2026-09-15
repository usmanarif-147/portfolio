<?php

use App\Livewire\Admin\IndusGas\ComingSoon;
use Illuminate\Support\Facades\Route;

Route::get('/indus-gas/dashboard', ComingSoon::class)
    ->defaults('feature', 'Dashboard')
    ->name('admin.indus-gas.dashboard');

Route::get('/indus-gas/customers', ComingSoon::class)
    ->defaults('feature', 'Customers')
    ->name('admin.indus-gas.customers');

Route::get('/indus-gas/bbn-plant', ComingSoon::class)
    ->defaults('feature', 'BBN Plant')
    ->name('admin.indus-gas.bbn-plant');

Route::get('/indus-gas/fas-tube', ComingSoon::class)
    ->defaults('feature', 'FAS Tube')
    ->name('admin.indus-gas.fas-tube');

Route::get('/indus-gas/expenses', ComingSoon::class)
    ->defaults('feature', 'Expenses')
    ->name('admin.indus-gas.expenses');
