<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SaasCategory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories data with customer layout
        View::composer('saas_customer.saas_layout.saas_layout', function ($view) {
            $navigationCategories = SaasCategory::where('status', true)
                ->with(['subcategories' => function($query) {
                    $query->with(['childCategories' => function($childQuery) {
                        $childQuery->take(6);
                    }])
                    ->take(8);
                }])
                ->orderBy('name')
                ->take(12)
                ->get();

            $view->with('navigationCategories', $navigationCategories);
        });
    }
}
