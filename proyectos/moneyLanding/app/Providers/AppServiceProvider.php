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
        // Compartir nombre del negocio con todas las vistas
        view()->composer('*', function ($view) {
            $settings = \App\Models\BusinessSetting::first();
            config(['app.name' => $settings->business_name ?? 'Lending Money']);
        });
    }
}
