@push('title', 'Configuración')
<div class="">
    <div class="space-y-2">
        <flux:heading size="xl">
            Configuración del sistema
        </flux:heading>
        <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
            Administra las opciones de configuración del sistema, incluyendo ajustes generales, gestión de usuarios y
            otras preferencias.
        </flux:text>
    </div>

    <div class="mt-6">
        <div class="space-y-4">
            <flux:heading size="lg">
                Estados de la plataforma
            </flux:heading>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <flux:card>
                    <flux:heading>Modo de mantenimiento</flux:heading>
                    <flux:switch x-data x-model="$flux.maintenance" label="Activar modo de mantenimiento" />
                    <flux:callout variant="warning" icon="exclamation-triangle" class="mt-4">
                        <flux:callout.text>
                            Al activar el modo de mantenimiento, la plataforma estará temporalmente fuera de
                            servicio para los usuarios, mostrando una página de mantenimiento personalizada. Esto es
                            útil para realizar actualizaciones o tareas de mantenimiento sin afectar la experiencia
                            del usuario.
                        </flux:callout.text>
                    </flux:callout>
                </flux:card>
            </div>
        </div>
    </div>
</div>
