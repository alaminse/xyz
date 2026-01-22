<?php $__env->startSection('title', 'Chapters'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Chapters <small>
            </small>
        </h2>

        <ul class="nav navbar-right panel_toolbox">
          <li><button class="btn btn-sm btn-success " type="button" data-toggle="modal" data-target=".bs-chapter-modal-lg" ><i class="fa fa-plus"></i> Add Chapter</button>
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
                                <th>Chapter</th>
                                <th>Lesson</th>
                                <th>Status</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(++$key); ?></td>
                                <td><?php echo e($chapter->name); ?></td>
                                <td>
                                    <?php if($chapter->lessons !== null && count($chapter->lessons) > 0): ?>
                                        <?php $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-success"><?php echo e($lesson->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        ---
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.chapters.status', $chapter->id )); ?>" class="btn btn-<?php echo e($chapter->status == 1 ? 'success' : ($chapter->status == 3 ? 'danger' : 'warning')); ?> btn-sm  <?php echo e($chapter->status == 3 ? 'disabled' : ''); ?>"><?php echo e(\App\Enums\Status::from($chapter->status)->title()); ?></a>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="<?php echo e(route('admin.chapters.edit', $chapter->id)); ?>"> <i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger <?php echo e($chapter->status == 3 ? 'disabled' : ''); ?>" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.chapters.destroy', $chapter->id)); ?>"><i class="fa fa-trash"></i></a>
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


<div class="modal fade bs-chapter-modal-lg" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-sm">

            
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-book mr-1"></i> Chapter Information
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            
            <form action="<?php echo e(route('admin.chapters.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-body px-4">

                    
                    <div class="form-group">
                        <label class="font-weight-semibold">
                            Chapter Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter chapter name"
                            value="<?php echo e(old('name')); ?>"
                            required
                        >
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-semibold">Lesson</label>
                                <select name="lesson_ids[]" class="form-control js-example-basic-multiple" multiple>
                                    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lesson->id); ?>"><?php echo e($lesson->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-semibold">Course</label>
                                <select name="course_ids[]" class="form-control js-example-basic-multiple" multiple>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <hr class="my-4">

                    
                    <div class="form-group">
                        <label class="font-weight-bold mb-3 d-block">
                            <i class="fa fa-cogs mr-1"></i> Available Features
                        </label>

                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <div class="row">

                                    <?php
                                        $features = [
                                            'sba'             => 'SBA',
                                            'note'            => 'Note',
                                            'mcq'             => 'MCQ',
                                            'flush'           => 'Flush',
                                            'videos'          => 'Videos',
                                            'ospe'            => 'OSPE',
                                            'written'         => 'Written',
                                            'mock_viva'       => 'Mock Viva',
                                            'self_assessment' => 'Self Assessment',
                                        ];
                                    ?>

                                    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="<?php echo e($key); ?>"
                                                    name="<?php echo e($key); ?>"
                                                    value="1"
                                                >
                                                <label class="custom-control-label" for="<?php echo e($key); ?>">
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

                
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save mr-1"></i> Create Chapter
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#createModal').on('shown.bs.modal', function () {
                $('.js-example-basic-multiple').select2();
            });
        });
    </script>

    <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/chapter/index.blade.php ENDPATH**/ ?>