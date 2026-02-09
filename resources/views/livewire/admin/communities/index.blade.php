@push('title', 'Comunidades')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <flux:heading size="xl">Comunidades</flux:heading>
            <flux:text class="text-sm">Administra las comunidades registradas.</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="create">
            Nueva comunidad
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" label="Buscar" placeholder="Nombre de comunidad"
                icon="magnifying-glass" clearable />
        </div>
        <div class="w-full sm:w-44">
            <flux:select wire:model.live="perPage" label="Por pagina" variant="listbox">
                <flux:select.option value="10">10</flux:select.option>
                <flux:select.option value="25">25</flux:select.option>
                <flux:select.option value="50">50</flux:select.option>
            </flux:select>
        </div>
    </div>

    <flux:table :paginate="$communities">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Acciones</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($communities as $community)
                <flux:table.row :key="$community->id">
                    <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                    <flux:table.cell variant="strong">{{ $community->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button size="sm" icon="chevron-down">
                                Acciones
                            </flux:button>
                            <flux:menu>
                                <flux:menu.item size="sm" icon="edit" wire:click="edit({{ $community->id }})">
                                    Editar
                                </flux:menu.item>
                                <flux:menu.item size="sm" variant="danger" icon="trash"
                                    wire:click="confirmDelete({{ $community->id }})">
                                    Eliminar
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="3">
                        <div class="py-8 text-center text-sm">No hay comunidades registradas.</div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model="showEditor" class="w-full max-w-sm">
        <form wire:submit="save" class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">{{ $editingId ? 'Editar comunidad' : 'Nueva comunidad' }}</flux:heading>
                <flux:text class="text-sm">
                    {{ $editingId ? 'Actualiza el nombre de la comunidad.' : 'Registra una nueva comunidad.' }}
                </flux:text>
            </div>

            <flux:input wire:model.defer="name" label="Nombre" placeholder="Nombre de la comunidad" />

            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showEditor', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ $editingId ? 'Guardar cambios' : 'Crear comunidad' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showDeleteConfirm">
        <div class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">Eliminar comunidad</flux:heading>
                <flux:text class="text-sm">Esta accion es permanente. Confirma para continuar.</flux:text>
            </div>
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
