<?php $__env->startSection('title', 'Edit Chapter'); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12">
    <div class="x_panel">

        
        <div class="x_title">
            <h2>Edit Chapter</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="<?php echo e(route('admin.chapters.index')); ?>">
                        Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        
        <div class="x_content">
            <form action="<?php echo e(route('admin.chapters.update', $chapter->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                
                <div class="form-group">
                    <label class="font-weight-semibold">
                        Chapter Name <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo e($chapter->name); ?>"
                        required
                    >
                </div>

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-semibold">Lessons</label>
                            <select name="lesson_ids[]" class="form-control js-example-basic-multiple" multiple>
                                <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option
                                        value="<?php echo e($lesson->id); ?>"
                                        <?php echo e(in_array($lesson->id, $chapter->lessons->pluck('id')->toArray()) ? 'selected' : ''); ?>

                                    >
                                        <?php echo e($lesson->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-semibold">Courses</label>
                            <select name="course_ids[]" class="form-control js-example-basic-multiple" multiple>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option
                                        value="<?php echo e($course->id); ?>"
                                        <?php echo e(in_array($course->id, $chapter->courses->pluck('id')->toArray()) ? 'selected' : ''); ?>

                                    >
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="font-weight-semibold">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php echo e($chapter->status == 1 ? 'selected' : ''); ?>>Active</option>
                        <option value="2" <?php echo e($chapter->status == 2 ? 'selected' : ''); ?>>Inactive</option>
                        <option value="3" <?php echo e($chapter->status == 3 ? 'selected' : ''); ?>>Delete</option>
                    </select>
                </div>

                <hr class="my-4">

                
                <div class="form-group">
                    <label class="font-weight-bold mb-3 d-block">
                        Available Features
                    </label>

                    <div class="card bg-light border-0">
                        <div class="card-body py-3">
                            <div class="row">

                                <?php
                                    $features = [
                                        'sba'             => 'SBA',
                                        'note'            => 'Note',
                                        'mcq'             => 'MCQ',
                                        'flush'           => 'Flush',
                                        'videos'          => 'Videos',
                                        'ospe'            => 'OSPE',
                                        'written'         => 'Written',
                                        'mock_viva'       => 'Mock Viva',
                                        'self_assessment' => 'Self Assessment',
                                    ];
                                ?>

                                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="<?php echo e($key); ?>"
                                                name="<?php echo e($key); ?>"
                                                value="1"
                                                <?php echo e($chapter->$key ? 'checked' : ''); ?>

                                            >
                                            <label class="custom-control-label" for="<?php echo e($key); ?>">
                                                <?php echo e($label); ?>

                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4">
                        Update Chapter
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/chapter/edit.blade.php ENDPATH**/ ?>