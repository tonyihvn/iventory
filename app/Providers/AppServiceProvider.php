<?php

namespace App\Providers;
use App\settings;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        view()->composer('*',function($view) {
            $site_settings = settings::first();
            $view->with('site_settings', $site_settings); 
        });
    }
}
