<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ensure Livewire/Alpine assets load on every page, including public
        // pages with no Livewire component (e.g. /projects) — needed by the
        // Alpine-only project modal. No-op where they already inject.
        Livewire::forceAssetInjection();
    }
}
