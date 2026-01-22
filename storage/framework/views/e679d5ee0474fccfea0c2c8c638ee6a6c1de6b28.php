<?php $__env->startSection('title', 'Courses'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>"
        rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Courses <small>
                    </small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-light" href="<?php echo e(route('admin.courses.create')); ?>"><i
                                class="fa fa-plus"></i> Add Course</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Parent Name</th>
                                        <th>Active</th>
                                        <th>Status</th>
                                        <th width="25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(++$key); ?></td>
                                            <td><?php echo e($course->name); ?></td>
                                            <td><?php echo e($course->getCourseName()); ?></td>
                                            <td>
                                                <?php
                                                    $detail = json_decode($course->detail);
                                                    $labels = [
                                                        'sba' => 'SBA',
                                                        'note' => 'Note',
                                                        'mcq' => 'MCQ',
                                                        'flush' => 'Flush',
                                                        'written' => 'Written',
                                                        'videos' => 'Video',
                                                        'verbal' => 'Verbal',
                                                        'ospe' => 'OSPE',
                                                        'self_assessment' => 'Self Assessment',
                                                    ];
                                                ?>

                                                <?php if($detail): ?>
                                                    <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(data_get($detail, $key) == 1): ?>
                                                            <span
                                                                class="badge bg-success me-1 text-white"><?php echo e($label); ?></span>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('admin.courses.status', $course->id)); ?>"
                                                    class="btn btn-<?php echo e($course->status == 1 ? 'success' : ($course->status == 3 ? 'danger' : 'warning')); ?> btn-sm  <?php echo e($course->status == 3 ? 'disabled' : ''); ?>"><?php echo e(\App\Enums\Status::from($course->status)->title()); ?></a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="<?php echo e(route('admin.courses.edit', $course->id)); ?>"> <i
                                                        class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger <?php echo e($course->status == 3 ? 'disabled' : ''); ?>"
                                                    onclick="showDeleteConfirmation(event)"
                                                    href="<?php echo e(route('admin.courses.destroy', $course->id)); ?>"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/course/index.blade.php ENDPATH**/ ?>