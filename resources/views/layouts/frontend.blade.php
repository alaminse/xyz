<!DOCTYPE html>
<html lang="en">
<head>
    @include('frontend.includes.links')
    @yield('css')
</head>
<body>


    {{-- ===================== NAVBAR ===================== --}}
    @include('frontend.includes.navbar')

    {{-- ===================== PAGE CONTENT ===================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    @include('frontend.includes.footer')
</body>
</html>
