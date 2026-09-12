

<?php $__env->startSection('title', 'Update Role'); ?>

<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Update Role <small><a class="btn btn-warning" href="<?php echo e(route('admin.roles.index')); ?>"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?php echo e(route('admin.roles.update', $role->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <span class="section">Role Info</span>
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role Name<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="name" value="<?php echo e($role->name); ?>" required="required"/>
                    </div>
                </div>
                <div class="field item form-group mt-3">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Permission<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <div class="row">
                            <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-sm-12 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permission[]" value="<?php echo e($value->id); ?>" <?php echo e(in_array($value->id, $rolePermissions) ? 'checked' : ''); ?> > <?php echo e($value->name); ?>

                                    </label>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type='submit' class="btn btn-primary">Update</button>
                            <button type='reset' class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/roles/edit.blade.php ENDPATH**/ ?>