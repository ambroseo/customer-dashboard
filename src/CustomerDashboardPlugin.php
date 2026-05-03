<?php

namespace Ambroseo\CustomerDashboard;

use Ambroseo\CustomerDashboard\Pages\Help;
use Ambroseo\CustomerDashboard\Services\CustomerBrandingService;
use Ambroseo\CustomerDashboard\Widgets\ActivityStatsWidget;
use Ambroseo\CustomerDashboard\Widgets\AmbroseoServiceWidget;
use Ambroseo\CustomerDashboard\Widgets\CustomerChatWidget;
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

        $this->applyBranding($panel);
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
            CustomerChatWidget::class,
            AmbroseoServiceWidget::class,
        ];
    }

    public static function pages(): array
    {
        return [
            Help::class,
        ];
    }

    /**
     * Holt Branding (Logo, Brand-Color, Name) zentral aus der AMBROSEO-API
     * und wendet es auf das Filament-Panel an. Wird beim Plugin-Register aufgerufen.
     * Wenn AMBROSEO_API_TOKEN nicht gesetzt ist (z.B. im AMBROSEO-Hauptpanel selbst),
     * wird das Default-Branding des Panels behalten.
     */
    protected function applyBranding(Panel $panel): void
    {
        if (empty(config('ambroseo-dashboard.api.token'))) {
            return;
        }

        $branding = app(CustomerBrandingService::class)->getBranding();

        if (! empty($branding['primary'])) {
            $panel->colors(['primary' => $branding['primary']]);
        }

        if (! empty($branding['logo_url'])) {
            $panel->brandLogo($branding['logo_url']);
        }

        if (! empty($branding['name'])) {
            $panel->brandName($branding['name']);
        }
    }
}
