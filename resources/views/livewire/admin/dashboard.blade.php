@push('title', 'Panel de control')
<div class="flex flex-col gap-6">
    <div class="space-y-1">
        <flux:heading size="xl">
            Bienvenido de nuevo, {{ auth()->user()->name }}!
        </flux:heading>
        <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
            Resumen general del sistema y estado de reportes de {{ ucfirst($currentMonth) }}.
        </flux:text>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="briefcase" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Proyectos</flux:text>
                <flux:heading size="lg">{{ $stats['projects'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">Total registrados</flux:text>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="home" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Comunidades</flux:text>
                <flux:heading size="lg">{{ $stats['communities'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">Comunidades activas</flux:text>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="users" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Becados</flux:text>
                <flux:heading size="lg">{{ $stats['scholarships'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">Total asignables</flux:text>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="files" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Reportes del mes</flux:text>
                <flux:heading size="lg">{{ $stats['reportsThisMonth'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">En {{ ucfirst($currentMonth) }}</flux:text>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="check-circle" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Proyectos al dia</flux:text>
                <flux:heading size="lg">{{ $stats['projectsWithReport'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">Con reporte enviado</flux:text>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4">
            <span
                class="flex size-12 items-center justify-center rounded-full bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <flux:icon name="clock" class="size-6 text-zinc-400 dark:text-zinc-300" />
            </span>
            <div>
                <flux:text class="text-xs uppercase text-zinc-400 dark:text-zinc-300">Proyectos pendientes</flux:text>
                <flux:heading size="lg">{{ $stats['projectsWithoutReport'] ?? 0 }}</flux:heading>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400">Sin reporte del mes</flux:text>
            </div>
        </flux:card>
    </div>

    <div class="flex flex-col gap-3">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <flux:heading size="lg">Resumen de proyectos</flux:heading>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                    Estado del reporte mensual por proyecto.
                </flux:text>
            </div>
            <flux:button variant="ghost" icon="folder" as="link" href="{{ route('admin.projects.index') }}"
                wire:navigate>
                Ver proyectos
            </flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Proyecto</flux:table.column>
                <flux:table.column>Comunidad</flux:table.column>
                <flux:table.column>Becados</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
                <flux:table.column>Reporte del mes</flux:table.column>
                <flux:table.column>Fecha</flux:table.column>
                <flux:table.column sticky class="bg-white dark:bg-zinc-900">Acciones</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($projects as $project)
                    @php
                        $report = $project->reports->first();
                        $reportDate = $report?->date ?? $report?->created_at;
                    @endphp
                    <flux:table.row>
                        <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <p class="line-clamp-2 text-wrap">{{ $project->name }}</p>
                        </flux:table.cell>
                        <flux:table.cell>{{ $project->community?->name ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell>{{ $project->scholarships->count() }}</flux:table.cell>
                        <flux:table.cell>
                            @if ($project->accept)
                                <flux:badge color="green" icon="check-circle" size="sm">Aprobado</flux:badge>
                            @else
                                <flux:badge color="amber" icon="clock" size="sm">Pendiente</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($report)
                                <flux:badge color="green" icon="check-circle" size="sm">Enviado</flux:badge>
                            @else
                                <flux:badge color="red" icon="x-circle" size="sm">Sin reporte</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($reportDate)
                                {{ $reportDate->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                            @else
                                Sin fecha
                            @endif
                        </flux:table.cell>
                        <flux:table.cell sticky class="bg-white dark:bg-zinc-900">
                            <flux:dropdown>
                                <flux:button size="sm" icon="chevron-down">
                                    Acciones
                                </flux:button>
                                <flux:menu>
                                    @if ($report)
                                        <flux:menu.item icon="file" as="link"
                                            href="{{ route('admin.reports.show', ['reportId' => $report->id]) }}"
                                            wire:navigate>
                                            Ver reporte del mes
                                        </flux:menu.item>
                                    @endif
                                    <flux:menu.item icon="report" as="link"
                                        href="{{ route('admin.reports.index', ['project' => $project->id]) }}"
                                        wire:navigate>
                                        Ver reportes
                                    </flux:menu.item>
                                    <flux:menu.item icon="eye" as="link"
                                        href="{{ route('admin.projects.show', ['projectId' => $project->id]) }}"
                                        wire:navigate>
                                        Ver proyecto
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="8">
                            <div class="py-8 text-center text-sm">No hay proyectos registrados.</div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
