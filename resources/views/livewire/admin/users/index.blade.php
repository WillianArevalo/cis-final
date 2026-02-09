@push('title', 'Usuarios')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="space-y-1">
            <flux:heading size="xl">Usuarios</flux:heading>
            <flux:text class="text-sm">Administra las cuentas del sistema.</flux:text>
        </div>
        <flux:button variant="primary" icon="user-plus" wire:click="create">
            Nuevo usuario
        </flux:button>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" label="Buscar" placeholder="Nombre o correo"
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

    <flux:table :paginate="$users">
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Nombre</flux:table.column>
            <flux:table.column>Rol</flux:table.column>
            <flux:table.column>Correo</flux:table.column>
            <flux:table.column>Estado</flux:table.column>
            <flux:table.column>Acciones</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse ($users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>{{ $loop->iteration }}</flux:table.cell>
                    <flux:table.cell variant="strong">{{ $user->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="blue" variant="solid" size="sm">{{ $user->role }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $user->email ?? 'N/A' }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($user->email_verified_at)
                            <flux:badge color="green" variant="solid" icon="check-circle">Verificado</flux:badge>
                        @else
                            <flux:badge color="amber" size="sm" icon="clock" variant="solid">
                                Pendiente
                            </flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown>
                            <flux:button size="sm" icon="chevron-down">
                                Acciones
                            </flux:button>
                            <flux:menu>
                                <flux:menu.item size="sm" icon="edit" wire:click="edit({{ $user->id }})">
                                    Editar
                                </flux:menu.item>
                                <flux:menu.item size="sm" variant="danger" icon="trash"
                                    wire:click="confirmDelete({{ $user->id }})">
                                    Eliminar
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="5">
                        <div class="py-8 text-center text-sm">No hay usuarios registrados.</div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model="showEditor">
        <form wire:submit="save" class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">{{ $editingId ? 'Editar becado' : 'Nuevo becado' }}</flux:heading>
                <flux:text class="text-sm">
                    {{ $editingId ? 'Actualiza los datos del becado.' : 'Crea un nuevo becado para el sistema.' }}
                </flux:text>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input wire:model.defer="name" label="Nombre" placeholder="Nombre completo" />
                <flux:input wire:model.defer="email" label="Correo" type="email" placeholder="correo@empresa.com" />
                <flux:input wire:model.defer="password" label="Contrasena" type="password"
                    placeholder="Minimo 8 caracteres" viewable />
                <flux:input wire:model.defer="password_confirmation" label="Confirmar contrasena" type="password"
                    placeholder="Repite la contrasena" viewable />
                <div class="sm:col-span-2">
                    <flux:switch wire:model="emailVerified" label="Correo verificado" />
                </div>
            </div>

            @if ($editingId)
                <flux:text class="text-xs">
                    Deja la contrasena en blanco para mantener la actual.
                </flux:text>
            @endif
            <div class="flex items-center justify-end gap-2">
                <flux:button variant="ghost" type="button" wire:click="$set('showEditor', false)">
                    Cancelar
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ $editingId ? 'Guardar cambios' : 'Crear usuario' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showDeleteConfirm">
        <div class="flex flex-col gap-4">
            <div class="space-y-1">
                <flux:heading size="xl">Eliminar usuario</flux:heading>
                <flux:text class="text-sm">Esta accion es permanente. Confirma para continuar.</flux:text>
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
