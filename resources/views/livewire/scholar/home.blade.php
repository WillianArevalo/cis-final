<div>
    <div class="mb-6">
        <p class="text-base font-bold tracking-tight text-zinc-600 dark:text-zinc-300">
            Proyecto asignado
        </p>
        <h1 class="text-2xl font-bold text-zinc-600 dark:text-white">
            {{ $project ? $project->name : 'No tienes un proyecto asignado actualmente.' }}
        </h1>
    </div>
    @if ($project && $project->accept)
        @if ($reportOfTheMonth)
            <flux:callout variant="success" icon="check-circle">
                <flux:callout.heading>
                    Reporte de este mes enviado
                </flux:callout.heading>
                <flux:callout.text>
                    El reporte del mes de "{{ $month }}" ya ha sido enviado. Puedes revisarlo haciendo clic en el
                    botón de abajo.
                </flux:callout.text>
            </flux:callout>
        @else
            <flux:callout variant="warning" icon="exclamation-triangle">
                <flux:callout.heading>
                    Reporte de este mes pendiente
                </flux:callout.heading>
                <flux:callout.text>
                    Aún no has enviado el reporte del mes de "{{ $month }}". Por favor, asegúrate de subirlo lo
                    antes
                    posible.
                </flux:callout.text>
            </flux:callout>
        @endif
    @endif
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            @if ($reportOfTheMonth)
                <div class="rounded-xl border border-zinc-300 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                    <h2 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                        Reporte del mes
                    </h2>
                    <flux:heading size="sm" class="mt-2">
                        {{ $reportOfTheMonth->theme }}
                    </flux:heading>
                    <div
                        class="mt-3 space-y-3 text-sm text-zinc-600 dark:text-zinc-300 [&_a]:text-emerald-600 [&_a]:underline [&_blockquote]:border-s-2 [&_blockquote]:border-zinc-200 [&_blockquote]:ps-4 [&_blockquote]:text-zinc-500 [&_ol]:list-decimal [&_ol]:ps-5 [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:ps-5">
                        {!! \Mews\Purifier\Facades\Purifier::clean($reportOfTheMonth->description) !!}
                    </div>
                    <div class="mt-3 flex justify-end">
                        <flux:button as="link" icon="file"
                            href="{{ route('scholar.reports.show', ['reportId' => $reportOfTheMonth->id]) }}"
                            wire:navigate>
                            Ver reporte
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-zinc-300 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                    <h2 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                        Reporte del mes
                    </h2>
                    <flux:heading size="sm" class="mt-2">
                        No hay reportes disponibles.
                    </flux:heading>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                        Aún no se ha subido un reporte para este mes.
                    </p>
                    @if ($project && $project->accept)
                        <div class="mt-4 flex justify-start">
                            <flux:button variant="primary" icon="plus" as="link"
                                href="{{ route('scholar.reports.create', ['month' => $month]) }}">
                                Enviar reporte
                            </flux:button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <div>
            <h2 class="mb-2 text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                Integrantes
            </h2>
            <div class="flex flex-col gap-3">
                @if ($project)
                    @forelse ($project->scholarships as $scholar)
                        <div
                            class="flex w-full items-center gap-3 rounded-xl border border-zinc-300 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                            @if ($scholar->photo && Storage::disk('public')->exists($scholar->photo))
                                <img src="{{ Storage::url($scholar->photo) }}" alt="{{ $scholar->name }}"
                                    class="h-10 w-10 rounded-full object-cover">
                            @else
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 text-xs text-zinc-600 dark:bg-zinc-700 dark:text-zinc-200">
                                    {{ strtoupper(substr($scholar->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ $scholar->name }}
                                </p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $scholar->community ? $scholar->community->name : 'Sin comunidad' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                            No hay becados asignados.
                        </flux:text>
                    @endforelse
                @else
                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                        No tienes un proyecto asignado actualmente.
                    </flux:text>
                @endif
            </div>
        </div>
    </div>
</div>
