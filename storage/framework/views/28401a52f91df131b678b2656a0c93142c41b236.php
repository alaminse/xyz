<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <?php echo $__env->make('frontend.includes.meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php echo $__env->make('frontend.includes.links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <title>
            <?php echo $__env->yieldContent('title'); ?>
        </title>
        <?php echo $__env->yieldContent('css'); ?>
    </head>
    <body class="antialiased">
      <div class="wrapper">
            <?php echo $__env->make('frontend.includes.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>

            <?php echo $__env->make('frontend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->make('frontend.includes.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <script src="<?php echo e(asset('frontend/js/script.js')); ?>"></script>
    </body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/layouts/frontend.blade.php ENDPATH**/ ?>