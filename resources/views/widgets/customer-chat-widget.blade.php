<x-filament-widgets::widget>
    <x-filament::section wire:poll.30s="refreshChat">
        <x-slot name="heading">Direkt-Chat mit AMBROSEO</x-slot>
        <x-slot name="description">Fragen zur Webseite? Wir antworten meistens in unter {{ $responseHours }} Stunden.</x-slot>

        <style>
            .ccw-stream {
                border: 1px solid #F3F4F6;
                border-radius: 0.625rem;
                background: #FAFAFA;
                padding: 1rem;
                max-height: 320px;
                min-height: 200px;
                overflow-y: auto;
            }
            .ccw-empty {
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                padding: 2rem 1rem; text-align: center;
            }
            .ccw-empty-icon { width: 2.5rem; height: 2.5rem; color: #9CA3AF; margin-bottom: 0.75rem; }
            .ccw-empty-title { font-size: 0.9375rem; font-weight: 600; color: #4B5563; margin-bottom: 0.25rem; }
            .ccw-empty-text { font-size: 0.8125rem; color: #6B7280; max-width: 320px; }
            .ccw-date {
                text-align: center; margin: 0.875rem 0 0.5rem;
                font-size: 0.6875rem; color: #9CA3AF; font-weight: 600;
                text-transform: uppercase; letter-spacing: 0.05em;
            }
            .ccw-msg { margin-bottom: 0.75rem; max-width: 80%; }
            .ccw-msg.them { margin-right: auto; }
            .ccw-msg.you { margin-left: auto; text-align: right; }
            .ccw-bubble {
                display: inline-block; padding: 0.625rem 0.875rem;
                border-radius: 0.75rem; font-size: 0.875rem; line-height: 1.5;
                text-align: left;
            }
            .ccw-msg.them .ccw-bubble {
                background: white; border: 1px solid #E5E7EB; color: #111827;
                border-bottom-left-radius: 0.25rem;
            }
            .ccw-msg.you .ccw-bubble {
                background: #240145; color: white;
                border-bottom-right-radius: 0.25rem;
            }
            .ccw-time { font-size: 0.6875rem; color: #9CA3AF; margin-top: 0.25rem; }
            .ccw-form {
                display: flex; gap: 0.5rem; margin-top: 0.875rem;
            }
            .ccw-input {
                flex: 1; padding: 0.625rem 0.875rem;
                border: 1px solid #E5E7EB; border-radius: 0.5rem;
                font-size: 0.875rem; outline: none;
                background: white;
            }
            .ccw-input:focus { border-color: #240145; }
            .ccw-send {
                background: #240145; color: white; border: none;
                padding: 0.625rem 1.125rem; border-radius: 0.5rem;
                font-size: 0.875rem; font-weight: 600; cursor: pointer;
            }
            .ccw-send:hover { background: #2D1B5E; }
        </style>

        <div class="ccw-stream" id="ccw-stream-{{ $this->getId() }}">
            @if ($isEmpty)
                <div class="ccw-empty">
                    <svg class="ccw-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <div class="ccw-empty-title">Noch keine Nachrichten</div>
                    <div class="ccw-empty-text">
                        Schreib uns einfach was du brauchst | Aenderungen, Fragen, Wuensche. Wir lesen jede Nachricht.
                    </div>
                </div>
            @else
                @foreach ($items as $item)
                    @if ($item['type'] === 'date')
                        <div class="ccw-date">{{ $item['label'] }}</div>
                    @else
                        <div class="ccw-msg {{ $item['fromCustomer'] ? 'you' : 'them' }}">
                            <div class="ccw-bubble">{{ $item['body'] }}</div>
                            <div class="ccw-time">{{ $item['time'] }}</div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <form class="ccw-form" wire:submit.prevent="sendMessage">
            <input
                type="text"
                wire:model.defer="newMessage"
                class="ccw-input"
                placeholder="Antworten | oder Datei anhaengen..."
                maxlength="5000"
                autocomplete="off"
            >
            <button type="submit" class="ccw-send">Senden</button>
        </form>

        <script>
            (function() {
                const id = 'ccw-stream-{{ $this->getId() }}';
                const scrollDown = () => {
                    const el = document.getElementById(id);
                    if (el) el.scrollTop = el.scrollHeight;
                };
                document.addEventListener('livewire:init', () => {
                    scrollDown();
                    Livewire.on('chat-message-sent', () => setTimeout(scrollDown, 50));
                });
                document.addEventListener('livewire:navigated', scrollDown);
            })();
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
