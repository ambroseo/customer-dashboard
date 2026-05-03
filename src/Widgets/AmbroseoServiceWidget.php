<?php

namespace Ambroseo\CustomerDashboard\Widgets;

use Ambroseo\CustomerDashboard\Services\CustomerDataService;
use Filament\Widgets\Widget;

class AmbroseoServiceWidget extends Widget
{
    protected string $view = 'ambroseo-customer-dashboard::widgets.ambroseo-service-widget';

    protected static ?int $sort = -3;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $support = config('ambroseo-dashboard.support', []);
        $invoices = app(CustomerDataService::class)->invoices();

        return [
            'supportEmail'  => $support['email'] ?? null,
            'supportPhone'  => $support['phone'] ?? null,
            'helpUrl'       => $support['help_url'] ?? '/hilfe',
            'responseHours' => (int) ($support['response_time_hours'] ?? 24),
            'openInvoices'  => (int) ($invoices['open_count'] ?? 0),
            'openAmount'    => (float) ($invoices['open_amount'] ?? 0.0),
        ];
    }
}
