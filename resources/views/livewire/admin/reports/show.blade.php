@push('title', 'Detalle del reporte')
<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-3">
                <flux:heading size="xl">Reporte de {{ $report?->month ?? 'mes' }}</flux:heading>
                <flux:badge color="green" size="sm" variant="solid" icon="check-circle">
                    Enviado
                </flux:badge>
            </div>
            <flux:text class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ $report?->project?->name ?? 'Proyecto no disponible' }}
                @if ($report?->project?->community)
                    <span class="text-zinc-400">•</span> {{ $report->project->community->name }}
                @endif
            </flux:text>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <flux:button variant="ghost" icon="arrow-left" as="link"
                href="{{ route('admin.reports.index', $report?->project_id) }}" wire:navigate class="w-full sm:w-max">
                Volver
            </flux:button>
            @if ($report?->file && Storage::disk('public')->exists($report->file))
                <flux:button as="link" icon="document-text" href="{{ Storage::url($report->file) }}"
                    target="_blank">
                    Descargar reporte
                </flux:button>
            @endif
        </div>
    </div>
    <div
        class="bg-linear-to-br rounded-2xl border border-zinc-200 from-emerald-50 via-white to-sky-50 p-4 sm:p-6 dark:border-zinc-700 dark:from-emerald-900/20 dark:via-zinc-900 dark:to-sky-900/10">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-3">
                <flux:text class="text-xs uppercase text-zinc-400">Tema</flux:text>
                <flux:heading size="lg">{{ $report?->theme ?? 'Sin tema registrado' }}</flux:heading>
                <div class="flex items-start gap-2 text-sm text-zinc-600 sm:items-center dark:text-zinc-300">
                    <flux:icon name="calendar-days" class="size-4" />
                    <span>
                        Fecha:
                        {{ $report?->date?->locale('es')->translatedFormat('d \d\e F \d\e Y h:i a') ?? 'Fecha no registrada' }}
                    </span>
                </div>
                <div class="flex items-start gap-2 text-sm text-zinc-600 sm:items-center dark:text-zinc-300">
                    <flux:icon name="user" class="size-4" />
                    <span>Enviado por: {{ $report?->user?->name ?? 'Autor no disponible' }}</span>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div
                    class="h-max rounded-xl border border-zinc-200 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                    <flux:text class="text-xs uppercase text-zinc-400">Participantes</flux:text>
                    <flux:heading size="lg" class="mt-2">
                        {{ $report?->number_participants ?? '0' }}
                    </flux:heading>
                </div>
                <div
                    class="h-max rounded-xl border border-zinc-200 bg-white/80 p-4 dark:border-zinc-700 dark:bg-zinc-900/60">
                    <flux:text class="text-xs uppercase text-zinc-400">Mes reportado</flux:text>
                    <flux:heading size="lg" class="mt-2">
                        {{ ucfirst($report?->month) ?? 'No registrado' }}
                    </flux:heading>
                </div>
            </div>
        </div>
    </div>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Descripcion</flux:heading>
                @if ($report?->description)
                    <div
                        class="mt-3 space-y-3 text-sm text-zinc-600 dark:text-zinc-300 [&_a]:text-emerald-600 [&_a]:underline [&_blockquote]:border-s-2 [&_blockquote]:border-zinc-200 [&_blockquote]:ps-4 [&_blockquote]:text-zinc-500 [&_ol]:list-decimal [&_ol]:ps-5 [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:ps-5">
                        {!! \Mews\Purifier\Facades\Purifier::clean($report->description) !!}
                    </div>
                @else
                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300">
                        No hay descripcion registrada.
                    </p>
                @endif
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Obstaculos</flux:heading>
                @if ($report?->obstacles)
                    <div
                        class="mt-3 space-y-3 text-sm text-zinc-600 dark:text-zinc-300 [&_a]:text-emerald-600 [&_a]:underline [&_blockquote]:border-s-2 [&_blockquote]:border-zinc-200 [&_blockquote]:ps-4 [&_blockquote]:text-zinc-500 [&_ol]:list-decimal [&_ol]:ps-5 [&_p]:leading-relaxed [&_ul]:list-disc [&_ul]:ps-5">
                        {!! \Mews\Purifier\Facades\Purifier::clean($report->obstacles) !!}
                    </div>
                @else
                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300">
                        No se registraron obstaculos.
                    </p>
                @endif
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Asistencias</flux:heading>
                <div class="mt-4 flex flex-col gap-3">
                    @forelse ($report?->project?->scholarships ?? [] as $scholarship)
                        <div class="flex items-center gap-2">
                            @php
                                $assistIds = $report->assists->pluck('scholarship_id')->toArray();
                                $hasAssistance = in_array($scholarship->id, $assistIds);
                            @endphp

                            @if ($hasAssistance)
                                <flux:icon name="check-circle" class="size-5 text-green-600 dark:text-green-400" />
                            @else
                                <flux:icon name="x-circle" class="size-5 text-red-600 dark:text-red-400" />
                            @endif

                            <p class="line-clamp-1 text-sm font-medium text-zinc-800 dark:text-zinc-100">
                                {{ $scholarship->name ?? 'Sin nombre' }}
                            </p>
                        </div>
                    @empty
                        <flux:text class="text-sm text-zinc-500">
                            No hay asistencias registradas.
                        </flux:text>
                    @endforelse
                </div>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="lg">Resumen rapido</flux:heading>
                <div class="mt-4 space-y-3 text-sm text-zinc-600 dark:text-zinc-300">
                    <div class="flex items-center gap-2">
                        <flux:icon name="briefcase" class="size-5" />
                        <span>{{ $report?->project?->name ?? 'Proyecto no disponible' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon name="map-pin" class="size-5" />
                        <span>{{ $report?->project?->location ?? 'Ubicacion no registrada' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon name="users" class="size-5" />
                        <span>{{ $report?->number_participants ?? '0' }} participantes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-zinc-200 bg-white p-4 sm:p-6 dark:border-zinc-700 dark:bg-zinc-900"
        x-data="{ open: false, src: '', scale: 1 }" x-cloak>
        <flux:heading size="lg">Evidencias fotograficas</flux:heading>
        @if ($report?->images && $report->images->count())
            <div class="mt-4 flex flex-wrap gap-4">
                @foreach ($report->images as $image)
                    @if ($image->path && Storage::disk('public')->exists($image->path))
                        <button type="button"
                            class="group relative overflow-hidden rounded-xl border border-zinc-200 bg-zinc-50 text-left dark:border-zinc-700 dark:bg-zinc-800"
                            @click="src='{{ Storage::url($image->path) }}'; scale=1; open=true">
                            <img src="{{ Storage::url($image->path) }}" alt="Evidencia del reporte"
                                class="size-20 object-cover transition duration-300 group-hover:scale-105 md:size-40">
                        </button>
                    @endif
                @endforeach
            </div>
        @else
            <flux:text class="mt-3 text-sm text-zinc-500">
                No hay evidencias fotograficas registradas.
            </flux:text>
        @endif

        <div x-show="open" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div class="absolute right-2 top-2 z-10 flex items-center gap-2 overflow-x-auto">
                <flux:button size="sm" variant="ghost" type="button" icon="minus"
                    @click="scale = Math.max(1, scale - 0.25)" square>
                </flux:button>
                <flux:button size="sm" variant="ghost" type="button" icon="plus"
                    @click="scale = Math.min(3, scale + 0.25)" square>
                </flux:button>
                <flux:button size="sm" variant="ghost" type="button" icon="arrow-path" @click="scale = 1"
                    square>
                </flux:button>
                <flux:button size="sm" variant="ghost" type="button" icon="x-mark" @click="open=false"
                    square>
                </flux:button>
            </div>
            <div class="relative w-full max-w-5xl">
                <img :src="src" alt="Evidencia ampliada"
                    class="mx-auto max-h-[80vh] w-auto transition duration-200"
                    :style="`transform: scale(${scale})`" />
            </div>
        </div>
    </div>
</div>
