<?php $__env->startSection('title', 'Written Assessment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-12">

            <div class="card shadow-sm border-0">
                <!-- Header -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fa fa-file-text-o text-primary"></i>
                        Written Assessment Details
                    </h5>

                    <a href="<?php echo e(route('admin.writtenassessments.index')); ?>"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <!-- Meta Info -->
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted font-weight-bold">
                            Chapter
                        </div>
                        <div class="col-md-9">
                            <?php echo e($written->chapter->name); ?>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 text-muted font-weight-bold">
                            Lesson
                        </div>
                        <div class="col-md-9">
                            <?php echo e($written->lesson->name); ?>

                        </div>
                    </div>

                    <hr>

                    <!-- Question -->
                    <div class="mb-4">
                        <div class="text-muted font-weight-bold mb-2">
                            Question
                        </div>
                        <div class="p-3 bg-light border rounded">
                            <?php echo e($written->question); ?>

                        </div>
                    </div>

                    <!-- Answer -->
                    <div class="mb-4">
                        <div class="text-muted font-weight-bold mb-2">
                            Answer
                        </div>
                        <div class="p-3 border rounded bg-white">
                            <?php echo $written->answer; ?>

                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="text-right">
                        <a href="<?php echo e(route('admin.writtenassessments.edit', $written->id)); ?>"
                           class="btn btn-sm btn-primary mr-1">
                            <i class="fa fa-edit"></i> Edit
                        </a>

                        <a href="<?php echo e(route('admin.writtenassessments.destroy', $written->id)); ?>"
                           onclick="showDeleteConfirmation(event)"
                           class="btn btn-sm btn-danger <?php echo e($written->status == 3 ? 'disabled' : ''); ?>">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/written-assessment/show.blade.php ENDPATH**/ ?>