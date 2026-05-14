<?php $__env->startSection('title', 'PDF Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-10 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>PDF Details</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="<?php echo e(route('admin.secure-pdfs.edit', $securePdf->id)); ?>"
                       class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.secure-pdfs.index')); ?>"
                       class="btn btn-sm btn-default">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">Title</th>
                            <td><?php echo e($securePdf->title); ?></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td>
                                <i class="fa fa-file-pdf-o text-danger"></i>
                                <?php echo e($securePdf->original_name); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo e($securePdf->category ?? '—'); ?></td>
                        </tr>
                        <tr>
                            <th>Pages</th>
                            <td><?php echo e($securePdf->total_pages); ?></td>
                        </tr>
                        <tr>
                            <th>Size</th>
                            <td><?php echo e($securePdf->file_size_formatted); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo $securePdf->status_badge; ?></td>
                        </tr>
                        <tr>
                            <th>Allow Print</th>
                            <td>
                                <?php echo e($securePdf->allow_print ? 'Yes' : 'No (Secure)'); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>Total Views</th>
                            <td><strong><?php echo e($securePdf->access_logs_count); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Uploaded By</th>
                            <td><?php echo e($securePdf->createdBy?->name); ?></td>
                        </tr>
                        <tr>
                            <th>Uploaded At</th>
                            <td><?php echo e($securePdf->created_at->format('d M Y, h:i A')); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <div class="well"
                         style="background:#f9fff9; border-color:#5cb85c;">
                        <h5>
                            <i class="fa fa-shield text-success"></i>
                            Active Security Layers
                        </h5>
                        <ul class="list-unstyled mb-0" style="line-height:2.2;">
                            <li><i class="fa fa-check text-success"></i> File stored outside public folder</li>
                            <li><i class="fa fa-check text-success"></i> File path encrypted in DB</li>
                            <li><i class="fa fa-check text-success"></i> IP-locked 30-min token</li>
                            <li><i class="fa fa-check text-success"></i> Canvas render — no PDF download</li>
                            <li><i class="fa fa-check text-success"></i> IDM & download managers blocked</li>
                            <li><i class="fa fa-check text-success"></i> DevTools detection active</li>
                            <li><i class="fa fa-check text-success"></i> Watermark on every page</li>
                            <li><i class="fa fa-check text-success"></i> Session binding active</li>
                        </ul>
                    </div>
                </div>
            </div>

            <h4 class="mt-3">
                Recent Access Logs
                <small>
                    <a href="<?php echo e(route('admin.secure-pdfs.access-logs', $securePdf->id)); ?>">
                        View All
                    </a>
                </small>
            </h4>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>IP</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                        <td><?php echo e($i + 1); ?></td>
                        <td>
                            <?php echo e($log->user?->name); ?><br>
                            <small class="text-muted"><?php echo e($log->user?->email); ?></small>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($badge); ?>">
                                <?php echo e($log->action); ?>

                            </span>
                        </td>
                        <td><code><?php echo e($log->ip_address); ?></code></td>
                        <td><?php echo e($log->accessed_at->format('d M Y, h:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No logs yet.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/secure-pdfs/show.blade.php ENDPATH**/ ?>