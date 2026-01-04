@props([
    'action' => null,
    'resource' => 'item',
    'method' => 'DELETE'
])

@php
    // Create a unique ID for this specific button/modal pair
    $modalId = 'deleteModal' . md5($action);
@endphp

<x-apollo-button
    type="button"
    color="danger"
    data-bs-toggle="modal"
    data-bs-target="#{{ $modalId }}"
    {{ $attributes->merge(['class' => 'fw-bold shadow-sm rounded']) }}
>
    {{ $slot->isEmpty() ? ($method === 'POST' ? 'Remove' : 'Delete') : $slot }}
</x-apollo-button>

<x-confirmation-modal
    :id="$modalId"
    :action="$action"
    :method="$method"
    :resource="$resource"
    title="Confirm Action"
/>
