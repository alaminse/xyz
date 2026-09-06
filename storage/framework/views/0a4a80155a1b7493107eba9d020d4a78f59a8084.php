<?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e(++$key); ?></td>
        <td><?php echo e($assessment->name); ?></td>
        <td>
            <?php $__currentLoopData = explode(', ', $assessment->course_names); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge bg-success me-1 mb-1 px-2 text-white"><?php echo e($courseName); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </td>
        <td><?php echo e($assessment->getChapterName()); ?></td>
        <td><?php echo e($assessment->getLessonName()); ?></td>
        <td><?php echo e($assessment->isPaid ? 'Paid' : 'Free'); ?></td>
        <td>
            <a href="<?php echo e(route('admin.assessments.status', $assessment->id)); ?>" class="btn btn-<?php echo e(\App\Enums\Status::from($assessment->status)->message()); ?> btn-sm">
                <?php echo e(\App\Enums\Status::from($assessment->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.assessments.edit', $assessment->id)); ?>">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.assessments.show', $assessment->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-danger btn-sm" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.assessments.destroy', $assessment->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/assessment_rows.blade.php ENDPATH**/ ?>