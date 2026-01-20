@props(['color' => 'primary', 'size' => 'md', 'href' => null])

@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $sizeClass = ($size === 'sm') ? 'btn-sm' : (($size === 'lg') ? 'btn-lg' : '');

    $textColor = in_array($color, ['primary', 'secondary'])
                 ? 'text-white'
                 : 'text-dark';

    $colorClass = ($color === 'info') ? 'btn-info' : "btn-$color";

    if ($href) {
        $attributes = $attributes->merge(['href' => $href]);
    }

    $classes = "btn $colorClass $sizeClass $textColor fw-bold shadow-sm rounded";
    $style = ($textColor === 'text-white') ? 'color: white !important;' : '';
@endphp

@if($href)
    <a {{ $attributes->merge(['class' => $classes]) }} style="{{ $style }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }} style="{{ $style }}">
        {{ $slot }}
    </button>
@endif
