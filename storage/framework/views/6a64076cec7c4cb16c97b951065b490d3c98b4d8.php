<?php $__env->startSection('title', 'Notices'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Floating Notices</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-light" href="<?php echo e(route('admin.notices.create')); ?>">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>Active Window</th>
                        <th>Status</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $notices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e(Str::limit(strip_tags($notice->message), 80)); ?></td>
                            <td>
                                <span class="badge
                                    <?php if($notice->type == 'urgent'): ?> badge-danger
                                    <?php elseif($notice->type == 'warning'): ?> badge-warning
                                    <?php else: ?> badge-info <?php endif; ?>">
                                    <?php echo e(ucfirst($notice->type)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($notice->start_at || $notice->end_at): ?>
                                    <?php echo e($notice->start_at?->format('d M Y') ?? 'Always'); ?>

                                    &ndash;
                                    <?php echo e($notice->end_at?->format('d M Y') ?? 'No end'); ?>

                                <?php else: ?>
                                    <span class="text-muted">Always active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.notices.status', $notice->id)); ?>"
                                   onclick="event.preventDefault(); document.getElementById('status-form-<?php echo e($notice->id); ?>').submit();">
                                    <?php if($notice->is_active): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </a>
                                <form id="status-form-<?php echo e($notice->id); ?>" action="<?php echo e(route('admin.notices.status', $notice->id)); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                </form>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.notices.edit', $notice->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="<?php echo e(route('admin.notices.destroy', $notice->id)); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="event.preventDefault();
                                            if (confirm('Delete this notice?')) {
                                                document.getElementById('delete-form-<?php echo e($notice->id); ?>').submit();
                                            }">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form id="delete-form-<?php echo e($notice->id); ?>" action="<?php echo e(route('admin.notices.destroy', $notice->id)); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">No notices yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/notice/index.blade.php ENDPATH**/ ?>