<script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('frontend/bootstrap-5.3.3/js/bootstrap.min.js')); ?>"></script>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/includes/script.blade.php ENDPATH**/ ?>