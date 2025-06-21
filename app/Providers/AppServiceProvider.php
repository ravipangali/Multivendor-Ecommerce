<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SaasCategory;
use App\Models\SaasSetting;
use App\Models\SaasBanner;

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
        View::composer(['saas_customer.saas_layout.saas_layout', 'saas_customer.saas_layout.saas_partials.saas_mobile_menu'], function ($view) {
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

            // Share settings data
            $settings = SaasSetting::first() ?? new SaasSetting();

            // Share banner data
            $popupBanners = SaasBanner::active()->position('popup')->take(1)->get();
            $footerBanners = SaasBanner::active()->position('footer')->orderBy('id', 'desc')->take(3)->get();

            $view->with([
                'navigationCategories' => $navigationCategories,
                'settings' => $settings,
                'popupBanners' => $popupBanners,
                'promotionalBanners' => $footerBanners
            ]);
        });
    }
}
