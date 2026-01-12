<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share constants to all views
        view()->share('constants', config('constants'));
        
        // Share categories from database to all views
        view()->composer('*', function ($view) {
            $view->with('categories', \App\Models\Category::where('status', true)->get());
        });
    }
}
