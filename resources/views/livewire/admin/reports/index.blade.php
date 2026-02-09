@push('title', 'Reportes del proyecto')
<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">
                Reportes del proyecto {{ $project->name }}
            </flux:heading>
            <flux:text class="text-sm">
                Administra los reportes de los becados asociados a este proyecto.
            </flux:text>
        </div>
        <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.projects.index') }}" wire:navigate>
            Regresar
        </flux:button>
    </div>
    <flux:table class="mt-6">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Mes</flux:table.column>
            <flux:table.column>Tema</flux:table.column>
            <flux:table.column>Enviado por</flux:table.column>
            <flux:table.column>Fecha</flux:table.column>
            <flux:table.column sticky class="bg-white dark:bg-zinc-900">Acciones</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @if ($project->reports->count() > 0)
                @foreach ($project->reports as $report)
                    <flux:table.row>
                        <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-col gap-1">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ ucfirst($report->month) }}
                                </p>
                                @if ($report->is_editable)
                                    <flux:badge size="sm" color="yellow" class="w-max" icon="edit">
                                        Editable
                                    </flux:badge>
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $report->theme }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                @if (
                                    $report->user &&
                                        $report->user->scholarship &&
                                        $report->user->scholarship->photo &&
                                        Storage::disk('public')->exists($report->user->scholarship->photo))
                                    <flux:avatar size="sm"
                                        :src="Storage::url($report->user->scholarship->photo)" />
                                @else
                                    <flux:avatar size="sm" :src="null" :name="$report->user->name" />
                                @endif
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $report->user->name }}
                                </p>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $report->created_at->format('d/m/Y h:i A') }}</flux:table.cell>
                        <flux:table.cell sticky class="bg-white dark:bg-zinc-900">
                            <flux:dropdown>
                                <flux:button icon="chevron-down" size="sm">Acciones</flux:button>
                                <flux:menu>
                                    <flux:menu.item as="link" icon="file"
                                        href="{{ route('admin.reports.show', $report) }}" wire:navigate>
                                        Ver reporte
                                    </flux:menu.item>
                                    @if (!$report->is_editable)
                                        <flux:menu.item icon="edit" wire:click="makeEditable({{ $report->id }})">
                                            Hacer editable
                                        </flux:menu.item>
                                    @else
                                        <flux:menu.item icon="x-mark"
                                            wire:click="makeNonEditable({{ $report->id }})">
                                            Quitar editable
                                        </flux:menu.item>
                                    @endif
                                    <flux:menu.separator />
                                    <flux:menu.item variant="danger" icon="trash"
                                        wire:click="confirmDelete({{ $report->id }})">
                                        Eliminar reporte
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            @else
                <flux:table.row>
                    <flux:table.cell colspan="6">
                        <flux:text class="text-center text-sm text-gray-500">
                            No hay reportes disponibles.
                        </flux:text>
                    </flux:table.cell>
                </flux:table.row>
            @endif
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model="showDelete" class="w-full max-w-sm">
        <div class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">Eliminar reporte</flux:heading>
                <flux:text class="text-sm">Esta acción es permanente. Confirma para continuar.</flux:text>
            </div>
            <flux:error name="delete" />
            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showDelete', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" type="button" wire:click="deleteReport">
                    Eliminar
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
