<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class WelcomeWidget extends Widget
{
    protected string $view = 'ambroseo-customer-dashboard::widgets.welcome-widget';

    protected static ?int $sort = -10;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        Carbon::setLocale('de');

        $user = auth()->user();
        $name = $user?->name ?? 'Kunde';
        $firstName = explode(' ', trim($name))[0];
        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'Guten Morgen',
            $hour < 18 => 'Guten Tag',
            default => 'Guten Abend',
        };

        return [
            'greeting' => $greeting,
            'firstName' => $firstName,
            'initials' => $this->getInitials($name),
            'today' => now()->translatedFormat('l, j. F Y'),
            'time' => now()->format('H:i'),
        ];
    }

    private function getInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_substr($part, 0, 1);
        }

        return mb_strtoupper($initials ?: 'K');
    }
}
