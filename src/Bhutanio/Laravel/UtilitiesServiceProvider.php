<?php

namespace Bhutanio\Laravel;

use Bhutanio\Laravel\Services\Guzzler;
use Bhutanio\Laravel\Services\MetaDataService;
use Illuminate\Support\ServiceProvider;

class UtilitiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Guzzler - http client
        $this->app->singleton(Guzzler::class, function () {
            return new Guzzler;
        });

        // MetaDataService - Meta title, description and theme manager
        $this->app->singleton(MetaDataService::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {

    }
}
