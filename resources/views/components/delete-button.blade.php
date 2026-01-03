@props(['action', 'resource'])

<form method="POST" action="{{ $action }}" class="d-inline" id="delete-form-{{ $resource }}">
    @csrf
    @method('DELETE')

    <x-apollo-button
        type="button"
        color="danger"
        class="px-4 fw-bold shadow-sm confirm-delete-btn"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-action="{{ $action }}"
        data-resource="{{ $resource }}">
        Delete
    </x-apollo-button>
</form>
