@props(['color' => 'primary', 'size' => 'md', 'href' => null])

@php
    // Use native Bootstrap sizing classes instead of manual padding
    $sizeClass = ($size === 'sm') ? 'btn-sm' : (($size === 'lg') ? 'btn-lg' : '');

    $textColor = ($color === 'primary') ? 'text-white' : 'text-dark';
    $colorClass = ($color === 'info') ? 'btn-info' : "btn-$color";
@endphp

<{{ $href ? 'a' : 'button' }}
{{ $href ? "href=$href" : '' }}
{{ $attributes->merge(['class' => "btn $colorClass $sizeClass $textColor fw-bold shadow-sm rounded"]) }}
>
{{ $slot }}
</{{ $href ? 'a' : 'button' }}>
