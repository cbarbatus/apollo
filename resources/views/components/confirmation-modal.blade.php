@props([
    'id',
    'action',
    'method' => 'DELETE',
    'resource' => 'item',
    'title' => 'Confirm Action'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4 text-center">
                @if($slot->isNotEmpty())
                    {!! $slot !!}
                @else
                    Are you sure you want to {{ strtoupper($method) === 'POST' ? 'remove' : 'delete' }} this <strong>{{ $resource }}</strong>?
                @endif
            </div>

            <div class="modal-footer bg-light justify-content-center">
                {{-- THE FORM START --}}
                <form action="{{ $action }}" method="POST">
                    @csrf

                    {{-- THE CONDITIONAL METHOD SPOOFER --}}
                    @if(strtoupper($method) !== 'POST')
                        @method($method)
                    @endif

                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Confirm</button>
                </form>
                {{-- THE FORM END --}}
            </div>
        </div>
    </div>
</div>
