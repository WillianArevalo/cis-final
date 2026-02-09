@push('title', 'Detalle del proyecto')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-3">
                <flux:heading size="xl">{{ $project->name }}</flux:heading>
                <flux:badge color="{{ $project->accept ? 'green' : 'amber' }}" size="sm">
                    {{ $project->accept ? 'Aprobado' : 'En revision' }}
                </flux:badge>
            </div>
            <flux:text class="text-sm">
                {{ $project->community ? $project->community->name : 'Comunidad no asignada' }}
                @if ($project->location)
                    <span class="text-zinc-400">•</span> {{ $project->location }}
                @endif
            </flux:text>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button variant="ghost" icon="arrow-left" as="link" href="{{ route('admin.projects.index') }}"
                wire:navigate>
                Volver
            </flux:button>
            <flux:button variant="primary" icon="pencil-square" as="link"
                href="{{ route('admin.projects.edit', ['projectId' => $project->id]) }}" wire:navigate>
                Editar
            </flux:button>
        </div>
    </div>

    <div
        class="bg-linear-to-br rounded-2xl border border-zinc-200 from-emerald-50 via-white to-sky-50 p-6 dark:border-zinc-700 dark:from-emerald-900/20 dark:via-zinc-900 dark:to-sky-900/10">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <flux:heading size="lg">Resumen general</flux:heading>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                    {{ $project->general_objective ?: 'No se ha registrado el objetivo general.' }}
                </flux:text>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div
                        class="rounded-xl border border-zinc-200 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                        <flux:text class="text-xs uppercase text-zinc-400">Poblacion beneficiada</flux:text>
                        <flux:heading size="sm" class="mt-2">
                            {{ $project->benefited_population ?: 'No registrado' }}
                        </flux:heading>
                    </div>
                    <div
                        class="rounded-xl border border-zinc-200 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                        <flux:text class="text-xs uppercase text-zinc-400">Responsable</flux:text>
                        <flux:heading size="sm" class="mt-2">
                            {{ $project->sentBy ? $project->sentBy->name : 'No asignado' }}
                        </flux:heading>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <flux:heading size="lg">Archivos</flux:heading>
                <div class="flex flex-col gap-3">
                    @if ($project->document && Storage::disk('public')->exists($project->document))
                        <flux:button as="link" icon="document-text" href="{{ Storage::url($project->document) }}"
                            target="_blank">
                            Ver documento
                        </flux:button>
                    @else
                        <flux:text class="text-sm text-zinc-500">Documento no disponible.</flux:text>
                    @endif

                    @if ($project->map && Storage::disk('public')->exists($project->map))
                        <flux:button as="link" icon="map" href="{{ Storage::url($project->map) }}"
                            target="_blank">
                            Ver mapa
                        </flux:button>
                    @else
                        <flux:text class="text-sm text-zinc-500">Mapa no disponible.</flux:text>
                    @endif

                    @if ($project->schedule && Storage::disk('public')->exists($project->schedule))
                        <flux:button as="link" icon="calendar-days" href="{{ Storage::url($project->schedule) }}"
                            target="_blank">
                            Ver cronograma
                        </flux:button>
                    @else
                        <flux:text class="text-sm text-zinc-500">Cronograma no disponible.</flux:text>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Contexto y plan</flux:heading>
                <div class="mt-4 space-y-4 text-sm text-zinc-600 dark:text-zinc-300">
                    <div>
                        <flux:text class="text-xs uppercase text-zinc-400">Justificacion</flux:text>
                        <p class="mt-2">{{ $project->justification ?: 'No registrada.' }}</p>
                    </div>
                    <div>
                        <flux:text class="text-xs uppercase text-zinc-400">Contextualizacion</flux:text>
                        <p class="mt-2">{{ $project->contextualization ?: 'No registrada.' }}</p>
                    </div>
                    <div>
                        <flux:text class="text-xs uppercase text-zinc-400">Descripcion de actividades</flux:text>
                        <p class="mt-2">{{ $project->description_activities ?: 'No registrada.' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Proyecciones y retos</flux:heading>
                <div class="mt-4 space-y-4 text-sm text-zinc-600 dark:text-zinc-300">
                    <div>
                        <flux:text class="text-xs uppercase text-zinc-400">Proyecciones</flux:text>
                        <p class="mt-2">{{ $project->projections ?: 'No registradas.' }}</p>
                    </div>
                    <div>
                        <flux:text class="text-xs uppercase text-zinc-400">Retos</flux:text>
                        <p class="mt-2">{{ $project->challenges ?: 'No registrados.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Becados asignados</flux:heading>
                <div class="mt-4 flex flex-wrap gap-3">
                    @forelse ($project->scholarships as $scholar)
                        <div
                            class="flex w-full items-center gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
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
                                <p class="text-xs text-zinc-500">{{ $scholar->institution ?: 'Sin institucion' }}</p>
                            </div>
                        </div>
                    @empty
                        <flux:text class="text-sm text-zinc-500">No hay becados asignados.</flux:text>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Objetivos especificos</flux:heading>
                <div class="mt-4 space-y-2 text-sm text-zinc-600 dark:text-zinc-300">
                    @forelse ($project->specificObjetives as $objective)
                        <div class="flex items-start gap-2">
                            <flux:icon name="check-circle" class="size-5" />
                            <p>{{ $objective->specific_objective ?? 'Objetivo sin descripcion.' }}</p>
                        </div>
                    @empty
                        <flux:text class="text-sm text-zinc-500">No hay objetivos registrados.</flux:text>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
