<?php

namespace Bhutanio\Laravel;

use Bhutanio\Laravel\Console\MakeAbitCommand;
use Bhutanio\Laravel\Services\Gatekeeper;
use Bhutanio\Laravel\Services\Guzzler;
use Bhutanio\Laravel\Services\MetaDataService;
use Illuminate\Support\ServiceProvider;

class AbitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Guzzler - http client
        $this->app->singleton('guzzler', function () {
            return new Guzzler;
        });

        // MetaDataService - Meta title, description and theme manager
        $this->app->singleton('metadata', MetaDataService::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {

    }
}
