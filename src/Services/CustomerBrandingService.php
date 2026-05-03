<?php

namespace Ambroseo\CustomerDashboard\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Holt Branding-Infos (Logo, Farben, Domain, Name) der Kunden-Webseite zentral
 * aus der AMBROSEO-API. Wird einmalig beim Filament-Panel-Boot aufgerufen, damit
 * Logo + Brand-Color dynamisch pro Container kommen koennen.
 *
 * Cache: 10min (Branding-Aenderungen sind selten). Bei API-Fehler returnt der
 * Service ein Default-Branding (AMBROSEO-Lila), damit die App beim API-Ausfall
 * nicht crasht.
 */
class CustomerBrandingService
{
    /**
     * @return array{name:string,domain:?string,admin_url:?string,primary:string,secondary:?string,logo_url:?string}
     */
    public function getBranding(): array
    {
        $ttl = (int) config('ambroseo-dashboard.api.cache_ttl', 600);

        return Cache::remember(
            'ambroseo-dashboard.branding',
            $ttl,
            fn () => $this->fetch(),
        );
    }

    public function flush(): void
    {
        Cache::forget('ambroseo-dashboard.branding');
    }

    protected function fetch(): array
    {
        $base = rtrim((string) config('ambroseo-dashboard.api.base_url'), '/');
        $token = (string) config('ambroseo-dashboard.api.token');
        $timeout = (int) config('ambroseo-dashboard.api.timeout', 5);

        if ($base === '' || $token === '') {
            return $this->fallback();
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout($timeout)
                ->get("{$base}/api/v1/customer/branding");

            if (! $response->successful()) {
                Log::warning('Customer branding fetch failed', [
                    'status' => $response->status(),
                    'body'   => mb_strimwidth($response->body(), 0, 200),
                ]);
                return $this->fallback();
            }

            $data = $response->json();

            return [
                'name'      => (string) ($data['name'] ?? 'Mein Bereich'),
                'domain'    => $data['domain'] ?? null,
                'admin_url' => $data['admin_url'] ?? null,
                'primary'   => (string) ($data['primary'] ?? '#240145'),
                'secondary' => $data['secondary'] ?? null,
                'logo_url'  => $data['logo_url'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Customer branding fetch exception', [
                'error' => $e->getMessage(),
            ]);
            return $this->fallback();
        }
    }

    protected function fallback(): array
    {
        return [
            'name'      => (string) config('app.name', 'Mein Bereich'),
            'domain'    => null,
            'admin_url' => null,
            'primary'   => '#240145',
            'secondary' => null,
            'logo_url'  => null,
        ];
    }
}
