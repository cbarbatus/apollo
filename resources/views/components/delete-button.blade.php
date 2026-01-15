@props([
    'action' => null,
    'resource' => 'item',
    'method' => 'DELETE'
])

@php
    // We cast to string immediately.
    // If it's a Spatie object, we grab the ID manually to prevent the JSON string.
    $actionUrl = is_object($action) ? url('/roles/' . $action->id) : (string)$action;

    // Use the cleaned string for the MD5
    $modalId = 'deleteModal' . md5($actionUrl);
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
    :action="$actionUrl"
    :method="$method"
    :resource="$resource"
    title="Confirm Action"
/>
