@props(['id', 'action', 'title' => 'Are you sure?', 'buttonText' => 'Confirm'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-body p-4 text-center">
                <h5 class="fw-bold mb-3">{{ $title }}</h5>
                <p class="text-muted mb-4">
                    {{ $slot }}
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px;">
                        Cancel
                    </button>

                    <form action="{{ $action }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <x-apollo-button type="submit" color="danger" class="px-4">
                            {{ $buttonText }}
                        </x-apollo-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
