<?php

namespace Ambroseo\CustomerDashboard;

use Ambroseo\CustomerDashboard\Pages\Help;
use Ambroseo\CustomerDashboard\Widgets\ActivityStatsWidget;
use Ambroseo\CustomerDashboard\Widgets\AmbroseoServiceWidget;
use Ambroseo\CustomerDashboard\Widgets\ServerStatusWidget;
use Ambroseo\CustomerDashboard\Widgets\WelcomeWidget;
use Filament\Contracts\Plugin;
use Filament\Panel;

class CustomerDashboardPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'ambroseo-customer-dashboard';
    }

    public function register(Panel $panel): void
    {
        $panel->widgets(self::widgets());
        $panel->pages(self::pages());
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function widgets(): array
    {
        return [
            WelcomeWidget::class,
            ServerStatusWidget::class,
            ActivityStatsWidget::class,
            AmbroseoServiceWidget::class,
        ];
    }

    public static function pages(): array
    {
        return [
            Help::class,
        ];
    }
}
