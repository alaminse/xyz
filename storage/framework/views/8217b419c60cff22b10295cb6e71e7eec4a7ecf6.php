<?php $__currentLoopData = $sbas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sba): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td>
            <span class="badge badge-info"><?php echo e($sba->questions_count); ?> Questions</span>
        </td>
        <td>
            <?php $__currentLoopData = $sba->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge badge-primary"><?php echo e($c->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </td>
        <td><?php echo e($sba->chapter?->name ?? 'N/A'); ?></td>
        <td><?php echo e($sba->lesson?->name ?? 'N/A'); ?></td>
        <td><?php echo e($sba->isPaid ? 'Paid' : 'Free'); ?></td>
        <td>
            <a href="<?php echo e(route('admin.sbas.status', $sba->id)); ?>"
               class="btn btn-<?php echo e(\App\Enums\Status::from($sba->status)->message()); ?>">
                <?php echo e(\App\Enums\Status::from($sba->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info" href="<?php echo e(route('admin.sbas.show', $sba->id)); ?>" title="View & Manage Questions">
                <i class="fa fa-eye"></i>
            </a>
            <a class="btn btn-primary" href="<?php echo e(route('admin.sbas.edit', $sba->id)); ?>" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" onclick="showDeleteConfirmation(event)"
               href="<?php echo e(route('admin.sbas.destroy', $sba->id)); ?>" title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/sba_rows.blade.php ENDPATH**/ ?>