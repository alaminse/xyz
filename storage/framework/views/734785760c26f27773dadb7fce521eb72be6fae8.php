<?php $__env->startSection('title', 'Access Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                Access Logs
                <small><?php echo e($securePdf->title); ?></small>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="<?php echo e(route('admin.secure-pdfs.show', $securePdf->id)); ?>"
                       class="btn btn-sm btn-default">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $badges = [
                                'viewed'              => 'info',
                                'streamed'            => 'primary',
                                'token_generated'     => 'default',
                                'suspicious_activity' => 'danger',
                            ];
                            $badge = $badges[$log->action] ?? 'default';
                        ?>
                        <tr class="<?php echo e($log->action === 'suspicious_activity' ? 'danger' : ''); ?>">
                            <td><?php echo e($logs->firstItem() + $i); ?></td>
                            <td>
                                <strong><?php echo e($log->user?->name); ?></strong><br>
                                <small class="text-muted"><?php echo e($log->user?->email); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($badge); ?>">
                                    <?php echo e($log->action); ?>

                                </span>
                            </td>
                            <td><code><?php echo e($log->ip_address); ?></code></td>
                            <td>
                                <small title="<?php echo e($log->user_agent); ?>">
                                    <?php echo e(Str::limit($log->user_agent, 45)); ?>

                                </small>
                            </td>
                            <td><?php echo e($log->accessed_at->format('d M Y, h:i A')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No access logs found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($logs->links()); ?>


        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/secure-pdfs/access-logs.blade.php ENDPATH**/ ?>