<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('css'); ?>
<style>
    .table td, .table th {
        white-space: nowrap;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm border-0">
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold ps-3 pt-3">My Courses</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px;">Item</th>
                        <th>Course</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Price</th>
                        <th>Sell</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <img
                                    src="<?php echo e(getImageUrl($course->course?->banner)); ?>"
                                    alt="<?php echo e($course->course?->name); ?>"
                                    class="rounded object-fit-cover"
                                    style="width:45px;height:45px;"
                                >
                            </td>

                            <td>
                                <div class="fw-semibold"><?php echo e($course->course?->name); ?></div>
                                <small class="text-muted">
                                    <?php echo e($course->course?->detail?->duration); ?>

                                    <?php echo e($course->course?->detail?->type); ?>

                                </small>
                            </td>

                            <td>
                                <span class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($course->start_date)->format('d M Y')); ?>

                                </span>
                            </td>

                            <td>
                                <span class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($course->end_date)->format('d M Y')); ?>

                                </span>
                            </td>

                            <td>
                                <span class="fw-medium">৳<?php echo e(number_format($course->price, 2)); ?></span>
                            </td>

                            <td>
                                <span class="fw-medium text-success">
                                    ৳<?php echo e(number_format($course->sell_price, 2)); ?>

                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge bg-<?php echo e(\App\Enums\Status::from($course->status)->message()); ?>">
                                    <?php echo e(\App\Enums\Status::from($course->status)->title()); ?>

                                </span>
                            </td>

                            <td class="text-end">
                                <a
                                    target="_blank"
                                    href="<?php echo e(route('courses.invoice', $course->slug)); ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-receipt"></i> Invoice
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No courses found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/enrollments.blade.php ENDPATH**/ ?>