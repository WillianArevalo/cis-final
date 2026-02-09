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
     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         {{ $attributes->class($classes) }} data-flux-icon>
         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
         <path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z" />
     </svg>
