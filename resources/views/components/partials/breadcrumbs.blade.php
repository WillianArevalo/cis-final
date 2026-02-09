@php $breadcrumbs = Breadcrumbs::generate(); @endphp

@if (!empty($breadcrumbs))
    <flux:breadcrumbs size="sm" class="overflow-x-auto whitespace-nowrap">
        @foreach ($breadcrumbs as $breadcrumb)
            @php
                $icon = $breadcrumb->icon ?? '';
                $title = $breadcrumb->title ?? 'Untitled';
            @endphp

            @if ($breadcrumb->url && !$loop->last)
                <flux:breadcrumbs.item href="{{ $breadcrumb->url }}" wire:navigate icon="{{ $icon }}">
                    {{ $title }}
                </flux:breadcrumbs.item>
            @else
                <flux:breadcrumbs.item icon="{{ $icon }}">
                    {{ $title }}
                </flux:breadcrumbs.item>
            @endif
        @endforeach
    </flux:breadcrumbs>
@endif
