

<?php $__env->startSection('title', 'Note Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12">
    <div class="x_panel">

        <div class="x_title">
    <h2 class="pull-left">Note Details</h2>

    <ul class="nav navbar-right panel_toolbox">
        <li>
            <a href="<?php echo e(route('admin.notes.index')); ?>" class="btn btn-warning btn-sm text-white">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </li>
    </ul>

    <div class="clearfix"></div>
</div>


        
        <div class="x_content">

            <div class="row">

                
                <div class="col-md-4">

                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">
                            Information
                        </div>
                        <div class="card-body">

                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Courses</small>
                                <?php $__empty_1 = true; $__currentLoopData = $note->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <span class="badge badge-primary mr-1 mb-1">
                                        <?php echo e($course->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </div>

                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Chapter</small>
                                <strong><?php echo e($note->chapter->name ?? '-'); ?></strong>
                            </div>

                            
                            <div class="mb-3">
                                <small class="text-muted d-block">Lesson</small>
                                <strong><?php echo e($note->lesson->name ?? '-'); ?></strong>
                            </div>

                        </div>
                    </div>

                </div>

                
                <div class="col-md-8">

                    
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">
                            Note Title
                        </div>
                        <div class="card-body">
                            <?php echo e($note->title); ?>

                        </div>
                    </div>

                    
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            Description
                        </div>
                        <div class="card-body bg-light">
                            <?php echo $note->description; ?>

                        </div>
                    </div>

                </div>

            </div>

            
            <div class="text-right mt-4">
                <a href="<?php echo e(route('admin.notes.edit', $note->id)); ?>"
                   class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>

                <a href="<?php echo e(route('admin.notes.destroy', $note->id)); ?>"
                   class="btn btn-danger <?php echo e($note->status == 3 ? 'disabled' : ''); ?>"
                   onclick="showDeleteConfirmation(event)">
                    <i class="fa fa-trash"></i> Delete
                </a>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/note/show.blade.php ENDPATH**/ ?>