<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('frontend.includes.meta')

        @include('frontend.includes.links')
        <title>
            @yield('title')
        </title>
        @yield('css')
    </head>
    <body class="antialiased">
      <div class="wrapper">
            @include('frontend.includes.navbar')

            @yield('content')

            @include('frontend.includes.footer')

            @include('frontend.includes.script')
            <script src="{{ asset('frontend/js/script.js') }}"></script>
    </body>
</html>
