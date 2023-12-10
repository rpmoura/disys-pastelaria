<?php

namespace App\Providers;

use App\Services\Client\{ClientService, ClientServiceInterface};
use App\Services\FileManager\{FileManagerService, FileManagerServiceInterface};
use App\Services\Product\{ProductService, ProductServiceInterface};
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(FileManagerServiceInterface::class, FileManagerService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
