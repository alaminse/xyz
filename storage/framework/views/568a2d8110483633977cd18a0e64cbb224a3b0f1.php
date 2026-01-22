<header class="header">
    <div class="topbar topbar-bg">
        <div class="container-xl d-flex">
            <div class="p-2 flex-grow-1">
                <?php
                    $socials = socials();
                ?>
                <div class="social-group">
                    <a href="https://<?php echo e($socials['linkedin'] ?? '#'); ?>" target="__blank">
                        <i class="bi bi-linkedin text-light" style="font-size: 20px;"></i>
                    </a>
                    <a href="https://<?php echo e($socials['facebook'] ?? '#'); ?>" target="__blank" class="px-4">
                        <i class="bi bi-facebook text-light" style="font-size: 20px;"></i>
                    </a>
                    <a href="https://<?php echo e($socials['instagram'] ?? '#'); ?>" target="__blank">
                        <i class="bi bi-instagram text-light" style="font-size: 20px;"></i>
                    </a>
                </div>
            </div>
            <?php if(auth()->guard()->guest()): ?>
                <div class="p-2">
                    <a href="<?php echo e(route('login')); ?>" class="text-white">Login /</a>
                </div>
                <div class="p-2">
                    <a href="<?php echo e(route('register')); ?>" class="text-white">Register</a>
                </div>
            <?php else: ?>
                <div class="p-2">
                    <?php if(!Auth::user()->hasRole('admin')): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-white">Dashboard</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- menu bar -->
    <nav class="navbar navbar-expand-lg navbar-dark menu-bg px-0 py-3">
        <div class="container-xl">
            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <img loading="lazy" src="<?php echo e(asset('uploads/logo/logo.png')); ?>" class="h-9" alt="...">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-lg-auto">
                    <a class="nav-item nav-link <?php echo e(Route::is('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>"
                        aria-current="page">Home</a>
                    <a class="nav-item nav-link <?php echo e(Route::is('about') ? 'active' : ''); ?>"
                        href="<?php echo e(route('about')); ?>">About</a>
                    <a class="nav-item nav-link <?php echo e(Route::is('courses.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('courses.index')); ?>">Course</a>
                    <a class="nav-item nav-link <?php echo e(Route::is('contact') ? 'active' : ''); ?>"
                        href="<?php echo e(route('contact')); ?>">Contact</a>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/includes/navbar.blade.php ENDPATH**/ ?>