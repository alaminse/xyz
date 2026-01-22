
<?php $__env->startSection('title', 'Lessons'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Lessons <small>
            </small>
        </h2>

        <ul class="nav navbar-right panel_toolbox">
          <li><button class="btn btn-sm btn-success " type="button" data-toggle="modal" data-target=".bs-lesson-modal-lg" ><i class="fa fa-plus"></i> Add Lesson</button>
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
                                <th>Status</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td><?php echo e($lesson->name); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.lessons.status', $lesson->id )); ?>" class="btn btn-<?php echo e($lesson->status == 1 ? 'success' : ($lesson->status == 3 ? 'danger' : 'warning')); ?> btn-sm  <?php echo e($lesson->status == 3 ? 'disabled' : ''); ?>"><?php echo e(\App\Enums\Status::from($lesson->status)->title()); ?></a>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo e(route('admin.lessons.edit', $lesson->id)); ?>"> <i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger <?php echo e($lesson->status == 3 ? 'disabled' : ''); ?>" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.lessons.destroy', $lesson->id)); ?>"><i class="fa fa-trash"></i></a>
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


<div class="modal fade bs-lesson-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="createModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Lesson Information</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="<?php echo e(route('admin.lessons.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <!-- BODY -->
                <div class="modal-body">
                    <div class="row">

                        <!-- Lesson Name -->
                        <div class="col-12 mb-3">
                            <label class="font-weight-bold">
                                Lesson Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   placeholder="Enter Lesson Name"
                                   value="<?php echo e(old('name')); ?>"
                                   required>
                        </div>

                        <!-- Lesson Has -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h6 class="font-weight-bold mb-2">Lesson Has</h6>

                                    <div class="row">
                                        <?php $__currentLoopData = [
                                            'sba' => 'SBA',
                                            'note' => 'Note',
                                            'mcq' => 'MCQ',
                                            'flush' => 'Flush',
                                            'videos' => 'Video',
                                            'ospe' => 'OSPE',
                                            'written' => 'Written Assessment',
                                            'mock_viva' => 'Mock Viva',
                                            'self_assessment' => 'Self Assessment'
                                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-4 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="<?php echo e($key); ?>"
                                                           value="1"
                                                           <?php echo e(old($key, 1) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label">
                                                        <?php echo e($label); ?>

                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Create Lesson
                    </button>
                </div>

            </form>

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

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lesson/index.blade.php ENDPATH**/ ?>