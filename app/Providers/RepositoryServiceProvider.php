<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Eloquent\EloquentProductRepository;
use App\Repositories\Eloquent\EloquentOrderRepository;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Impl\OrderService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class, 
            EloquentProductRepository::class);
        $this->app->bind(
            OrderRepositoryInterface::class, 
            EloquentOrderRepository::class);
        $this->app->bind(
            OrderServiceInterface::class, 
            OrderService::class);
    }

    public function boot(): void
    {
        //
    }
}
