<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    public function boot()
    {
        // Melampirkan data user ke semua view
        View::composer('*', function ($view) {
            // Ambil data user dari session
            $user = session('user', null); // Default null jika tidak ada data user di session
            $view->with('user', $user);
        });
    }
}
