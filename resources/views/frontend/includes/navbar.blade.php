<header class="header">
    <div class="topbar topbar-bg">
        <div class="container-xl d-flex">
            <div class="p-2 flex-grow-1">
                @php
                    $socials = socials();
                @endphp
                <div class="social-group">
                    <a href="https://{{$socials['linkedin'] ?? '#'}}" target="__blank">
                        <i class="bi bi-linkedin text-light" style="font-size: 20px;"></i>
                    </a>
                    <a href="https://{{$socials['facebook'] ?? '#'}}" target="__blank" class="px-4">
                        <i class="bi bi-facebook text-light" style="font-size: 20px;"></i>
                    </a>
                    <a href="https://{{$socials['instagram'] ?? '#'}}" target="__blank">
                        <i class="bi bi-instagram text-light" style="font-size: 20px;"></i>
                    </a>
                </div>
            </div>
            @guest
                <div class="p-2">
                    <a href="{{ route('login') }}" class="text-white">Login /</a>
                </div>
                <div class="p-2">
                    <a href="{{ route('register') }}" class="text-white">Register</a>
                </div>
            @else
                <div class="p-2">
                    @if (!Auth::user()->hasRole('admin'))
                        <a href="{{ route('dashboard') }}" class="text-white">Dashboard</a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
    <!-- menu bar -->
    <nav class="navbar navbar-expand-lg navbar-dark menu-bg px-0 py-3">
        <div class="container-xl">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img loading="lazy" src="{{ asset('uploads/logo/logo.png') }}" class="h-9" alt="...">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-lg-auto">
                    <a class="nav-item nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}"
                        aria-current="page">Home</a>
                    <a class="nav-item nav-link {{ Route::is('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">About</a>
                    <a class="nav-item nav-link {{ Route::is('courses.*') ? 'active' : '' }}"
                        href="{{ route('courses.index') }}">Course</a>
                    <a class="nav-item nav-link {{ Route::is('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">Contact</a>
                </div>
                <div class="navbar-nav ms-lg-4">
                    <form id="searchForm" action="#">
                        <div class="search-box">
                            <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
                            <input type="text" class="input-search" placeholder="Type to Search...">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
