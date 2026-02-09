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
        <flux:sidebar sticky collapsible
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <flux:sidebar.brand href="{{ route('admin.dashboard') }}" logo="{{ asset('logo.webp') }}" name="CIS" />
                <flux:sidebar.collapse
                    class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>
            <flux:sidebar.search placeholder="Buscar..." as="button" icon="magnifying-glass"
                onclick="window.Livewire.dispatch('openQuickSearch')" kbd="⌘K" />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="layout-dashboard" :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')" wire:navigate>
                    Panel de control
                </flux:sidebar.item>
                <flux:sidebar.item icon="users" :href="route('admin.users.index')"
                    :current="request()->routeIs('admin.users.index')" wire:navigate>
                    Usuarios
                </flux:sidebar.item>
                <flux:sidebar.item icon="school" :href="route('admin.scholars.index')"
                    :current="request()->routeIs('admin.scholars.index')" wire:navigate>
                    Becados
                </flux:sidebar.item>
                <flux:sidebar.item icon="home" :href="route('admin.communities.index')"
                    :current="request()->routeIs('admin.communities.index')" wire:navigate>
                    Comunidades
                </flux:sidebar.item>
                <flux:sidebar.item icon="briefcase" :href="route('admin.projects.index')"
                    :current="request()->routeIs('admin.projects.index')" wire:navigate>
                    Proyectos
                </flux:sidebar.item>
                <flux:sidebar.item icon="cog" :href="route('admin.settings')"
                    :current="request()->routeIs('admin.settings')" wire:navigate>
                    Configuración
                </flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
        </flux:sidebar>
        <flux:header
            class="block! border-b border-zinc-200 bg-white lg:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:navbar class="w-full">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />
                <div class="hidden lg:block">
                    @include('components.partials.breadcrumbs')
                </div>
                <flux:spacer />
                <flux:dropdown position="bottom" align="end">
                    @if (auth()->user()->profile_photo_path)
                        <flux:sidebar.profile :name="auth()->user()->name"
                            :avatar="Storage::url(auth()->user()->profile_photo_path)" icon:trailing="chevron-down"
                            data-test="sidebar-menu-button" />
                    @else
                        <flux:sidebar.profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                            icon:trailing="chevron-down" data-test="sidebar-menu-button" />
                    @endif
                    <flux:menu class="w-55">
                        <flux:menu.radio.group>
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-xl">
                                    @if (auth()->user()->profile_photo_path)
                                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                                            alt="{{ auth()->user()->name }}" class="h-full w-full object-cover" />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-xl bg-zinc-200 text-black dark:bg-zinc-700 dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <flux:menu.item :href="route('admin.profile')" icon="user" wire:navigate>
                            Mi perfil
                        </flux:menu.item>
                        <flux:menu.item keep-open icon="moon">
                            <flux:switch x-data x-model="$flux.dark" label="Modo oscuro" />
                        </flux:menu.item>
                        <flux:menu.separator />
                        <livewire:logout />
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>

            <div class="block lg:hidden pb-4">
                @include('components.partials.breadcrumbs')
            </div>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @stack('scripts')
    </body>

</html>
