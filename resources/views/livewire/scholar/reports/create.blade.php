@push('title', 'Enviar reporte mensual')
<div class="flex flex-col gap-6">
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <flux:heading size="xl">
                Enviar reporte de {{ $month }}
            </flux:heading>
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                Completa el formulario para enviar tu reporte mensual.
            </flux:text>
        </div>
        <flux:button variant="ghost" icon="arrow-left" as="link" href="{{ route('scholar.reports.index') }}"
            class="w-full sm:w-auto">
            Volver a mis reportes
        </flux:button>
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
            @if ($project->scholarships->count() > 0)
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
                <flux:input label="Número de participantes" wire:model="numberParticipants" placeholder="Ej. 4"
                    type="number" min="1" required icon="users-group" />
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:editor label="Descripción de la actividad" wire:model="description"
                toolbar="bold | italic | bullet | align ~ undo redo"
                placeholder="Describe las actividades realizadas durante el mes" required />
            <flux:editor label="Obstáculos" wire:model="obstacles" toolbar="bold | italic | bullet | align ~ undo redo"
                placeholder="Describe los obstáculos enfrentados durante el mes" required />
        </div>
        <flux:file-upload wire:model="images" multiple label="Fotografías de la actividad" accept="image/*"
            max-size="10240">
            <flux:file-upload.dropzone heading="Arrastra los archivos aquí o haz clic para buscar"
                text="JPG, PNG, GIF hasta 10MB" with-progress />
        </flux:file-upload>

        @if (count($images) > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($images as $index => $image)
                    <flux:file-item :key="$index" image="{{ $image->temporaryUrl() }}"
                        heading="{{ $image->getClientOriginalName() }}" size="{{ $image->getSize() }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeImage({{ $index }})" />
                        </x-slot>
                    </flux:file-item>
                @endforeach
            </div>
        @endif

        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">
            <flux:button variant="ghost" type="button" as="link" icon="x-mark"
                href="{{ route('scholar.reports.index') }}" class="w-full sm:w-max">
                Cancelar
            </flux:button>
            <flux:button variant="primary" type="submit" icon="paper-airplane" class="w-full sm:w-max">
                Enviar reporte
            </flux:button>
        </div>
    </form>
</div>
