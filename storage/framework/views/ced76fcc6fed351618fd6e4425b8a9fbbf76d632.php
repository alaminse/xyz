<?php $__env->startSection('title', 'Terms And Conditions'); ?>
<?php $__env->startSection('content'); ?>
    <section class="about-us">
        <div class="container" style="overflow: hidden">
            <?php if(isset($value) && !empty($value)): ?>
                <?php echo e($value); ?>

            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/terms.blade.php ENDPATH**/ ?>