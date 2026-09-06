<?php $__currentLoopData = $ospestations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ospe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($ospe->getChapterName()); ?></td>
        <td><?php echo e($ospe->getLessonName()); ?></td>
        <td>
            <?php if($ospe->questions->first() && $ospe->questions->first()->image): ?>
                <img src="<?php echo e(asset('uploads/'.$ospe->questions->first()->image)); ?>" alt="OSPE Image" width="100">
            <?php else: ?>
                <span>No image</span>
            <?php endif; ?>
        </td>
        <td>
            <a href="#" class="btn btn-<?php echo e(\App\Enums\IsPaid::from($ospe->isPaid)->message()); ?> btn-sm">
                <?php echo e(\Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($ospe->isPaid)->title(), 100, '....')); ?>

            </a>
        </td>

        <td>
            <a href="<?php echo e(route('admin.ospestations.status', $ospe->id)); ?>" class="btn btn-sm btn-<?php echo e(\App\Enums\Status::from($ospe->status)->message()); ?>">
                <?php echo e(\App\Enums\Status::from($ospe->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-sm btn-info" href="<?php echo e(route('admin.ospestations.show', $ospe->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.ospestations.edit', $ospe->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-sm btn-danger <?php echo e($ospe->status == 3 ? 'disabled' : ''); ?>" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.ospestations.destroy', $ospe->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/ospe_station_row.blade.php ENDPATH**/ ?>