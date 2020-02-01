<?php

namespace App\Utils\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Helpers\APIAuth', function()
        {
            return \App\Utils\Helpers\APIAuth::getInstance();
        });
    }
}
