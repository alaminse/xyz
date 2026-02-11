
<?php $__env->startSection('title', 'User Lists'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Users Management </h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="btn btn-sm btn-success text-white" href="<?php echo e(route('admin.users.create')); ?>"><i class="fa fa-plus"></i> Add New</a>
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
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joining Date</th>
                                <th>Roles</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($user->created_at)->format('M d, Y')); ?></td>
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

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/users/index.blade.php ENDPATH**/ ?>