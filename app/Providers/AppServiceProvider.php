<?php

namespace App\Providers;

use App\SwApi\SwApi;
use App\SwApi\SwApiClient;
use App\SwApi\SwApiInterface;
use App\SwApi\SwApiCacheDecorator;
use Illuminate\Http\Client\Factory;
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
        #
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(SwApiInterface::class, SwApi::class);

        $this->app->instance(
            SwApiClient::class,
            new SwApiCacheDecorator(
                $this->app->make('cache'),
                $this->app->make(Factory::class)
            )
        );
    }
}
