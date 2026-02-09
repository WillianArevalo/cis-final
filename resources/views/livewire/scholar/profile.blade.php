@push('title', 'Mi perfil')
<div class="mx-auto w-full max-w-3xl">
    <div class="space-y-2">
        <flux:heading size="xl">Mi perfil</flux:heading>
        <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
            Desde esta sección puedes actualizar tu usuario y cambiar tu contraseña.
        </flux:text>
    </div>

    <div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="space-y-4">
            <flux:heading size="lg">Información personal</flux:heading>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:input label="Nombre" wire:model="name" placeholder="Tu nombre completo" />
                <flux:input label="Correo electrónico" wire:model="email" icon="mail"
                    placeholder="example@email.com" />
            </div>

            <flux:separator text="Cambiar contraseña" />
            <flux:input label="Contraseña actual" wire:model="currentPassword" type="password" icon="lock"
                placeholder="Ingresa tu contraseña actual para cambiarla" />
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:input label="Nueva contraseña" wire:model="password" type="password" icon="lock"
                    placeholder="Ingresa una nueva contraseña" />
                <flux:input label="Confirmar contraseña" wire:model="confirmPassword" type="password" icon="lock-check"
                    placeholder="Confirma tu nueva contraseña" />
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <flux:button wire:click="updateProfile" variant="primary" class="sm:w-max w-full">
                Guardar cambios
            </flux:button>
        </div>
    </div>
</div>
