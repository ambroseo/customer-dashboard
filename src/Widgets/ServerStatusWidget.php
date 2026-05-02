<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use App\Models\User;
use App\Services\Hosting\HostingDashboardService;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ServerStatusWidget extends BaseWidget
{
    protected static ?int $sort = -5;

    protected function getStats(): array
    {
        $live = $this->liveSnapshot();

        if ($live !== null) {
            return $this->buildLiveStats($live);
        }

        return $this->buildDemoStats();
    }

    protected function liveSnapshot(): ?array
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user instanceof User) {
            return null;
        }

        return app(HostingDashboardService::class)->forUser($user);
    }

    protected function buildLiveStats(array $live): array
    {
        $statusIcon = match ($live['status']) {
            'online'      => 'heroicon-o-check-circle',
            'maintenance' => 'heroicon-o-wrench-screwdriver',
            'offline'     => 'heroicon-o-x-circle',
            'pending'     => 'heroicon-o-clock',
            default       => 'heroicon-o-question-mark-circle',
        };

        $sslDays = $live['ssl_days_left'];
        $sslLabel = $live['ssl_valid_until']
            ? Carbon::parse($live['ssl_valid_until'])->translatedFormat('j. M Y')
            : 'Unbekannt';
        $sslDescription = $sslDays !== null
            ? "Gueltig noch {$sslDays} Tage"
            : 'Konnte nicht geprueft werden';
        $sslColor = $sslDays === null
            ? 'gray'
            : ($sslDays > 30 ? 'success' : ($sslDays > 7 ? 'warning' : 'danger'));

        $ping = $live['ping_ms'];
        $pingValue = $ping !== null ? "{$ping} ms" : 'n/v';
        $pingColor = $ping === null
            ? 'gray'
            : ($ping < 200 ? 'success' : ($ping < 500 ? 'warning' : 'danger'));

        return [
            Stat::make('Server-Status', $live['status_label'])
                ->description('Live aus Coolify')
                ->descriptionIcon($statusIcon)
                ->color($live['status_color']),

            Stat::make('Letztes Backup', 'taeglich 03:00 Uhr')
                ->description('Automatisch ueber Coolify')
                ->descriptionIcon('heroicon-o-arrow-down-tray')
                ->color('primary'),

            Stat::make('SSL-Zertifikat', $sslLabel)
                ->description($sslDescription)
                ->descriptionIcon('heroicon-o-lock-closed')
                ->color($sslColor),

            Stat::make('Antwortzeit', $pingValue)
                ->description('HTTP HEAD auf '.$live['domain'])
                ->descriptionIcon('heroicon-o-bolt')
                ->color($pingColor),
        ];
    }

    protected function buildDemoStats(): array
    {
        $server = config('ambroseo-dashboard.server', []);

        $statusValue = match ($server['status'] ?? 'unknown') {
            'online' => 'Online',
            'maintenance' => 'Wartung',
            'offline' => 'Offline',
            default => 'Unbekannt',
        };

        $statusColor = match ($server['status'] ?? 'unknown') {
            'online' => 'success',
            'maintenance' => 'warning',
            'offline' => 'danger',
            default => 'gray',
        };

        $statusIcon = match ($server['status'] ?? 'unknown') {
            'online' => 'heroicon-o-check-circle',
            'maintenance' => 'heroicon-o-wrench-screwdriver',
            'offline' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle',
        };

        $hours = (int) ($server['last_backup_hours_ago'] ?? 0);
        $backupValue = $hours === 0
            ? 'gerade eben'
            : ($hours === 1 ? 'vor 1 Stunde' : "vor {$hours} Stunden");

        $sslDate = Carbon::parse($server['ssl_valid_until'] ?? now()->addYear());
        $sslDays = (int) now()->diffInDays($sslDate, false);
        $sslColor = $sslDays > 30 ? 'success' : ($sslDays > 7 ? 'warning' : 'danger');

        $ping = (int) ($server['ping_ms'] ?? 0);
        $pingColor = $ping < 100 ? 'success' : ($ping < 300 ? 'warning' : 'danger');

        return [
            Stat::make('Server-Status', $statusValue)
                ->description('Demo-Daten (kein Live-Hosting)')
                ->descriptionIcon($statusIcon)
                ->color($statusColor),

            Stat::make('Letztes Backup', $backupValue)
                ->description('Demo-Daten')
                ->descriptionIcon('heroicon-o-arrow-down-tray')
                ->color('primary'),

            Stat::make('SSL-Zertifikat', $sslDate->translatedFormat('j. M Y'))
                ->description("Gueltig noch {$sslDays} Tage (Demo)")
                ->descriptionIcon('heroicon-o-lock-closed')
                ->color($sslColor),

            Stat::make('Antwortzeit', "{$ping} ms")
                ->description('Demo-Daten')
                ->descriptionIcon('heroicon-o-bolt')
                ->color($pingColor),
        ];
    }
}
