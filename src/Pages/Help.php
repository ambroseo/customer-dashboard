<?php

namespace Ambroseo\CustomerDashboard\Pages;

use Ambroseo\CustomerDashboard\Services\HelpDocsService;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Help extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Hilfe';

    protected static ?int $navigationSort = 90;

    protected static ?string $title = 'Hilfe & Support';

    protected static ?string $slug = 'hilfe';

    protected string $view = 'ambroseo-customer-dashboard::pages.help';

    public function getViewData(): array
    {
        $docs = app(HelpDocsService::class);

        return [
            'books'      => $docs->getBooks(),
            'sidebar'    => $docs->getSidebar(),
            'blog'       => $docs->getBlogIndex(3),
            'public_url' => $docs->publicUrl(),
            'support'    => config('ambroseo-dashboard.support', []),
            'docs'       => $docs,
        ];
    }
}
