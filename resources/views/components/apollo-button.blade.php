@props(['color' => 'primary', 'size' => 'md', 'href' => null])

@php
    $sizeClass = ($size === 'sm') ? 'btn-sm' : (($size === 'lg') ? 'btn-lg' : '');

    $textColor = in_array($color, ['primary', 'secondary'])
                 ? 'text-white'
                 : 'text-dark';

    $colorClass = ($color === 'info') ? 'btn-info' : "btn-$color";

    // Add href to the attributes bag programmatically to avoid quote nesting
    if ($href) {
        $attributes = $attributes->merge(['href' => $href]);
    }
@endphp

<{{ $href ? 'a' : 'button' }}
    {{ $attributes->merge(['class' => "btn $colorClass $sizeClass $textColor fw-bold shadow-sm rounded"]) }}
    @if($textColor === 'text-white') style="color: white !important;" @endif
>
{{ $slot }}
</{{ $href ? 'a' : 'button' }}>
