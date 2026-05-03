<?php

return [
    'demo_mode' => true,

    'server' => [
        'status' => 'online',
        'last_backup_hours_ago' => 6,
        'ssl_valid_until' => '2026-07-15',
        'ping_ms' => 42,
    ],

    'activity' => [
        'visitors_7d' => 1247,
        'visitors_trend' => '+12,4 %',
        'submissions_7d' => 8,
        'page_views_7d' => 4892,
    ],

    'support' => [
        'email' => 'support@ambroseo.de',
        'phone' => null,
        'help_url' => '/hilfe',
        'response_time_hours' => 24,
    ],

    'help' => [
        // Public AMBROSEO Docs-API (liefert JSON zur Doku-Struktur)
        'api_url'    => env('AMBROSEO_DOCS_API_URL', 'https://ambroseo.de/api/v1/docs'),
        'public_url' => env('AMBROSEO_DOCS_PUBLIC_URL', 'https://docs.ambroseo.de'),
        'cache_ttl'  => 600,
        'timeout'    => 5,
    ],

    // AMBROSEO-API fuer den Kunden-Container | wird beim Onboarding gesetzt
    'api' => [
        'base_url'  => env('AMBROSEO_API_URL', 'https://ambroseo.de'),
        'token'     => env('AMBROSEO_API_TOKEN', ''),
        'cache_ttl' => 600,
        'timeout'   => 5,
    ],
];
