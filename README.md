# ambroseo/customer-dashboard

Customer-Admin-Dashboard fuer AMBROSEO-Kundenwebseiten | Filament-Plugin.

Liefert pro Kunden-Container:
- **Welcome-Widget** (Begruessung + Uhrzeit)
- **Server-Status-Widget** (Coolify-Status, SSL, Backup, Antwortzeit)
- **Activity-Stats** (Besucher, Trend, Anfragen, Seitenaufrufe)
- **Direkt-Chat zu AMBROSEO** (Polling 30s, sendet via API)
- **AMBROSEO-Service-Widget** (Support, offene Rechnungen, Hilfe)
- **Hilfe-Page** (zieht Doku von docs.ambroseo.de)

Daten + Branding (Logo, Brand-Color, Name) werden zentral aus der AMBROSEO-API gepulled.

## Installation

```bash
composer require ambroseo/customer-dashboard:^1.0
```

In `app/Providers/Filament/CustomerPanelProvider.php`:

```php
use Ambroseo\CustomerDashboard\CustomerDashboardPlugin;

$panel->plugins([
    CustomerDashboardPlugin::make(),
]);
```

## ENV-Variablen

```dotenv
AMBROSEO_API_URL=https://ambroseo.de
AMBROSEO_API_TOKEN=  # wird beim Onboarding via AMBROSEO Hosting-Manager gesetzt
AMBROSEO_DOCS_API_URL=https://ambroseo.de/api/v1/docs
AMBROSEO_DOCS_PUBLIC_URL=https://docs.ambroseo.de
```

## Versionen

- **1.0.0** | Erste produktive Version mit Chat, Branding-API-Pull, dual-mode Service-Layer
