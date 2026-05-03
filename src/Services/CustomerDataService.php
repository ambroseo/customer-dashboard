<?php

namespace Ambroseo\CustomerDashboard\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Liefert Live-Daten fuer die Widgets des Customer-Dashboards.
 *
 * Zwei Modi:
 *
 * 1) **Kunden-Container** (AMBROSEO_API_TOKEN gesetzt) | ruft die zentrale AMBROSEO-API
 *    via Sanctum-PAT. Endpoints: /server-status, /invoices, /messages.
 *
 * 2) **AMBROSEO-Hauptpanel** (kein Token) | nutzt direkt die App-internen Services
 *    (HostingDashboardService, Invoice-Modell). Dadurch laeuft das gleiche Plugin
 *    sowohl im Hauptpanel als auch in jedem Kunden-Container.
 *
 * Bei Fehlern in beiden Pfaden returnt die Methode `null` | Widgets zeigen dann
 * Demo-Daten oder Empty-States.
 */
class CustomerDataService
{
    public function serverStatus(): ?array
    {
        $cacheTtl = (int) config('ambroseo-dashboard.api.cache_ttl', 60);

        return Cache::remember(
            'ambroseo-dashboard.server-status',
            min($cacheTtl, 60),
            fn () => $this->fetchServerStatus(),
        );
    }

    public function invoices(): array
    {
        $cacheTtl = (int) config('ambroseo-dashboard.api.cache_ttl', 60);

        return Cache::remember(
            'ambroseo-dashboard.invoices',
            min($cacheTtl, 60),
            fn () => $this->fetchInvoices(),
        );
    }

    public function messages(?string $since = null): array
    {
        if (! $this->hasApiToken()) {
            return ['messages' => []];
        }

        try {
            $response = $this->httpClient()->get($this->apiUrl('messages'), array_filter(['since' => $since]));

            if (! $response->successful()) {
                Log::warning('CustomerDataService::messages failed', ['status' => $response->status()]);
                return ['messages' => []];
            }

            return $response->json() ?? ['messages' => []];
        } catch (\Throwable $e) {
            Log::warning('CustomerDataService::messages exception', ['error' => $e->getMessage()]);
            return ['messages' => []];
        }
    }

    public function sendMessage(string $body): bool
    {
        if (! $this->hasApiToken() || trim($body) === '') {
            return false;
        }

        try {
            $response = $this->httpClient()->post($this->apiUrl('messages'), ['body' => trim($body)]);
            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('CustomerDataService::sendMessage exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected function fetchServerStatus(): ?array
    {
        if ($this->hasApiToken()) {
            return $this->apiGet('server-status');
        }

        // AMBROSEO-Hauptpanel-Pfad
        if (class_exists('App\\Services\\Hosting\\HostingDashboardService') && class_exists('App\\Models\\User')) {
            $user = Auth::user();
            if (is_a($user, 'App\\Models\\User')) {
                return app('App\\Services\\Hosting\\HostingDashboardService')->forUser($user);
            }
        }

        return null;
    }

    protected function fetchInvoices(): array
    {
        if ($this->hasApiToken()) {
            return $this->apiGet('invoices') ?? ['open_count' => 0, 'open_amount' => 0.0, 'invoices' => []];
        }

        // AMBROSEO-Hauptpanel-Pfad: direkt Modul-Invoice abfragen
        if (class_exists('Modules\\Billing\\Models\\Invoice') && class_exists('App\\Models\\User')) {
            $user = Auth::user();

            if (is_a($user, 'App\\Models\\User')) {
                /** @var object{client?:object} $user */
                $clientId = $user->client?->id ?? null;

                if ($clientId) {
                    /** @var class-string<\Illuminate\Database\Eloquent\Model> $cls */
                    $cls = 'Modules\\Billing\\Models\\Invoice';
                    $query = $cls::where('client_id', $clientId)->whereIn('status', ['sent', 'overdue']);
                    return [
                        'open_count'  => (clone $query)->count(),
                        'open_amount' => (float) (clone $query)->sum('total'),
                        'invoices'    => [],
                    ];
                }
            }
        }

        return ['open_count' => 0, 'open_amount' => 0.0, 'invoices' => []];
    }

    protected function apiGet(string $path): ?array
    {
        try {
            $response = $this->httpClient()->get($this->apiUrl($path));

            if (! $response->successful()) {
                Log::warning('CustomerDataService API failed', [
                    'path' => $path,
                    'status' => $response->status(),
                ]);
                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::warning('CustomerDataService API exception', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    protected function httpClient()
    {
        $token = (string) config('ambroseo-dashboard.api.token');
        $timeout = (int) config('ambroseo-dashboard.api.timeout', 5);

        return Http::withToken($token)
            ->acceptJson()
            ->timeout($timeout);
    }

    protected function apiUrl(string $path): string
    {
        $base = rtrim((string) config('ambroseo-dashboard.api.base_url'), '/');
        return "{$base}/api/v1/customer/{$path}";
    }

    protected function hasApiToken(): bool
    {
        return ! empty(config('ambroseo-dashboard.api.token'));
    }
}
