<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActivityStatsWidget extends BaseWidget
{
    protected static ?int $sort = -4;

    protected function getStats(): array
    {
        $activity = config('ambroseo-dashboard.activity', []);

        $visitors = (int) ($activity['visitors_7d'] ?? 0);
        $trend = $activity['visitors_trend'] ?? '0 %';
        $isPositive = str_starts_with($trend, '+');

        $submissions = (int) ($activity['submissions_7d'] ?? 0);
        $views = (int) ($activity['page_views_7d'] ?? 0);
        $viewsPerVisitor = $visitors > 0 ? round($views / $visitors, 1) : 0;

        return [
            Stat::make('Besucher', number_format($visitors, 0, ',', '.'))
                ->description('Letzte 7 Tage')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Trend', $trend)
                ->description($isPositive ? 'Im Vergleich zur Vorwoche' : 'Rückgang zur Vorwoche')
                ->descriptionIcon($isPositive ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($isPositive ? 'success' : 'warning'),

            Stat::make('Anfragen', $submissions)
                ->description('Über das Kontaktformular')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('info'),

            Stat::make('Seitenaufrufe', number_format($views, 0, ',', '.'))
                ->description("Ø {$viewsPerVisitor} Seiten pro Besucher")
                ->descriptionIcon('heroicon-o-eye')
                ->color('warning'),
        ];
    }
}
