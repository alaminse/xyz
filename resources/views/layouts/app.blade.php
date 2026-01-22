<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('uploads/logo/favicon.png') }}">
    <link href="{{ asset('frontend/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('bootstrap-icons-1.11.3/font/bootstrap-icons.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>
        @yield('title')
    </title>
    @yield('css')
    @livewireStyles
</head>

<body>
    <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
        @include('frontend.includes.sidebar')
    </div>

    <div class="p-1 my-container active-cont">
        <nav class="navbar top-navbar navbar-light top-height">
            <div class="d-flex justify-content-between">
                <a class="btn border-0" id="menu-btn"><i class="bi bi-list"></i></a>
            </div>
        </nav>

        <div class="main p-3" id="main">
            @yield('content')
        </div>
    </div>


    <script src="{{ asset('frontend/bootstrap-5.3.3/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/bootstrap-5.3.3/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontend/bootstrap-5.3.3/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dashboard.js') }}"></script>

    @stack('scripts')
    @livewireScripts
</body>

</html>
