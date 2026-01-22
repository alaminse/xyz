<?php $__currentLoopData = $writtenassessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $written): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($written->question); ?></td>
        <td><?php echo e($written->getCourseNames()); ?></td>
        <td><?php echo e($written->getChapterName()); ?></td>
        <td>
            <a href="#" class="btn btn-<?php echo e(\App\Enums\IsPaid::from($written->isPaid)->message()); ?> btn-sm">
                <?php echo e(\Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($written->isPaid)->title(), 100, '....')); ?>

            </a>
        </td>

        <td>
            <a href="<?php echo e(route('admin.writtenassessments.status', $written->id)); ?>" class="btn btn-<?php echo e(\App\Enums\Status::from($written->status)->message()); ?> btn-sm">
                <?php echo e(\App\Enums\Status::from($written->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.writtenassessments.show', $written->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.writtenassessments.edit', $written->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger btn-sm <?php echo e($written->status == 3 ? 'disabled' : ''); ?>" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.writtenassessments.destroy', $written->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/written-assessment_row.blade.php ENDPATH**/ ?>