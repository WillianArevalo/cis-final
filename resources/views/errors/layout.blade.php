<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        @include('components.partials.head')
    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-900">
        @php
            $exceptionMessage = trim($exception?->getMessage() ?? '');
        @endphp
        <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
            <div
                class="pointer-events-none absolute -left-40 top-0 h-80 w-80 rounded-full bg-emerald-200/40 blur-3xl dark:bg-emerald-900/30">
            </div>
            <div
                class="pointer-events-none absolute -right-40 bottom-0 h-96 w-96 rounded-full bg-sky-200/40 blur-3xl dark:bg-sky-900/30">
            </div>

            <div class="relative w-full max-w-2xl">
                <div
                    class="rounded-2xl border border-zinc-200 bg-white/90 p-6 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/80">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span
                                class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                                Error @yield('code')
                            </span>
                            <flux:badge color="amber" icon="exclamation-triangle" size="sm">
                                {{ strtoupper(app()->environment()) }}
                            </flux:badge>
                        </div>
                        <div class="space-y-2">
                            <flux:heading size="xl">@yield('title')</flux:heading>
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                                @yield('message')
                            </flux:text>
                            @if ($exceptionMessage !== '')
                                <flux:callout variant="warning" icon="information-circle" class="mt-4">
                                    <flux:callout.text>
                                        {{ $exceptionMessage }}
                                    </flux:callout.text>
                                </flux:callout>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            @hasSection('actions')
                                @yield('actions')
                            @else
                                <flux:button variant="primary" as="link" href="{{ route('home') }}" icon="home">
                                    Ir al inicio
                                </flux:button>
                                <flux:button variant="ghost" type="button" onclick="history.back()" icon="arrow-left">
                                    Volver
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="mt-6 text-center text-xs text-zinc-500">
                    Si el problema persiste, contacta al administrador del sistema.
                </p>
            </div>
        </main>
        @fluxScripts
    </body>

</html>
