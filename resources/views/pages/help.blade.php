<x-filament-panels::page>
    <div class="space-y-8">
        {{-- Hero --}}
        <section class="rounded-xl bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/30 p-8 ring-1 ring-primary-200 dark:ring-primary-800">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        Brauchst du Hilfe?
                    </h2>
                    <p class="text-gray-700 dark:text-gray-300 max-w-2xl">
                        In der Wissensdatenbank findest du Anleitungen zu deiner Website,
                        zur Pflege deiner Inhalte, zu Sicherheit und zu typischen Fragen.
                        Direkter Support per E-Mail an
                        <a href="mailto:{{ $support['email'] ?? 'support@ambroseo.de' }}"
                           class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">
                            {{ $support['email'] ?? 'support@ambroseo.de' }}
                        </a>
                        @if(! empty($support['response_time_hours']))
                            (Antwortzeit ca. {{ $support['response_time_hours'] }} Stunden).
                        @endif
                    </p>
                </div>
                <a href="{{ $public_url }}"
                   target="_blank"
                   rel="noopener"
                   class="inline-flex items-center gap-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 text-sm font-medium transition shadow">
                    <span>docs.ambroseo.de oeffnen</span>
                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                </a>
            </div>
        </section>

        {{-- Doku-Buecher --}}
        @if(count($books) > 0)
            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-book-open class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                    <span>Wissensdatenbank</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($books as $book)
                        <a href="{{ $docs->publicUrl($book['slug']) }}"
                           target="_blank"
                           rel="noopener"
                           class="block rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-5 hover:border-primary-400 hover:shadow-md transition group">
                            <div class="flex items-start justify-between gap-3">
                                <div class="space-y-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $book['title'] }}
                                    </h4>
                                    @if(! empty($book['description']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {{ $book['description'] }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-500 pt-1">
                                        {{ $book['chapters_count'] ?? 0 }} Kapitel,
                                        {{ $book['pages_count'] ?? 0 }} Seiten
                                    </p>
                                </div>
                                <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-400 group-hover:text-primary-500 shrink-0" />
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @else
            <section class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-6 text-center">
                <x-heroicon-o-cloud class="w-8 h-8 text-gray-400 mx-auto mb-2" />
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Wissensdatenbank gerade nicht erreichbar. Bitte spaeter erneut probieren
                    oder schreib uns direkt an
                    <a href="mailto:{{ $support['email'] ?? 'support@ambroseo.de' }}"
                       class="font-medium text-primary-600 hover:text-primary-500">
                        {{ $support['email'] ?? 'support@ambroseo.de' }}
                    </a>.
                </p>
            </section>
        @endif

        {{-- Aktuelles vom Blog --}}
        @if(count($blog) > 0)
            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-newspaper class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                    <span>Neueste Artikel</span>
                </h3>
                <div class="space-y-2">
                    @foreach($blog as $post)
                        <a href="{{ rtrim(config('ambroseo-dashboard.help.public_url'), '/') }}/blog/{{ $post['slug'] ?? '' }}"
                           target="_blank"
                           rel="noopener"
                           class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 hover:border-primary-400 transition">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">
                                    {{ $post['title'] ?? 'Ohne Titel' }}
                                </p>
                                @if(! empty($post['published_at']))
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($post['published_at'])->translatedFormat('j. F Y') }}
                                    </p>
                                @endif
                            </div>
                            <x-heroicon-o-arrow-right class="w-4 h-4 text-gray-400 shrink-0" />
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-filament-panels::page>
