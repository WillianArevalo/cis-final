@push('title', 'Becados')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Becados</flux:heading>
            <flux:text class="text-sm">
                Administra los becados registrados.
            </flux:text>
        </div>
        <flux:button variant="primary" icon="user-plus" wire:click="create">
            Nuevo becado
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" label="Buscar"
                placeholder="Buscar por nombre, institución o comunidad" icon="magnifying-glass" clearable />
        </div>
        <div class="w-full sm:w-44">
            <flux:select wire:model.live="perPage" label="Por pagina" variant="listbox">
                <flux:select.option value="10">10</flux:select.option>
                <flux:select.option value="25">25</flux:select.option>
                <flux:select.option value="50">50</flux:select.option>
            </flux:select>
        </div>
    </div>

    <flux:table :paginate="$scholars">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Becado</flux:table.column>
            <flux:table.column>Nivel</flux:table.column>
            <flux:table.column>Comunidad</flux:table.column>
            <flux:table.column sticky class="bg-white dark:bg-zinc-900">Acciones</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($scholars as $scholar)
                <flux:table.row :key="$scholar->id">
                    <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                    <flux:table.cell variant="strong">
                        <div class="flex items-center gap-3">
                            @if ($scholar->photo && Storage::disk('public')->exists($scholar->photo))
                                <img src="{{ Storage::url($scholar->photo) }}" alt="{{ $scholar->name }}"
                                    class="h-8 w-8 rounded-full object-cover" />
                            @else
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-200 text-xs font-medium text-zinc-600">
                                    {{ strtoupper(substr($scholar->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="flex flex-col gap-1">
                                <p class="text-sm font-medium">
                                    {{ $scholar->name }}
                                </p>
                                <flux:badge color="blue" size="sm" class="w-max">
                                    {{ $scholar->institution ?? 'N/A' }}
                                </flux:badge>

                                @if ($scholar->project)
                                    <p class="text-xs text-zinc-400">
                                        Proyecto: {{ $scholar->project->name }}
                                    </p>
                                @else
                                    <p class="text-xs text-zinc-400">
                                        Sin proyecto asignado
                                    </p>
                                @endif
                            </div>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex flex-col">
                            <p class="text-sm">
                                {{ $scholar->career ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-zinc-400">
                                {{ $scholar->study_level ?? 'N/A' }} / {{ $scholar->academic_level ?? 'N/A' }}
                            </p>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($scholar->community)
                            <flux:badge size="sm">
                                {{ $scholar->community->name }}
                            </flux:badge>
                        @else
                            <p class="text-sm">Sin comunidad</p>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell sticky class="bg-white dark:bg-zinc-900">
                        <flux:dropdown>
                            <flux:button size="sm" icon="chevron-down">
                                Acciones
                            </flux:button>
                            <flux:menu>
                                <flux:menu.item size="sm" icon="edit" wire:click="edit({{ $scholar->id }})">
                                    Editar
                                </flux:menu.item>
                                <flux:menu.item size="sm" variant="danger" icon="trash"
                                    wire:click="confirmDelete({{ $scholar->id }})">
                                    Eliminar
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5">
                        <div class="py-8 text-center text-sm">No hay becados registrados.</div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>


    <flux:modal wire:model="showEditor" class="w-full max-w-xl" flyout>
        <form wire:submit="save" class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">{{ $editingId ? 'Editar becado' : 'Nuevo becado' }}</flux:heading>
                <flux:text class="text-sm">
                    {{ $editingId ? 'Actualiza los datos del becado.' : 'Crea un nuevo becado para el sistema.' }}
                </flux:text>
            </div>

            @if ($errors->any())
                <div
                    class="mb-4 rounded-xl border border-dashed border-red-200 bg-red-50 p-4 dark:border-red-700 dark:bg-red-900/20">
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

            <flux:input wire:model.defer="name" label="Nombre completo" required placeholder="Ingresar nombre completo"
                icon="user" />
            <flux:input wire:model.defer="institution" label="Institución" placeholder="Ingresar institución"
                icon="building" />
            <flux:input label="Carrera o área de estudio" wire:model.defer="career"
                placeholder="Ej. Informatica, contabilidad, etc." icon="book" />
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <flux:select label="Nivel educativo" wire:model.defer="studyLevel" variant="listbox"
                        placeholder="Seleccionar nivel educativo">
                        <flux:select.option value="" disabled>Seleccionar nivel educativo</flux:select.option>
                        <flux:select.option value="Bachillerato General">Bachillerato General</flux:select.option>
                        <flux:select.option value="Bachillerato Vocacional">Bachillerato Vocacional</flux:select.option>
                        <flux:select.option value="Técnico Universitario u otro">Técnico universitario u otro
                        </flux:select.option>
                        <flux:select.option value="Universidad">Universidad</flux:select.option>
                    </flux:select>
                </div>
                <div class="flex-1">
                    <flux:input wire:model.defer="academicLevel" label="Año o grado académico"
                        placeholder="3er año / 1ro grado" icon="school" />
                </div>
            </div>
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

            <flux:select wire:model.defer="projectId" label="Proyecto asignado" variant="listbox"
                placeholder="Seleccionar proyecto" searchable>
                <x-slot name="search">
                    <flux:select.search placeholder="Buscar proyecto..." />
                </x-slot>
                <flux:select.option value="">Sin proyecto asignado</flux:select.option>
                @foreach ($projects as $project)
                    <flux:select.option value="{{ $project->id }}">{{ $project->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:file-upload wire:model="photo" label="Foto del becado" accept="image/*" max-size="2048">
                <flux:file-upload.dropzone heading="Arrastra y suelta la foto aquí o haz clic para seleccionar"
                    with-progress text="JPG, PNG, WEBP, JPEG hasta 5MB" inline />
            </flux:file-upload>

            @if ($editingId && $currentPhoto && Storage::disk('public')->exists($currentPhoto) && !$photo)
                <flux:file-item heading="{{ basename($currentPhoto) }}" image="{{ Storage::url($currentPhoto) }}"
                    size="{{ Storage::disk('public')->size($currentPhoto) }}">
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="$set('currentPhoto', null)" />
                    </x-slot>
                </flux:file-item>
            @endif

            @if ($photo)
                <flux:file-item heading="{{ $photo->getClientOriginalName() }}" image="{{ $photo->temporaryUrl() }}"
                    size="{{ $photo->getSize() }}">
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="$set('photo', null)" />
                    </x-slot>
                </flux:file-item>
            @endif

            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showEditor', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showDeleteConfirm" class="w-full max-w-sm">
        <div class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">Eliminar becado</flux:heading>
                <flux:text class="text-sm">Esta acción es permanente. Confirma para continuar.</flux:text>
            </div>
            <flux:error name="delete" />
            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showDeleteConfirm', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="danger" type="button" wire:click="delete">
                    Eliminar
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
