<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


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
        // Cargar rutas web normales
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Cargar rutas API manualmente
        Route::middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
