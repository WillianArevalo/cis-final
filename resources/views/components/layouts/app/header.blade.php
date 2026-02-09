<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-900">
        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        <flux:header class="mx-auto w-full max-w-5xl bg-white dark:bg-zinc-900">
            <flux:sidebar.toggle class="mr-2 lg:hidden" icon="bars-3-bottom-left" inset="left" />
            <flux:brand href="{{ route('scholar.home') }}" logo="{{ asset('logo.webp') }}" name="CIS" />
            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" href="{{ route('scholar.home') }}" wire:navigate
                    :current="request()->routeIs('scholar.home')">
                    Inicio
                </flux:navbar.item>
                <flux:navbar.item icon="files" href="{{ route('scholar.reports.index') }}"
                    :current="request()->routeIs('scholar.reports.*')" wire:navigate>
                    Reportes
                </flux:navbar.item>
                <flux:navbar.item icon="briefcase" href="{{ route('scholar.project') }}"
                    :current="request()->routeIs('scholar.project')" wire:navigate>
                    Proyecto
                </flux:navbar.item>
            </flux:navbar>
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile name="{{ auth()->user()->name }}"
                    :avatar="auth()->user()->scholarship?->photo && Storage::disk('public')->exists(auth()->user()->scholarship->photo) ? Storage::url(auth()->user()->scholarship->photo) : null" />
                <flux:menu>
                    <flux:menu.item icon="user" as="link" href="{{ route('scholar.profile') }}" wire:navigate>
                        Perfil
                    </flux:menu.item>
                    <flux:menu.item keep-open icon="moon">
                        <flux:switch x-data x-model="$flux.dark" label="Modo oscuro" />
                    </flux:menu.item>
                    <flux:menu.separator />
                    <livewire:logout />
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:sidebar sticky collapsible="mobile"
            class="border-r border-zinc-200 bg-white lg:hidden dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:sidebar.brand href="{{ route('scholar.home') }}" logo="{{ asset('logo.webp') }}" name="CIS" />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="{{ route('scholar.home') }}"
                    :current="request()->routeIs('scholar.home')">Inicio</flux:sidebar.item>
                <flux:sidebar.item icon="files" href="{{ route('scholar.reports.index') }}"
                    :current="request()->routeIs('scholar.reports.*')">
                    Reportes
                </flux:sidebar.item>
                <flux:sidebar.item icon="briefcase" href="{{ route('scholar.project') }}"
                    :current="request()->routeIs('scholar.project')">
                    Proyecto
                </flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="information-circle" href="#">
                    Ayuda
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
        @stack('scripts')
    </body>

</html>
