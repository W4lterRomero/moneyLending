<?php

namespace App\Providers;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        $businessName = config('app.name', 'Lending Money');

        if (Schema::hasTable('business_settings')) {
            $settings = BusinessSetting::first();
            $businessName = $settings->business_name ?? $businessName;
        }

        config(['app.name' => $businessName]);
        View::share('appName', $businessName);
    }
}
