<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use Filament\Widgets\Widget;
use Modules\Billing\Models\Invoice;

class AmbroseoServiceWidget extends Widget
{
    protected string $view = 'ambroseo-customer-dashboard::widgets.ambroseo-service-widget';

    protected static ?int $sort = -3;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $support = config('ambroseo-dashboard.support', []);
        $clientId = auth()->user()?->client?->id;

        $openInvoices = 0;
        $openAmount = 0.0;

        if ($clientId) {
            $query = Invoice::where('client_id', $clientId)
                ->whereIn('status', ['sent', 'overdue']);
            $openInvoices = (clone $query)->count();
            $openAmount = (float) (clone $query)->sum('total');
        }

        return [
            'supportEmail' => $support['email'] ?? null,
            'supportPhone' => $support['phone'] ?? null,
            'helpUrl' => $support['help_url'] ?? '/hilfe',
            'responseHours' => (int) ($support['response_time_hours'] ?? 24),
            'openInvoices' => $openInvoices,
            'openAmount' => $openAmount,
        ];
    }
}
