@php $attributes = $unescapedForwardedAttributes ?? $attributes; @endphp

@props([
    'variant' => 'outline',
])

@php
    $classes = Flux::classes('shrink-0')->add(
        match ($variant) {
            'outline' => '[:where(&)]:size-6',
            'solid' => '[:where(&)]:size-6',
            'mini' => '[:where(&)]:size-5',
            'micro' => '[:where(&)]:size-4',
        },
    );
@endphp

<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000" {{ $attributes->class($classes) }} data-flux-icon>
    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
    <g id="SVGRepo_iconCarrier">
        <g fill="none" fill-rule="evenodd">
            <circle cx="7" cy="12" r="7" fill="#EA001B"></circle>
            <circle cx="17" cy="12" r="7" fill="#FFA200" fill-opacity=".8"></circle>
        </g>
    </g>
</svg>
