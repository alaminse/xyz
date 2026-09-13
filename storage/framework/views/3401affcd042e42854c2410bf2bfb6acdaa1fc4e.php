<?php $__env->startSection('title', 'Notices'); ?>

<?php $__env->startSection('content'); ?>
<section class="notice-page-header">
    <div class="container">
        <h1 class="mb-2">Notices</h1>
        <p class="text-muted mb-0">Class updates, schedule changes, and announcements from MediManiac.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php $__empty_1 = true; $__currentLoopData = $notices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('notices.show', $notice->slug)); ?>" class="notice-list-item text-decoration-none">
                        <span class="notice-type-badge badge-<?php echo e($notice->type); ?>"><?php echo e(ucfirst($notice->type)); ?></span>
                        <h3 class="notice-list-title"><?php echo e($notice->title ?? 'Notice'); ?></h3>
                        <span class="notice-list-date"><?php echo e($notice->created_at->format('d M, Y')); ?></span>
                        <p class="notice-list-excerpt"><?php echo e(Str::limit(strip_tags($notice->message), 140)); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="alert alert-info">No notices published yet.</div>
                <?php endif; ?>

                <div class="mt-4">
                    <?php echo e($notices->links()); ?>

                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/notices/index.blade.php ENDPATH**/ ?>