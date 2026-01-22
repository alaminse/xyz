<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $enrolle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td>
            <img class="image-preview img-fluid" src="<?php echo e(getImageUrl($enrolle->course?->banner)); ?>"
                alt="<?php echo e($enrolle->course?->title); ?>" style="height: 40px; width: 40px;">
        </td>
        <td>
            <div><strong><?php echo e($enrolle->course?->name); ?></strong></div>
            <?php echo e($enrolle->course?->detail?->duration); ?> <?php echo e($enrolle->course?->detail?->type); ?>

        </td>
        <td><?php echo e($enrolle->user?->name); ?></td>
        <td><?php echo e($enrolle->user?->email); ?></td>
        <td><?php echo e(\Carbon\Carbon::parse($enrolle->start_date)->format('M d, Y')); ?></td>
        <td><?php echo e(\Carbon\Carbon::parse($enrolle->end_date)->format('M d, Y')); ?></td>
        <td><?php echo e($enrolle->price); ?></td>
        <td><?php echo e($enrolle->sell_price); ?></td>
        <td>
            <button type="button"
                class="btn btn-<?php echo e(\App\Enums\Status::from($enrolle->status)->message()); ?> btn-sm  <?php echo e($enrolle->status == 3 ? 'disabled' : ''); ?>"
                data-toggle="modal" data-target="#statusUpdate" data-id="<?php echo e($enrolle->id); ?>"
                data-status="<?php echo e($enrolle->status); ?>">
                <?php echo e(\App\Enums\Status::from($enrolle->status)->title()); ?></button>
        </td>
        <td>
            <a class="btn btn-info btn-sm" target="__blank"
                href="<?php echo e(route('courses.invoice', $enrolle->slug)); ?>">Invoice</a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/enrolle/table.blade.php ENDPATH**/ ?>