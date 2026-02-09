<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        @include('components.partials.head')
    </head>

    <body
        class="dark:bg-linear-to-b flex min-h-screen flex-col items-center justify-center bg-white dark:from-zinc-950 dark:to-zinc-900">
        <main class="flex w-full flex-1 items-center justify-center">
            {{ $slot }}
        </main>
        @fluxScripts
    </body>

</html>
