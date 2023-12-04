<?php

namespace App\Providers;

use App\Repositories\Client\{ClientRepository, ClientRepositoryInterface};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
