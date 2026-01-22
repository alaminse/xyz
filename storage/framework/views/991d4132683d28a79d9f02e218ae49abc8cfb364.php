

<?php $__env->startSection('title', 'Edit Lesson'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Lesson</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="<?php echo e(route('admin.lessons.index')); ?>">Back</a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form action="<?php echo e(route('admin.lessons.update', $lesson->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- BASIC INFO -->
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="font-weight-bold">
                                    Lesson Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="<?php echo e(old('name', $lesson->name)); ?>"
                                       required>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <label class="font-weight-bold">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo e($lesson->status == 1 ? 'selected' : ''); ?>>Active</option>
                                    <option value="2" <?php echo e($lesson->status == 2 ? 'selected' : ''); ?>>Inactive</option>
                                    <option value="3" <?php echo e($lesson->status == 3 ? 'selected' : ''); ?>>Deleted</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LESSON HAS -->
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3">Lesson Has</h6>

                        <div class="row">
                            <?php $__currentLoopData = [
                                'sba' => 'SBA',
                                'note' => 'Note',
                                'mcq' => 'MCQ',
                                'flush' => 'Flush',
                                'videos' => 'Video',
                                'ospe' => 'OSPE',
                                'written' => 'Written Assessment',
                                'mock_viva' => 'Mock Viva',
                                'self_assessment' => 'Self Assessment'
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 col-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="<?php echo e($key); ?>"
                                               value="1"
                                               <?php echo e(old($key, $lesson->$key) ? 'checked' : ''); ?>>
                                        <label class="form-check-label">
                                            <?php echo e($label); ?>

                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    </div>
                </div>

                <!-- ACTION -->
                <div class="text-right">
                    <button type="submit" class="btn btn-warning">
                        Update Lesson
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lesson/edit.blade.php ENDPATH**/ ?>