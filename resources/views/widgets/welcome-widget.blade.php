<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;">

            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; font-weight: 700; color: #FFFFFF; background: linear-gradient(135deg, #240145 0%, #5828B8 100%); box-shadow: 0 4px 8px rgba(36, 1, 69, 0.2); flex-shrink: 0;">
                    {{ $initials }}
                </div>

                <div>
                    <div style="font-size: 1.25rem; font-weight: 600; color: #2D1B5E; line-height: 1.4;">
                        {{ $greeting }}, {{ $firstName }}
                    </div>
                    <div style="font-size: 0.875rem; color: #6B5F8A; margin-top: 0.125rem;">
                        {{ $today }}
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; align-items: flex-end; flex-shrink: 0;">
                <div style="font-size: 1.5rem; font-weight: 700; font-variant-numeric: tabular-nums; color: #240145; line-height: 1;">
                    {{ $time }}
                </div>
                <div style="font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #A07800; margin-top: 0.25rem; font-weight: 600;">
                    Aktuelle Uhrzeit
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
