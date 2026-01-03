{{-- Wrap your existing delete logic/modal trigger in the standard component --}}
<x-apollo-button
    type="button"
    color="danger"
    size="sm"
    {{-- This ensures it matches the height/weight of Edit/Activate --}}
    {{ $attributes->merge(['class' => 'fw-bold shadow-sm rounded']) }}
    onclick="confirmDelete('{{ $action }}', '{{ $resource }}')"
>
    Delete
</x-apollo-button>
