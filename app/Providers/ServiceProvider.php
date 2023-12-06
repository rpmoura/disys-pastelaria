<?php

namespace App\Providers;

use App\Services\Client\{ClientService, ClientServiceInterface};
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
