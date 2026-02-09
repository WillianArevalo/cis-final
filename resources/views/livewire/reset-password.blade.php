@push('title', 'Restablecer contraseña')
<div class="flex w-full max-w-sm flex-col gap-6 px-4">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="me-5 flex w-max items-center space-x-2 rounded-xl bg-transparent p-2 rtl:space-x-reverse">
            <img src="{{ asset('logo.webp') }}" alt="CIS" class="h-14 w-auto object-cover" />
        </div>
        <h1 class="text-center text-2xl font-bold uppercase">Restablecer contraseña</h1>
        <p class="text-center text-sm text-zinc-600 dark:text-zinc-300">
            Crea una nueva contraseña para tu cuenta.
        </p>
    </div>

    @if (!$tokenValid)
        <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            El enlace no es válido o ya expiró. Solicita uno nuevo para continuar.
        </div>
        <flux:button variant="primary" as="link" href="{{ route('password.request') }}" wire:navigate>
            Solicitar nuevo enlace
        </flux:button>
    @else
        <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-4">
            <flux:input wire:model="password" label="Nueva contraseña" type="password" required
                placeholder="Ingresa tu nueva contraseña" autocomplete="new-password" icon="lock-closed" viewable />
            <flux:input wire:model="password_confirmation" label="Confirmar contraseña" type="password" required
                placeholder="Confirma tu nueva contraseña" autocomplete="new-password" icon="lock-closed" viewable />
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="reset-password-button">
                    Cambiar contraseña
                </flux:button>
            </div>
        </form>
    @endif

    <div class="text-center">
        <flux:link href="{{ route('login') }}" wire:navigate>
            Volver a iniciar sesión
        </flux:link>
    </div>
</div>
