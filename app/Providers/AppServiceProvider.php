<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\DeliveryRepositoryInterface;
use App\Repositories\Eloquent\DeliveryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DeliveryRepositoryInterface::class, DeliveryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
