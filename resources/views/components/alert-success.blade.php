@if (session('success'))
    {{-- Added border-0, shadow-sm, px-4, and fw-bold to match the buttons --}}
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm px-4 fw-bold" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
