<?php

namespace App\Providers;

use App\Services\SaasCartCalculationService;
use App\Services\SaasShippingService;
use App\Services\SaasTaxService;
use Illuminate\Support\ServiceProvider;

class SaasCartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SaasShippingService::class, function ($app) {
            return new SaasShippingService();
        });

        $this->app->singleton(SaasTaxService::class, function ($app) {
            return new SaasTaxService();
        });

        $this->app->singleton(SaasCartCalculationService::class, function ($app) {
            return new SaasCartCalculationService(
                $app->make(SaasShippingService::class),
                $app->make(SaasTaxService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
