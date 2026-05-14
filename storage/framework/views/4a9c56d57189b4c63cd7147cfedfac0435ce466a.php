<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('uploads/logo/favicon.png')); ?>">
    <link href="<?php echo e(asset('frontend/bootstrap-5.3.3/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/dashboard.css')); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap-icons-1.11.3/font/bootstrap-icons.css')); ?>">
    <title>
        <?php echo $__env->yieldContent('title'); ?>
    </title>
    <?php echo $__env->yieldContent('css'); ?>
    <style>
        .button-yellow {
            height: 38px !important;
        }
    </style>
    <?php echo \Livewire\Livewire::styles(); ?>

</head>

<body>
    <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
        <?php echo $__env->make('frontend.includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="p-1 my-container active-cont">
        <nav class="navbar top-navbar navbar-light top-height">
            <div class="d-flex justify-content-between text-white">
                <a class="btn border-0" id="menu-btn"><i class="bi bi-list"></i></a>
                <a class="btn border-0 text-white fs-2 mt-1" target="__blank" href="<?php echo e(url('/')); ?>"><i class="bi bi-globe-asia-australia"></i></a>
            </div>
            <div class="text-light me-3">
                <div class="btn-group">
                    <div type="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo e(getProfile(Auth::user()?->profile?->photo)); ?>" alt="Rounded Image" class="rounded-circle">
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a href="<?php echo e(route('profile')); ?>" class="dropdown-item">Profile</a></li>
                      <li><a href="<?php echo e(route('password.change')); ?>" class="dropdown-item">Password Change</a></li>
                      <form action="<?php echo e(route('logout')); ?>" method="POST">
                          <?php echo csrf_field(); ?>
                          <li><button type="submit" class="dropdown-item">Logout</button></li>
                      </form>

                    </ul>
                </div>
            </div>
        </nav>

        <div class="main p-3" id="main">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/jquery-3.7.1.min.js')); ?> "></script>
    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/popper.min.js')); ?>">
    </script>
    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/bootstrap.min.js')); ?>">
    </script>
    <script src="<?php echo e(asset('frontend/js/dashboard.js')); ?>"></script>
    <script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/toastr.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo \Livewire\Livewire::scripts(); ?>


    <script>
        <?php if(Session::has('success')): ?>
            toastr.success("<?php echo e(Session::get('success')); ?>");
        <?php endif; ?>

        <?php if(Session::has('error')): ?>
            toastr.error("<?php echo e(Session::get('error')); ?>");
        <?php endif; ?>

        <?php if(Session::has('warning')): ?>
            toastr.warning("<?php echo e(Session::get('warning')); ?>");
        <?php endif; ?>

        <?php if(Session::has('info')): ?>
            toastr.info("<?php echo e(Session::get('info')); ?>");
        <?php endif; ?>
    </script>
</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/app.blade.php ENDPATH**/ ?>