@push('title', 'Proyectos sociales')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <flux:heading size="xl">Proyectos sociales</flux:heading>
            <flux:text class="text-sm">Administra los proyectos sociales registrados.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" as="link" href="{{ route('admin.projects.create') }}"
            wire:navigate>
            Nuevo proyecto social
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex-2">
            <flux:input wire:model.live.debounce.300ms="search" label="Buscar"
                placeholder="Nombre de proyecto social, comunidad, estado" icon="magnifying-glass" clearable />
        </div>
        <div class="flex-1">
            <flux:select wire:model.live="communityFilter" label="Filtrar por comunidad" variant="listbox">
                <flux:select.option value="">Todas las comunidades</flux:select.option>
                @foreach ($communities as $community)
                    <flux:select.option value="{{ $community->id }}">{{ $community->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <div class="w-full sm:w-44">
            <flux:select wire:model.live="perPage" label="Por pagina" variant="listbox">
                <flux:select.option value="10">10</flux:select.option>
                <flux:select.option value="25">25</flux:select.option>
                <flux:select.option value="50">50</flux:select.option>
            </flux:select>
        </div>
    </div>

    <flux:table :paginate="$projects">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Comunidad</flux:table.column>
            <flux:table.column>Estado</flux:table.column>
            <flux:table.column>Reporte del mes</flux:table.column>
            <flux:table.column>Encargados</flux:table.column>
            <flux:table.column sticky class="bg-white dark:bg-zinc-900">Acciones</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($projects as $project)
                @php
                    $reportOfTheMonth = $project->reports()->where('month', $month)->first();
                    $class = $reportOfTheMonth
                        ? 'bg-green-50 text-green-800 dark:bg-green-900/10 dark:text-green-300'
                        : 'bg-red-50 text-red-800 dark:bg-red-900/10 dark:text-red-300';
                @endphp
                <flux:table.row :key="$project->id" class="{{ $class }}">
                    <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                    <flux:table.cell variant="strong">
                        <p class="line-clamp-2 text-wrap">{{ $project->name }}</p>
                    </flux:table.cell>
                    <flux:table.cell>{{ $project->community ? $project->community->name : 'N/A' }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($project->accept)
                            <flux:badge color="green" icon="check-circle" size="sm">
                                Aceptado
                            </flux:badge>
                        @else
                            <flux:badge color="amber" icon="clock" size="sm">
                                Pendiente
                            </flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($reportOfTheMonth)
                            <flux:button size="xs" variant="primary" color="green" as="link"
                                href="{{ route('admin.reports.show', ['reportId' => $reportOfTheMonth->id]) }}"
                                wire:navigate icon="file">
                                Reporte subido
                            </flux:button>
                        @else
                            <flux:badge icon="x-circle" color="red" size="sm">
                                Sin reporte
                            </flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex -space-x-2 rtl:space-x-reverse">
                            @if ($project->scholarships->count() == 0)
                                <flux:badge icon="x-circle" color="red" size="sm">
                                    Sin asignar
                                </flux:badge>
                            @endif
                            @foreach ($project->scholarships as $scholar)
                                <flux:tooltip content="{{ $scholar->name }}">
                                    @if ($scholar->photo && Storage::disk('public')->exists($scholar->photo))
                                        <img src="{{ Storage::url($scholar->photo) }}" alt="{{ $scholar->name }}"
                                            class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full border border-zinc-300 bg-zinc-200 text-xs text-zinc-600 dark:border-zinc-700 dark:bg-zinc-600 dark:text-zinc-300">
                                            {{ strtoupper(substr($scholar->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </flux:tooltip>
                            @endforeach
                        </div>
                    </flux:table.cell>
                    <flux:table.cell sticky class="bg-white dark:bg-zinc-900">
                        <flux:dropdown>
                            <flux:button size="sm" icon="chevron-down">
                                Acciones
                            </flux:button>
                            <flux:menu>
                                @if ($reportOfTheMonth)
                                    <flux:menu.item icon="file" as="link"
                                        href="{{ route('admin.reports.show', ['reportId' => $reportOfTheMonth->id]) }}"
                                        wire:navigate>
                                        Ver reporte del mes
                                    </flux:menu.item>
                                @endif
                                <flux:menu.item icon="edit" as="link"
                                    href="{{ route('admin.projects.edit', ['projectId' => $project->id]) }}"
                                    wire:navigate>
                                    Editar
                                </flux:menu.item>
                                <flux:menu.item icon="eye" as="link"
                                    href="{{ route('admin.projects.show', ['projectId' => $project->id]) }}"
                                    wire:navigate>
                                    Detalles
                                </flux:menu.item>
                                <flux:menu.item icon="user-plus" wire:click="assignScholars({{ $project->id }})">
                                    Asignar becados
                                </flux:menu.item>
                                <flux:menu.item icon="report" as="link"
                                    href="{{ route('admin.reports.index', $project->id) }}" wire:navigate>
                                    Reportes
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item variant="danger" icon="trash"
                                    wire:click="confirmDelete({{ $project->id }})">
                                    Eliminar
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7">
                        <div class="py-8 text-center text-sm">No hay proyectos sociales registrados.</div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model="assigned" class="w-full max-w-md">
        <div class="mb-6">
            <flux:heading size="xl">Asignar becados</flux:heading>
            <flux:text class="text-sm">Selecciona los becados que deseas asignar a este proyecto social.</flux:text>
        </div>
        <flux:pillbox wire:model="selectedScholarships" multiple placeholder="Selecciona becados"
            label="Becados asignados" searchable search:placeholder="Buscar becados...">
            @foreach ($scholarships as $scholarship)
                <flux:pillbox.option value="{{ $scholarship->id }}">{{ $scholarship->name }}</flux:pillbox.option>
            @endforeach
            <x-slot name="empty">
                <flux:pillbox.option.empty when-loading="Loading tags...">
                    No se encontraron becados.
                </flux:pillbox.option.empty>
            </x-slot>
        </flux:pillbox>
        <div class="mt-6 flex items-center justify-end gap-4">
            <flux:button variant="ghost" wire:click="$set('assigned', false)" icon="x-mark">
                Cancelar
            </flux:button>
            <flux:button variant="primary" wire:click="saveAssignedScholars">
                Guardar asignaciones
            </flux:button>
        </div>
    </flux:modal>

    <flux:modal wire:model="showDelete" class="w-full max-w-sm">
        <div class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">Eliminar proyecto</flux:heading>
                <flux:text class="text-sm">Esta acción es permanente. Confirma para continuar.</flux:text>
            </div>
            <flux:error name="delete" />
            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showDelete', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" type="button" wire:click="deleteProject">
                    Eliminar
                </flux:button>
            </div>
        </div>
    </flux:modal>

</div>
