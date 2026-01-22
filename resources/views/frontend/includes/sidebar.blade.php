<div class="sidebar-container">
    <div class="logo-box">
        <a href="{{ route('home') }}">
            <img src="{{ asset('uploads/logo/logo.png') }}" alt="Logo">
        </a>
    </div>

    <ul class="nav flex-column">

        {{-- Dashboard --}}
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="bx bxs-dashboard me-2"></i> Dashboard
            </a>
        </li>

        {{-- Enrolled Courses --}}
        <li class="nav-item {{ request()->is('enrollments') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('enrollments') }}">
                <i class="bx bx-book me-2"></i> Enrolled Courses
            </a>
        </li>

        {{-- Courses --}}
        <li class="nav-item {{ request()->is('all.courses') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('all.courses') }}">
                <i class="bx bx-book me-2"></i> All Courses
            </a>
        </li>

        <div class="header-title">My Courses</div>

        @php
            $currentCourseSlug = request()->segment(2);

            $modules = [
                'flashs' => [
                    'enabled' => 'flush',
                    'icon' => 'bx-layer',
                    'title' => 'Flash Cards',
                    'route' => 'flashs.index',
                ],
                'sbas' => [
                    'enabled' => 'sba',
                    'icon' => 'bx-list-check',
                    'title' => 'SBA',
                    'route' => 'sbas.index',
                ],
                'mcqs' => [
                    'enabled' => 'mcq',
                    'icon' => 'bx-checkbox-checked',
                    'title' => 'MCQ',
                    'route' => 'mcqs.index',
                ],
                'notes' => [
                    'enabled' => 'note',
                    'icon' => 'bx-notepad',
                    'title' => 'Notes',
                    'route' => 'notes.index',
                ],
                'writtens' => [
                    'enabled' => 'written',
                    'icon' => 'bx-edit',
                    'title' => 'Written Assessment',
                    'route' => 'writtens.index',
                ],
                'videos' => [
                    'enabled' => 'videos',
                    'icon' => 'bx-video',
                    'title' => 'Lecture Video',
                    'route' => 'videos.index',
                ],
                'mockvivas' => [
                    'enabled' => 'mock_viva',
                    'icon' => 'bx-microphone',
                    'title' => 'Mock Viva',
                    'route' => 'mockvivas.index',
                ],
                'ospes' => [
                    'enabled' => 'ospe',
                    'icon' => 'bx-user-check',
                    'title' => 'OSPE Station',
                    'route' => 'ospes.index',
                ],
                'assessments' => [
                    'enabled' => 'self_assessment',
                    'icon' => 'bx-task',
                    'title' => 'Assessments',
                    'route' => 'assessments.index',
                    'group' => 'Self Assessment',
                ],
            ];

            $activeRouteGroup = collect(array_keys($modules))->first(fn($key) => request()->routeIs($key . '.*'));
        @endphp

        @foreach (enrolled_courses() as $item)
            @php
                $isCurrentCourse = $currentCourseSlug === $item['slug'];
                $isExpanded = $activeRouteGroup && $isCurrentCourse;
            @endphp

            <li class="nav-item {{ $isExpanded ? 'active' : '' }}">
                <a class="nav-link {{ $isExpanded ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    href="#course-{{ $item['slug'] }}" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
                    <i class="bx bx-folder me-2"></i>
                    {{ $item['name'] }}
                </a>

                <ul class="collapse {{ $isExpanded ? 'show' : '' }}" id="course-{{ $item['slug'] }}">

                    @foreach ($modules as $routeKey => $module)
                        @continue(!($item[$module['enabled']] ?? false))

                        @if (!empty($module['group']))
                            <div class="submenu-title">{{ $module['group'] }}</div>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link dropdown-item
                            {{ request()->routeIs($routeKey . '.*') && $isCurrentCourse ? 'active' : '' }}"
                                href="{{ route($module['route'], $item['slug']) }}">
                                <i class="bx {{ $module['icon'] }} me-2"></i>
                                {{ $module['title'] }}
                            </a>
                        </li>
                    @endforeach

                </ul>
            </li>
        @endforeach


    </ul>
</div>

<style>
    .sidebar-container {
        height: 100vh;
        overflow-y: auto;
        background: linear-gradient(180deg, #020b1c, #03132e);
        color: #ffffff;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-container::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-container::-webkit-scrollbar-thumb {
        background: rgba(248, 184, 74, 0.3);
        border-radius: 10px;
    }

    .sidebar-container::-webkit-scrollbar-thumb:hover {
        background: rgba(248, 184, 74, 0.5);
    }

    .logo-box {
        padding: 20px;
        background: rgba(0, 0, 0, 0.3);
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-box img {
        max-width: 140px;
        filter: brightness(1.1);
    }

    .header-title {
        padding: 15px 20px 8px;
        font-size: 11px;
        color: #9aa4b2;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 600;
        margin-top: 10px;
    }

    .nav-item>a {
        padding: 12px 20px;
        color: #eaeaea;
        display: flex;
        align-items: center;
        font-size: 14px;
        transition: all .3s ease;
        border-left: 3px solid transparent;
        text-decoration: none;
    }

    .nav-item>a i {
        font-size: 18px;
        width: 24px;
    }

    .nav-item>a:hover {
        color: #f8b84a;
        background: rgba(248, 184, 74, 0.08);
        border-left-color: rgba(248, 184, 74, 0.3);
    }

    .nav-item.active>a {
        background: rgba(248, 184, 74, 0.15);
        color: #f8b84a !important;
        border-left-color: #f8b84a;
        font-weight: 500;
    }

    .collapse {
        background: rgba(0, 0, 0, 0.2);
        margin: 0 10px 8px 10px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .collapse .nav-link {
        padding: 10px 15px 10px 35px;
        font-size: 13px;
        border-left: none;
    }

    .collapse .nav-link i {
        font-size: 16px;
        opacity: 0.7;
    }

    .dropdown-item.active {
        background: rgba(248, 184, 74, 0.2) !important;
        color: #f8b84a !important;
        border-left: 2px solid #f8b84a;
        border-radius: 4px;
        margin: 2px 5px;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #f8b84a;
    }

    .submenu-title {
        padding: 12px 20px 6px;
        font-size: 10px;
        color: #7a8794;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-top: 8px;
    }

    /* Smooth collapse animation */
    .collapse {
        transition: all 0.3s ease-in-out;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .sidebar-container {
            height: auto;
        }

        .logo-box img {
            max-width: 100px;
        }
    }
</style>
