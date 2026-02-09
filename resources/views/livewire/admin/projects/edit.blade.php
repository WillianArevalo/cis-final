@push('title', 'Editar proyecto')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <flux:heading size="xl">Editar proyecto</flux:heading>
            <flux:text class="text-sm">Actualiza la informacion del proyecto social.</flux:text>
        </div>
        <flux:button variant="ghost" icon="arrow-left" as="link" href="{{ route('admin.projects.index') }}"
            wire:navigate>
            Volver
        </flux:button>
    </div>

    <form wire:submit="save" class="flex flex-col gap-6">
        @if ($errors->any())
            <div
                class="rounded-xl border border-dashed border-red-200 bg-red-50 p-4 dark:border-red-700 dark:bg-red-900/20">
                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                    <flux:icon name="x-circle" />
                    <p class="text-sm font-medium">Por favor, corrige los siguientes errores:</p>
                </div>
                <ul class="ml-6 mt-2 list-disc text-sm text-red-600 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            <flux:input wire:model.defer="name" label="Nombre del proyecto" required
                placeholder="Nombre del proyecto social" icon="briefcase" />
            <flux:select wire:model.defer="communityId" label="Comunidad" variant="listbox"
                placeholder="Seleccionar comunidad" searchable>
                <x-slot name="search">
                    <flux:select.search placeholder="Buscar comunidad..." />
                </x-slot>
                <flux:select.option value="" disabled>Seleccionar comunidad</flux:select.option>
                @foreach ($communities as $community)
                    <flux:select.option value="{{ $community->id }}">{{ $community->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <flux:input wire:model.defer="location" label="Ubicacion" placeholder="Municipio, canton o caserio"
                icon="map-pin" />
            <flux:input wire:model.defer="benefitedPopulation" label="Poblacion beneficiada"
                placeholder="Ej. 120 familias" icon="users" />
        </div>

        <div class="flex items-center gap-2">
            <flux:switch wire:model.defer="accept" label="Proyecto aprobado" />
        </div>

        <flux:textarea wire:model.defer="generalObjective" label="Objetivo general" rows="4"
            placeholder="Describe el objetivo general del proyecto" />
        <flux:textarea wire:model.defer="justification" label="Justificacion" rows="4"
            placeholder="Motiva el por que del proyecto" />
        <flux:textarea wire:model.defer="contextualization" label="Contextualizacion" rows="4"
            placeholder="Contexto de la comunidad y necesidades" />
        <flux:textarea wire:model.defer="descriptionActivities" label="Descripcion de actividades" rows="4"
            placeholder="Describe las actividades principales" />
        <flux:textarea wire:model.defer="projections" label="Proyecciones" rows="4"
            placeholder="Resultados esperados del proyecto" />
        <flux:textarea wire:model.defer="challenges" label="Retos" rows="4"
            placeholder="Riesgos o retos previstos" />

        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-1">
                    <flux:heading size="lg">Objetivos especificos</flux:heading>
                    <flux:text class="text-sm">Agrega o ajusta los objetivos especificos del proyecto.</flux:text>
                </div>
                <flux:button variant="ghost" type="button" icon="plus" wire:click="addSpecificObjective">
                    Agregar objetivo
                </flux:button>
            </div>

            <div class="mt-4 flex flex-col gap-3">
                @foreach ($specificObjectives as $index => $objective)
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center"
                        wire:key="objective-{{ $index }}">
                        <flux:input wire:model.defer="specificObjectives.{{ $index }}" class="flex-1"
                            placeholder="Objetivo especifico" />
                        <flux:button variant="ghost" type="button" icon="trash"
                            wire:click="removeSpecificObjective({{ $index }})">
                            Quitar
                        </flux:button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="flex flex-col gap-4">
                <flux:file-upload wire:model="schedule" label="Cronograma" accept="application/pdf" max-size="5120">
                    <flux:file-upload.dropzone heading="Sube el cronograma en PDF" with-progress text="PDF hasta 5MB"
                        inline />
                </flux:file-upload>

                @if ($currentSchedule && Storage::disk('public')->exists($currentSchedule) && !$schedule)
                    <flux:file-item heading="{{ basename($currentSchedule) }}"
                        size="{{ Storage::disk('public')->size($currentSchedule) }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeSchedule" />
                        </x-slot>
                    </flux:file-item>
                @endif

                @if ($schedule)
                    <flux:file-item heading="{{ $schedule->getClientOriginalName() }}"
                        size="{{ $schedule->getSize() }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="$set('schedule', null)" />
                        </x-slot>
                    </flux:file-item>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <flux:file-upload wire:model="document" label="Documento del proyecto" accept="application/pdf"
                    max-size="5120">
                    <flux:file-upload.dropzone heading="Sube el documento en PDF" with-progress text="PDF hasta 5MB"
                        inline />
                </flux:file-upload>

                @if ($currentDocument && Storage::disk('public')->exists($currentDocument) && !$document)
                    <flux:file-item heading="{{ basename($currentDocument) }}"
                        size="{{ Storage::disk('public')->size($currentDocument) }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeDocument" />
                        </x-slot>
                    </flux:file-item>
                @endif

                @if ($document)
                    <flux:file-item heading="{{ $document->getClientOriginalName() }}"
                        size="{{ $document->getSize() }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="$set('document', null)" />
                        </x-slot>
                    </flux:file-item>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <flux:file-upload wire:model="map" label="Mapa del proyecto" accept="image/*" max-size="5120">
                    <flux:file-upload.dropzone heading="Sube una imagen del mapa" with-progress
                        text="PNG, JPG, WEBP hasta 5MB" inline />
                </flux:file-upload>

                @if ($currentMap && Storage::disk('public')->exists($currentMap) && !$map)
                    <flux:file-item heading="{{ basename($currentMap) }}" image="{{ Storage::url($currentMap) }}"
                        size="{{ Storage::disk('public')->size($currentMap) }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removeMap" />
                        </x-slot>
                    </flux:file-item>
                @endif

                @if ($map)
                    <flux:file-item heading="{{ $map->getClientOriginalName() }}" image="{{ $map->temporaryUrl() }}"
                        size="{{ $map->getSize() }}">
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="$set('map', null)" />
                        </x-slot>
                    </flux:file-item>
                @endif
            </div>
        </div>

        <div class="flex flex-col items-center justify-center gap-2 sm:flex-row">
            <flux:button variant="ghost" type="button" as="link" icon="x-mark"
                href="{{ route('admin.projects.index') }}" wire:navigate class="w-full sm:w-max">
                Cancelar
            </flux:button>
            <flux:button variant="primary" type="submit" class="w-full sm:w-max">
                Guardar cambios
            </flux:button>
        </div>
    </form>
</div>
