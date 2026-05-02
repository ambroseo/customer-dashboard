<?php

namespace Ambroseo\CustomerDashboard;

use Illuminate\Support\ServiceProvider;

class CustomerDashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/ambroseo-dashboard.php',
            'ambroseo-dashboard',
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'ambroseo-customer-dashboard',
        );

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ambroseo-dashboard.php' => config_path('ambroseo-dashboard.php'),
            ], 'ambroseo-customer-dashboard-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/ambroseo-customer-dashboard'),
            ], 'ambroseo-customer-dashboard-views');
        }
    }
}
