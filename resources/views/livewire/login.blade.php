@push('title', 'Iniciar sesión')
<div class="flex w-full max-w-sm flex-col gap-6 px-4">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="me-5 flex w-max items-center space-x-2 rounded-xl bg-transparent p-2 rtl:space-x-reverse">
            <img src="{{ asset('logo.webp') }}" alt="CIS" class="h-14 w-auto object-cover" />
        </div>
        <h1 class="text-2xl font-bold uppercase">Iniciar sesión</h1>
    </div>
    <form method="POST" wire:submit="login" class="flex flex-col gap-4">
        <flux:input wire:model="identifier" label="Usuario o correo" type="text" required autofocus autocomplete="username"
            placeholder="usuario o email@example.com" clearable icon="at-symbol" />
        <div class="relative">
            <flux:input wire:model="password" label="Contraseña" type="password" required icon="lock-closed"
                autocomplete="current-password" placeholder="Contraseña" viewable />
            <flux:link class="absolute end-0 top-0 text-sm" href="#" wire:navigate>
                ¿Olvidaste tu contraseña?
            </flux:link>
        </div>
        <flux:checkbox wire:model="remember" label="Recuérdame" />
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                Iniciar sesión
            </flux:button>
        </div>
    </form>
</div>
