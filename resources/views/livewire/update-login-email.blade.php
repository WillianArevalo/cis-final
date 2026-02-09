@push('title', 'Actualizar correo')
<div class="flex w-full max-w-sm flex-col gap-6 px-4">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="me-5 flex w-max items-center space-x-2 rounded-xl bg-transparent p-2 rtl:space-x-reverse">
            <img src="{{ asset('logo.webp') }}" alt="CIS" class="h-14 w-auto object-cover" />
        </div>
        <h1 class="text-2xl font-bold uppercase">Actualizar correo</h1>
        <p class="text-center text-sm text-zinc-600 dark:text-zinc-300">
            Hola {{ $userName }}, para continuar agrega tu correo. A partir de ahora iniciaras sesion con ese correo.
        </p>
    </div>
    <form method="POST" wire:submit="save" class="flex flex-col gap-4">
        <flux:input wire:model="email" label="Correo electronico" type="email" required autofocus
            autocomplete="email" placeholder="email@example.com" clearable icon="at-symbol" />
        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="update-email-button">
                Guardar correo
            </flux:button>
        </div>
    </form>
</div>
