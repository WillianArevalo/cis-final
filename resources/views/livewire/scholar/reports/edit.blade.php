@push('title', 'Editar reporte mensual')
<div class="flex flex-col gap-6">
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <flux:heading size="xl">
                    Editar reporte de {{ $report?->month ?? 'mes' }}
                </flux:heading>
                <flux:badge color="emerald" size="sm" variant="solid" icon="pencil-square">
                    Edicion habilitada
                </flux:badge>
            </div>
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                Actualiza la informacion del reporte y guarda los cambios.
            </flux:text>
        </div>
        <div class="flex w-full flex-wrap gap-2 sm:w-auto">
            <flux:button variant="ghost" icon="arrow-left" as="link"
                href="{{ route('scholar.reports.show', $report->id) }}" class="w-full sm:w-auto">
                Volver al reporte
            </flux:button>
            <flux:button variant="ghost" icon="list-bullet" as="link" href="{{ route('scholar.reports.index') }}"
                class="w-full sm:w-auto">
                Mis reportes
            </flux:button>
        </div>
    </div>

    <form class="flex flex-col gap-6" wire:submit.prevent="save">
        <div class="flex flex-col items-center gap-4 md:flex-row">
            <div class="flex-2 w-full">
                <flux:input label="Proyecto" value="{{ $project?->name ?? 'No asignado' }}" readonly />
            </div>
            <div class="w-full flex-1">
                <flux:date-picker wire:model="date" label="Fecha del reporte" required placeholder="Seleccionar fecha"
                    selectable-header />
            </div>
        </div>

        <div class="w-max">
            @if ($project?->scholarships?->count() > 0)
                <flux:checkbox.group label="Asistencia" wire:model.live="assists">
                    <flux:checkbox.all label="Seleccionar todos" />
                    @foreach ($project->scholarships as $scholarship)
                        <flux:checkbox value="{{ $scholarship->id }}" label="{{ $scholarship->name }}" />
                    @endforeach
                </flux:checkbox.group>
            @endif
        </div>

        <div class="flex flex-col items-center gap-4 md:flex-row">
            <div class="flex-2 w-full">
                <flux:input label="Tema de la actividad" wire:model="theme"
                    placeholder="Ej. Taller de liderazgo juvenil" required />
            </div>
            <div class="w-full flex-1">
                <flux:input label="Numero de participantes" wire:model="numberParticipants" placeholder="Ej. 4"
                    type="number" min="1" required icon="users-group" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:editor label="Descripcion de la actividad" wire:model="description"
                toolbar="bold | italic | bullet | align ~ undo redo"
                placeholder="Describe las actividades realizadas durante el mes" required />
            <flux:editor label="Obstaculos" wire:model="obstacles" toolbar="bold | italic | bullet | align ~ undo redo"
                placeholder="Describe los obstaculos enfrentados durante el mes" required />
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg">Evidencias actuales</flux:heading>
            <flux:text class="mt-2 text-sm text-zinc-500">
                Marca las fotos que deseas eliminar antes de guardar.
            </flux:text>
            @if ($report?->images && $report->images->count())
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($report->images as $image)
                        @if (!in_array($image->id, $removedImageIds, true))
                            @if ($image->path && Storage::disk('public')->exists($image->path))
                                <flux:file-item :key="$image->id" image="{{ Storage::url($image->path) }}"
                                    heading="{{ basename($image->path) }}">
                                    <x-slot name="actions">
                                        <flux:file-item.remove wire:click="removeExistingImage({{ $image->id }})" />
                                    </x-slot>
                                </flux:file-item>
                            @endif
                        @endif
                    @endforeach
                </div>
            @else
                <flux:text class="mt-3 text-sm text-zinc-500">
                    No hay evidencias fotograficas registradas.
                </flux:text>
            @endif
        </div>

        <flux:file-upload wire:model="newImages" multiple label="Agregar nuevas fotografias" accept="image/*"
            max-size="10240">
            <flux:file-upload.dropzone heading="Arrastra los archivos aqui o haz clic para buscar"
                text="JPG, PNG, GIF hasta 10MB" with-progress />
        </flux:file-upload>

        @if (count($newImages) > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($newImages as $index => $image)
                    <flux:file-item :key="$index" image="{{ $image->temporaryUrl() }}"
                        heading="{{ $image->getClientOriginalName() }}" size="{{ $image->getSize() }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeNewImage({{ $index }})" />
                        </x-slot>
                    </flux:file-item>
                @endforeach
            </div>
        @endif

        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">
            <flux:button variant="ghost" type="button" as="link" icon="x-mark"
                href="{{ route('scholar.reports.show', $report->id) }}" class="w-full sm:w-max">
                Cancelar
            </flux:button>
            <flux:button variant="primary" type="submit" icon="check" class="w-full sm:w-max">
                Guardar cambios
            </flux:button>
        </div>
    </form>
</div>
