@push('title', 'Reportes mensuales')
<div class="flex flex-col gap-6">
    <div class="space-y-2">
        <flux:heading size="xl">Reportes mensuales</flux:heading>
        <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
            Revisa el estado de cada reporte mensual y envia los pendientes.
        </flux:text>
    </div>

    @if (!$project)
        <flux:callout variant="warning" icon="exclamation-triangle">
            <flux:callout.heading>
                Sin proyecto asignado
            </flux:callout.heading>
            <flux:callout.text>
                Aun no tienes un proyecto asignado, por lo que no puedes enviar reportes.
            </flux:callout.text>
        </flux:callout>
    @else
        @if (!$project->accept)
            <flux:callout variant="warning" icon="information-circle">
                <flux:callout.heading>Proyecto pendiente</flux:callout.heading>
                <flux:callout.text>
                    Tu proyecto social ha sido creado pero aún no ha sido aceptado por el comité. El comité revisará la
                    información proporcionada y decidirá si acepta o rechaza el proyecto. Mientras tanto, no podrás
                    enviar
                    reportes mensuales.
                </flux:callout.text>
            </flux:callout>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($cards as $card)
                    @php
                        $status = $card['status'];
                        $baseClass =
                            'flex h-full flex-col gap-4 rounded-2xl border-2 border-zinc-200 bg-white/80 p-5 shadow-sm transition dark:border-zinc-700 dark:bg-zinc-900/70';
                        $statusClass = match ($status) {
                            'future' => 'opacity-60 pointer-events-none',
                            default => '',
                        };
                    @endphp

                    <div class="{{ $baseClass }} {{ $statusClass }}">
                        <div class="flex items-start justify-between gap-4">
                            <flux:heading size="lg" class="font-bold tracking-wide">
                                {{ $card['month'] }}
                            </flux:heading>
                            @if ($status === 'sent')
                                <flux:badge color="green" size="sm" variant="solid" icon="check-circle">
                                    Reporte enviado
                                </flux:badge>
                            @elseif ($status === 'pending')
                                <flux:badge color="amber" size="sm" variant="solid" icon="clock">
                                    Pendiente
                                </flux:badge>
                            @elseif ($status === 'overdue')
                                <flux:badge color="red" size="sm" variant="solid" icon="exclamation-triangle">
                                    Atrasado
                                </flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm" variant="solid" icon="lock-closed">
                                    No disponible
                                </flux:badge>
                            @endif
                        </div>

                        <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-300">
                            @if ($status === 'sent')
                                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                                    <flux:icon name="check-circle" class="size-5" />
                                    <span>Reporte enviado</span>
                                </div>
                                <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-300">
                                    <flux:icon name="calendar-days" class="size-5" />
                                    <span>{{ $card['dateText'] ?? 'Fecha no registrada.' }}</span>
                                </div>
                            @elseif ($status === 'pending')
                                <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                                    <flux:icon name="clock" class="size-5" />
                                    <span>Reporte pendiente del mes actual.</span>
                                </div>
                                <p>Recuerda enviar el reporte de {{ $card['monthRaw'] }}.</p>
                            @elseif ($status === 'overdue')
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                    <flux:icon name="exclamation-triangle" class="size-5" />
                                    <span>Reporte atrasado de meses anteriores.</span>
                                </div>
                                <p>Este reporte debio enviarse antes. Envialo lo antes posible.</p>
                            @else
                                <div class="flex items-center gap-2 text-zinc-500">
                                    <flux:icon name="lock-closed" class="size-5" />
                                    <span>Todavia no puedes enviar el reporte de este mes.</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center justify-end gap-2">
                            @if ($status === 'sent')
                                @if ($card['report']?->is_editable)
                                    <flux:button variant="primary" icon="pencil-square" as="link"
                                        href="{{ route('scholar.reports.edit', ['reportId' => $card['report']->id]) }}"
                                        wire:navigate>
                                        Editar reporte
                                    </flux:button>
                                @endif
                                <flux:button as="link" icon="file"
                                    href="{{ $card['report'] ? route('scholar.reports.show', ['reportId' => $card['report']->id]) : '#' }}"
                                    wire:navigate>
                                    Ver reporte
                                </flux:button>
                            @elseif ($status === 'pending')
                                <flux:button variant="primary" icon="paper-airplane" as="link"
                                    href="{{ route('scholar.reports.create', ['month' => $card['monthRaw']]) }}"
                                    wire:navigate>
                                    Enviar reporte
                                </flux:button>
                            @elseif ($status === 'overdue')
                                <flux:button variant="danger" icon="paper-airplane" as="link"
                                    href="{{ route('scholar.reports.create', ['month' => $card['monthRaw']]) }}"
                                    wire:navigate>
                                    Enviar reporte
                                </flux:button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
