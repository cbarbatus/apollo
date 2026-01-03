<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CTZCPCHVZ5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-CTZCPCHVZ5');
    </script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=BAAhO9Kdh6sldcZSbvKxZKzHD8jOR2jCGn2M5_gjh1l4xpz6dPnaGxMzvUYAIqIerToxV5DsJLDVWvAwjQ&components=hosted-buttons&enable-funding=venmo&currency=USD">
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Apollo Raven's Cry Grove, ADF</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="canonical" href='{{ url()->current() }}'>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Define a CSS variable using the Laravel asset() helper */
        :root {
            --header-image-url: url("{{ asset('img/rcg_large_text.png') }}");
            --header-image-tree-url: url("{{ asset('img/newtree2560.jpg') }}");
        }
    </style>
</head>
<body>

<div id="app">
    <nav class="navbar navbar-expand-md bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand px-2 text-white" href="{{ url('/') }}">
                Home
            </a>
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span >Menu</span>
            </button>

            <div class="navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav list-unstyled">
                    <li class="nav-item">
                        <a class="px-2 text-white" href="{{ url('/slideshows') }}">Photos</a></li>
                    <li class="nav-item">
                        <a class="px-2 text-white" href="{{ url('/rituals') }}">Rituals</a></li>
                    <li class="nav-item">
                        <a class="px-2 text-white" href="{{ url('/books') }}">Books</a></li>                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Member Login') }}</a>
                        </li>
                    @else


                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                @role(['SeniorDruid', 'admin'])
                                $ SeniorDruid:
                                <a  class="dropdown-item" href="/announcements"> $ Announcements </a>
                                <a  class="dropdown-item" href="/schedule"> $ Schedule </a>
                                <a  class="dropdown-item" href="/venues"> $ Venues </a>
                                <a  class="dropdown-item" href="/books"> $ Books </a>
                                <a  class="dropdown-item" href="/contacts"> $ Contacts </a>
                                <a  class="dropdown-item" href="/members/newmembers"> $ New Members </a>
                                <a  class="dropdown-item" href="/rituals/editNames"> $ Ritual Names </a>
                                <a  class="dropdown-item" href="/rituals/editCultures"> $ Ritual Cultures </a>
                                <a  class="dropdown-item" href="/sections"> $ Sections </a>

                                @endrole

                                @role('admin')
                                * Admin:
                                <a  class="dropdown-item" href="/users"> * Users </a>
                                <a  class="dropdown-item" href="/grove/upload"> * Upload </a>
                                <a  class="dropdown-item" href="/roles"> * Roles </a>

                                @endrole

                                @role('joiner')
                                <a  class="dropdown-item" href="/members/join"> Join Us! </a>
                                @endrole

                                @role('member')
                                Members Only:

                                <a  class="dropdown-item" href="/members"> Members </a>
                                <a  class="dropdown-item" href="/liturgy/find"> Liturgy </a>
                                <a  class="dropdown-item" href="/grove/bylaws"> Bylaws </a>
                                <a  class="dropdown-item" href="/grove/pay"> Pay Dues </a>
                                @endrole

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

            </div>
        </div>
    </nav>

    <div class="startpad">
        <div class="content">





                <main class="py-4">
                    <div class="container mt-4">
                        <x-alert-danger />
                        <x-alert-success />
                    @yield('content')
                </div>
            </main>

                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title confirmation-title" id="confirmDeleteModalLabel">
                                    Confirm Deletion
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body confirmation-body">
                                Are you absolutely sure you want to delete this item? This action cannot be undone.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="modal-confirm-delete">Yes, Delete It</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">



<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
{{-- This must be in your main layout file, usually near the </body> tag --}}

@stack('scripts')

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0">Are you sure you want to delete this item? This action is irreversible.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="modal-confirm-delete">Yes, Delete It</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirmDeleteModal');
        const confirmBtn = document.getElementById('modal-confirm-delete');
        let actionUrl = '';

        if (modal && confirmBtn) {
            modal.addEventListener('show.bs.modal', function (event) {
                // 'event.relatedTarget' is the "Delete" button you actually clicked
                const button = event.relatedTarget;
                actionUrl = button.getAttribute('data-action');
            });

            confirmBtn.addEventListener('click', function () {
                if (actionUrl) {
                    const form = document.getElementById('global-delete-form');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        }
    });
</script>

<form id="global-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

</body>
</html>
