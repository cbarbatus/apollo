@props(['action' => null, 'resource' => 'item'])

@php
    // Pre-calculate the onclick string so we don't break the HTML tag below
    $onclick = $action ? "confirmDelete('{$action}', '{$resource}')" : null;
@endphp

<x-apollo-button
    type="button"
    color="danger"
    size="sm"
    {{ $attributes->merge(['class' => 'fw-bold shadow-sm rounded', 'onclick' => $onclick]) }}
>
    {{ $slot }}
</x-apollo-button>
