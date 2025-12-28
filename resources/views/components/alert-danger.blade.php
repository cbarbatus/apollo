@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            {{-- Using a Bootstrap Icon for visual weight --}}
            <i class="bi bi-exclamation-octagon-fill me-3 fs-4"></i>
            <div>
                <ul class="mb-0 ps-0" style="list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li class="fw-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Also catch manual session errors --}}
@if (session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-octagon-fill me-3 fs-4"></i>
            <div class="fw-bold">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
