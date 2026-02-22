@push('title', 'Registro')
<div class="w-full py-20">
    <div class="mx-auto w-full max-w-xl px-4">
        <div class="mb-6 flex flex-col items-center justify-center gap-4">
            <div class="me-5 flex w-max items-center space-x-2 rounded-xl bg-transparent p-2 rtl:space-x-reverse">
                <img src="{{ asset('logo.webp') }}" alt="CIS" class="h-14 w-auto object-cover" />
            </div>
            <h1 class="text-2xl font-bold uppercase">Registro</h1>
            <p class="text-center text-sm">
                Completa el formulario para crear tu cuenta. Solo si no posees una cuenta, de lo contrario, por favor
                inicia sesión.
            </p>
        </div>
        <form method="POST" wire:submit="register" class="flex w-full flex-col gap-4">
            <flux:input wire:model="name" label="Nombre completo" type="text" required autofocus autocomplete="name"
                placeholder="Tu nombre completo" clearable icon="user" />
            <flux:select label="Institución" wire:model="institution" required variant="listbox"
                placeholder="Selecciona tu institución">
                <flux:select.option value="Centro Escolar Catón San Juan Mesas">
                    Centro Escolar Catón San Juan Mesas
                </flux:select.option>
                <flux:select.option value="Instituto Católico San Pablo Apostol">
                    Instituto Católico San Pablo Apóstol
                </flux:select.option>
                <flux:select.option value="Instituto Nacional de San Pablo Tacachico">
                    Instituto Nacional de San Pablo Tacachico
                </flux:select.option>
            </flux:select>
            <div class="flex flex-col items-center gap-4 sm:flex-row">
                <div class="flex-2 w-full">
                    <flux:select label="Nivel educativo" wire:model.defer="studyLevel" variant="listbox"
                        placeholder="Seleccionar nivel educativo">
                        <flux:select.option value="" disabled>Seleccionar nivel educativo</flux:select.option>
                        <flux:select.option value="Bachillerato General">Bachillerato General</flux:select.option>
                        <flux:select.option value="Bachillerato Vocacional">Bachillerato Vocacional</flux:select.option>
                        <flux:select.option value="Técnico Universitario u otro">
                            Técnico universitario u otro
                        </flux:select.option>
                        <flux:select.option value="Universidad">Universidad</flux:select.option>
                    </flux:select>
                </div>
                <div class="w-full flex-1">
                    <flux:select wire:model.defer="academicLevel" label="Año o grado académico" placeholder="1er año"
                        variant="listbox">
                        <flux:select.option value="" disabled>
                            Seleccionar
                        </flux:select.option>
                        <flux:select.option value="6to grado">6to grado</flux:select.option>
                        <flux:select.option value="7mo grado">7mo grado</flux:select.option>
                        <flux:select.option value="8vo grado">8vo grado</flux:select.option>
                        <flux:select.option value="9no grado">9no grado</flux:select.option>
                        <flux:select.option value="1er año">1er año</flux:select.option>
                        <flux:select.option value="2do año">2do año</flux:select.option>
                        <flux:select.option value="3er año">3er año</flux:select.option>
                    </flux:select>
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
            <flux:input label="Correo electrónico" wire:model="email" type="email" required autocomplete="email"
                placeholder="Tu correo electrónico" icon="mail"
                description:trailing="Utiliza tu correo institucional o el correo personal que más uses (importante no olvidarlo)" />
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:input label="Contraseña" wire:model="password" type="password" required
                    autocomplete="new-password" placeholder="Crea una contraseña segura" viewable />
                <flux:input label="Confirmar contraseña" wire:model="passwordConfirmation" type="password" required
                    autocomplete="new-password" placeholder="Confirma tu contraseña" viewable />
            </div>
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="register-button">
                    Registrarse
                </flux:button>
            </div>
        </form>

        <p class="text-center text-sm mt-6">
            ¿Ya tienes una cuenta?
            <flux:link href="{{ route('login') }}" wire:navigate
                class="text-primary-600 hover:text-primary-500 font-medium">
                Inicia sesión
            </flux:link>
        </p>
    </div>
</div>
