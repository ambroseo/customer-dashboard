<?php

namespace Ambroseo\CustomerDashboard\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Fetches Doku-Inhalte aus der oeffentlichen AMBROSEO Docs-API.
 * Cached pro Endpoint, damit jeder Customer-Server nicht bei jedem Hilfe-Page-Reload
 * die zentrale Docs-API anpingt.
 *
 * Defensive: bei API-Fehlern werden leere Arrays zurueckgegeben | UI zeigt dann
 * "Doku gerade nicht erreichbar" statt zu crashen.
 */
class HelpDocsService
{
    public function getBooks(): array
    {
        return $this->fetch('books', "{$this->apiUrl()}/books");
    }

    public function getSidebar(): array
    {
        return $this->fetch('sidebar', "{$this->apiUrl()}/sidebar");
    }

    public function getBlogIndex(int $limit = 5): array
    {
        $items = $this->fetch('blog', "{$this->apiUrl()}/blog");

        return array_slice($items, 0, $limit);
    }

    public function publicUrl(?string $bookSlug = null, ?string $chapterSlug = null, ?string $pageSlug = null): string
    {
        $base = rtrim((string) config('ambroseo-dashboard.help.public_url'), '/');

        if (! $bookSlug) {
            return $base;
        }
        if (! $chapterSlug) {
            return "{$base}/{$bookSlug}";
        }
        if (! $pageSlug) {
            return "{$base}/{$bookSlug}/{$chapterSlug}";
        }

        return "{$base}/{$bookSlug}/{$chapterSlug}/{$pageSlug}";
    }

    protected function fetch(string $cacheKey, string $url): array
    {
        $ttl = (int) config('ambroseo-dashboard.help.cache_ttl', 600);
        $timeout = (int) config('ambroseo-dashboard.help.timeout', 5);

        return Cache::remember(
            "ambroseo-dashboard.help.{$cacheKey}",
            $ttl,
            function () use ($url, $timeout): array {
                try {
                    $response = Http::timeout($timeout)->acceptJson()->get($url);
                    if (! $response->successful()) {
                        return [];
                    }

                    return $response->json('data') ?? [];
                } catch (\Throwable) {
                    return [];
                }
            },
        );
    }

    protected function apiUrl(): string
    {
        return rtrim((string) config('ambroseo-dashboard.help.api_url'), '/');
    }
}
