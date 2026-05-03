<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use Ambroseo\CustomerDashboard\Services\CustomerDataService;
use Carbon\Carbon;
use Filament\Widgets\Widget;

/**
 * Chat-Widget zwischen Kunden und AMBROSEO. Pollt alle 30s neue Nachrichten,
 * sendet ueber CustomerDataService an die zentrale AMBROSEO-API.
 *
 * Wird nur angezeigt wenn AMBROSEO_API_TOKEN gesetzt ist (also nur in
 * Kunden-Containern, nicht im AMBROSEO-Hauptpanel selbst | dort hat Vincent
 * stattdessen die /admin/customer-inbox-Page).
 */
class CustomerChatWidget extends Widget
{
    protected string $view = 'ambroseo-customer-dashboard::widgets.customer-chat-widget';

    protected static ?int $sort = -2;

    protected int | string | array $columnSpan = 'full';

    public string $newMessage = '';

    public static function canView(): bool
    {
        return ! empty(config('ambroseo-dashboard.api.token'));
    }

    public function sendMessage(): void
    {
        $body = trim($this->newMessage);

        if (mb_strlen($body) < 2 || mb_strlen($body) > 5000) {
            return;
        }

        $ok = app(CustomerDataService::class)->sendMessage($body);

        if ($ok) {
            $this->newMessage = '';
            \Illuminate\Support\Facades\Cache::forget('ambroseo-dashboard.messages');
            $this->dispatch('chat-message-sent');
        }
    }

    public function refreshChat(): void
    {
        \Illuminate\Support\Facades\Cache::forget('ambroseo-dashboard.messages');
    }

    protected function getViewData(): array
    {
        $service = app(CustomerDataService::class);
        $payload = $service->messages();
        $raw = $payload['messages'] ?? [];

        $grouped = [];
        $currentDate = null;

        foreach ($raw as $msg) {
            $created = Carbon::parse($msg['created_at']);
            $dateLabel = $this->formatDateLabel($created);

            if ($dateLabel !== $currentDate) {
                $currentDate = $dateLabel;
                $grouped[] = ['type' => 'date', 'label' => $dateLabel];
            }

            $grouped[] = [
                'type'         => 'message',
                'id'           => $msg['id'],
                'body'         => $msg['body'],
                'fromCustomer' => ($msg['direction'] ?? '') === 'from_customer',
                'time'         => $created->format('H:i'),
            ];
        }

        $support = config('ambroseo-dashboard.support', []);

        return [
            'items'         => $grouped,
            'isEmpty'       => count($raw) === 0,
            'supportEmail'  => $support['email'] ?? 'support@ambroseo.de',
            'responseHours' => (int) ($support['response_time_hours'] ?? 24),
        ];
    }

    private function formatDateLabel(Carbon $date): string
    {
        if ($date->isToday()) {
            return 'Heute';
        }
        if ($date->isYesterday()) {
            return 'Gestern';
        }
        if ($date->year === now()->year) {
            return $date->translatedFormat('j. F');
        }
        return $date->format('d.m.Y');
    }
}
