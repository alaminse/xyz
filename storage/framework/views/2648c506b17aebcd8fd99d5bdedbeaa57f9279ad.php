
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row row-cols-2 row-cols-md-4">
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-user"></i> Total Users</h2>
                <div class="count green" style="font-size: 44px"><?php echo e($data['total_users']); ?></div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-user"></i> New User</h2>
                <div class="count green" style="font-size: 44px"><?php echo e($data['new_users']); ?></div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-play"></i> Course</h2>
                <div class="count green" style="font-size: 44px"><?php echo e($data['assessments']); ?></div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-tasks"></i> Assessment</h2>
                <div class="count green" style="font-size: 44px"><?php echo e($data['courses']); ?></div>
            </div>
          </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-box table-responsive">
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="25%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(++$key); ?></td>
                            <td><?php echo e($user->name); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                            <?php if(!empty($user->getRoleNames())): ?>
                                <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="badge badge-success"><?php echo e($v); ?></label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <a class="btn btn-info" href="<?php echo e(route('admin.users.show', $user->id)); ?>"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-primary" href="<?php echo e(route('admin.users.edit', $user->id)); ?>"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-danger" type="submit" onclick="showDeleteConfirmation(event)"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->startPush('scripts'); ?>
    <!-- Datatables -->
    <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/index.blade.php ENDPATH**/ ?>