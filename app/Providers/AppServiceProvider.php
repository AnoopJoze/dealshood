<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
            try {
                View::share('siteSettings', Setting::allCached());
            } catch (\Exception $e) {
                // Fail silently during migrations / first install
                View::share('siteSettings', []);
            }
    }
}
