<?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($video->title); ?></td>
        <td>
            <?php $__empty_1 = true; $__currentLoopData = $video->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <span class="badge badge-primary"><?php echo e($course->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span class="text-muted">N/A</span>
            <?php endif; ?>
        </td>
        <td><?php echo e($video->getChapterName()); ?></td>
        <td>
            <a href="#" class="btn btn-<?php echo e(\App\Enums\IsPaid::from($video->isPaid)->message()); ?> btn-sm">
                <?php echo e(\Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($video->isPaid)->title(), 100, '....')); ?>

            </a>
        </td>

        <td>
            <a href="<?php echo e(route('admin.lecturevideos.status', $video->id)); ?>" class="btn btn-<?php echo e(\App\Enums\Status::from($video->status)->message()); ?> btn-sm">
                <?php echo e(\App\Enums\Status::from($video->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.lecturevideos.show', $video->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.lecturevideos.edit', $video->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger btn-sm <?php echo e($video->status == 3 ? 'disabled' : ''); ?>" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.lecturevideos.destroy', $video->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/lecture-video_row.blade.php ENDPATH**/ ?>