<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">AMBROSEO Service</x-slot>
        <x-slot name="description">Direkter Draht zum Support, Hilfe-Center und Übersicht offener Rechnungen.</x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">

            <div style="border-radius: 0.625rem; padding: 1rem; background: rgba(36, 1, 69, 0.04); border: 1px solid rgba(36, 1, 69, 0.08);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <x-heroicon-o-lifebuoy style="width: 1.25rem; height: 1.25rem; color: #240145;" />
                    <div style="font-size: 0.875rem; font-weight: 600; color: #2D1B5E;">Support</div>
                </div>

                @if ($supportEmail)
                    <a href="mailto:{{ $supportEmail }}" style="display: block; font-size: 0.875rem; font-weight: 500; color: #240145; text-decoration: none;">
                        {{ $supportEmail }}
                    </a>
                @endif

                @if ($supportPhone)
                    <a href="tel:{{ $supportPhone }}" style="display: block; margin-top: 0.25rem; font-size: 0.875rem; color: #5B4F7A; text-decoration: none;">
                        {{ $supportPhone }}
                    </a>
                @endif

                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6B5F8A;">
                    Antwortzeit: max. {{ $responseHours }} Stunden
                </div>
            </div>

            <div style="border-radius: 0.625rem; padding: 1rem; background: rgba(36, 1, 69, 0.04); border: 1px solid rgba(36, 1, 69, 0.08);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <x-heroicon-o-book-open style="width: 1.25rem; height: 1.25rem; color: #240145;" />
                    <div style="font-size: 0.875rem; font-weight: 600; color: #2D1B5E;">Hilfe-Center</div>
                </div>

                <a href="{{ $helpUrl }}" style="font-size: 0.875rem; font-weight: 500; color: #240145; text-decoration: none;">
                    Anleitungen ansehen
                </a>

                <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #6B5F8A;">
                    Schritt für Schritt durch dein Panel.
                </div>
            </div>

            @if ($openInvoices > 0)
                <a href="/portal/my-invoices" style="display: block; border-radius: 0.625rem; padding: 1rem; background: rgba(212, 160, 23, 0.10); border: 1px solid rgba(212, 160, 23, 0.25); text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <x-heroicon-o-document-currency-euro style="width: 1.25rem; height: 1.25rem; color: #A07800;" />
                        <div style="font-size: 0.875rem; font-weight: 600; color: #2D1B5E;">Offene Rechnungen</div>
                    </div>

                    <div style="font-size: 1.125rem; font-weight: 700; color: #A07800;">
                        {{ number_format($openAmount, 2, ',', '.') }} EUR
                    </div>

                    <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #6B5F8A;">
                        {{ $openInvoices === 1 ? '1 Rechnung' : $openInvoices . ' Rechnungen' }} ausstehend
                    </div>
                </a>
            @else
                <div style="border-radius: 0.625rem; padding: 1rem; background: rgba(52, 211, 153, 0.08); border: 1px solid rgba(52, 211, 153, 0.20);">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <x-heroicon-o-check-circle style="width: 1.25rem; height: 1.25rem; color: #15803D;" />
                        <div style="font-size: 0.875rem; font-weight: 600; color: #15803D;">Alles bezahlt</div>
                    </div>

                    <div style="font-size: 0.75rem; color: #6B5F8A;">
                        Keine offenen Rechnungen. Vielen Dank für die pünktliche Zahlung.
                    </div>
                </div>
            @endif

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
