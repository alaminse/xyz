<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $__env->make('backend.assets.meta', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>
        <?php echo $__env->yieldContent('title'); ?>
    </title>
    <?php echo $__env->make('backend.assets.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </head>
  <?php if(auth()->guard()->guest()): ?>
    <?php echo $__env->yieldContent('auth'); ?>
  <?php else: ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">

        <?php echo $__env->make('backend.includes.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- top navigation -->
        <?php echo $__env->make('backend.includes.top_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <?php echo $__env->yieldContent('content'); ?>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <?php echo $__env->make('backend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- /footer content -->
      </div>
    </div>

    <?php echo $__env->make('backend.assets.js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </body>
  <?php endif; ?>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/layouts/backend.blade.php ENDPATH**/ ?>