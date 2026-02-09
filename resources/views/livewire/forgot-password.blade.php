@push('title', 'Cambiar contraseña')
<div class="flex w-full max-w-sm flex-col gap-6 px-4">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="me-5 flex w-max items-center space-x-2 rounded-xl bg-transparent p-2 rtl:space-x-reverse">
            <img src="{{ asset('logo.webp') }}" alt="CIS" class="h-14 w-auto object-cover" />
        </div>
        <h1 class="text-2xl font-bold uppercase">Cambiar contraseña</h1>
        <p class="text-center text-sm text-zinc-600 dark:text-zinc-300">
            Ingresa tu correo y te enviaremos un enlace para cambiar tu contraseña.
        </p>
    </div>

    @if ($sent)
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            Si el correo está registrado, enviaremos el enlace en unos minutos.
        </div>
    @endif

    <form method="POST" wire:submit="sendResetLink" class="flex flex-col gap-4">
        <flux:input wire:model="email" label="Correo electronico" type="email" required autofocus
            autocomplete="email" placeholder="email@example.com" clearable icon="at-symbol" />
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="forgot-password-button">
                Enviar enlace
            </flux:button>
        </div>
    </form>

    <div class="text-center">
        <flux:link href="{{ route('login') }}" wire:navigate>
            Volver a iniciar sesión
        </flux:link>
    </div>
</div>
