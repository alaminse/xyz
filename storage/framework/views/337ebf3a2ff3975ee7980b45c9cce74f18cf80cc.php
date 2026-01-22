<!-- jQuery -->
<script src="<?php echo e(asset('backend/vendors/jquery/dist/jquery.min.js')); ?>"></script>
<!-- Bootstrap -->
<script src="<?php echo e(asset('backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- FastClick -->

<!-- NProgress -->

<!-- Chart.js -->

<!-- gauge.js -->

<!-- bootstrap-progressbar -->

<!-- iCheck -->

<!-- Skycons -->

<!-- Flot -->

<!-- Flot plugins -->

<!-- DateJS -->

<!-- JQVMap -->


<!-- bootstrap-daterangepicker -->


<script src="<?php echo e(asset('backend/build/js/custom.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/custome.js')); ?>"></script>
<script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/toastr.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>

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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/assets/js.blade.php ENDPATH**/ ?>