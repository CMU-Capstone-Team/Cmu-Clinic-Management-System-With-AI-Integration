<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // ✅ Nasa taas ang import

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
        // ✅ Isang boot() method lang at nasa loob ng class
        
        // Force Laravel to load migrations from subdirectories
        $this->loadMigrationsFrom(database_path('migrations/students'));
        $this->loadMigrationsFrom(database_path('migrations/visits'));
        $this->loadMigrationsFrom(database_path('migrations/excuses'));
        
        // Optional: Fix for older MySQL versions regarding string length
        Schema::defaultStringLength(191);
    }
}