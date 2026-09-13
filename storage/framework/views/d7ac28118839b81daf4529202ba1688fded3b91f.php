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
        <td><?php echo e($assessment->user_progress_count); ?> users attended</td>
        <td>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.assessments.leaderboard', $assessment->id)); ?>">
                <i class="fa fa-edit"></i> Details
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/user_progress_rows.blade.php ENDPATH**/ ?>