<?php

namespace App\Providers;

use App\Repositories\Client\{ClientRepository, ClientRepositoryInterface};
use App\Repositories\Order\{OrderRepository, OrderRepositoryInterface};
use App\Repositories\Product\{ProductRepository, ProductRepositoryInterface};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
