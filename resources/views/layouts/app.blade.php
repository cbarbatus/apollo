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

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Apollo Raven's Cry Grove, ADF</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="canonical" href='{{ url()->current() }}'>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">

    <style>
        #accordion-1{font-size: 12px;}
    </style>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <a class="px-2 text-white" href="{{ url('/slideshows/0/list') }}">Photos</a></li>
                    <li class="nav-item">
                        <a class="px-2 text-white" href="{{ url('/rituals/0/list') }}">Past Rituals</a></li>
                    <li class="nav-item">
                        <a class="px-2 text-white" href="{{ url('/books') }}">Books</a></li>                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Member Login') }}</a>
                        </li>
                    @else


                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
                                <a  class="dropdown-item" href="/rituals/1/list"> $ Rituals </a>
                                <a  class="dropdown-item" href="/announcements"> $ Announcements </a>
                                <a  class="dropdown-item" href="/schedule"> $ Schedule </a>
                                <a  class="dropdown-item" href="/venues"> $ Venues </a>
                                <a  class="dropdown-item" href="/books"> $ Books </a>
                                <a  class="dropdown-item" href="/contacts"> $ Contacts </a>
                                <a  class="dropdown-item" href="/slideshows/1/list"> $ Photos </a>
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
                                <a  class="dropdown-item" href="/votes"> Vote </a>
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
            @if (Session::has('message'))
                {{-- Assuming 'message' is often used for success or informational feedback --}}
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

                @if (Session::has('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ Session::get('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class='alert alert-danger'>
                        <h4 class="alert-heading">Validation Errors:</h4>
                        <ul class="mb-0">
                            @foreach ( $errors->all() as $error )
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <main class="py-4">
                <div class="container" >
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
