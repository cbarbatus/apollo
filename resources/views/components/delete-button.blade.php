@props(['action', 'resource'])

<form method="POST" action="{{ $action }}" class="d-inline"
      onsubmit="return confirm('Are you sure you want to delete this {{ $resource }}? This action is irreversible.');">

    @csrf
    @method('DELETE')

    <button type="button" class="btn btn-sm btn-danger confirm-delete-btn"
            data-bs-toggle="modal"
            data-bs-target="#confirmDeleteModal"
            data-action="{{ $action }}">
        Delete
    </button>
</form>
