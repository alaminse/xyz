<?php $__env->startSection('title', 'Assessment Create'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <style>

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px 20px;
            cursor: pointer;
        }

        .card-body {
            padding: 25px;
            background: #f8f9fa;
        }

        .form-control,
        .select2-container .select2-selection--single {
            border-radius: 6px;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
        }

        .form-check-input:checked+.form-check-label {
            color: #667eea;
            font-weight: 600;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .x_panel {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 14px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-file-text-o"></i> Create New Assessment</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.assessments.index')); ?>">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?php echo e(route('admin.assessments.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    
                    <div class="section-title">
                        <i class="fa fa-info-circle"></i> Assessment Details
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Courses <span class="text-danger">*</span>
                            </label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required
                                data-old-value='<?php echo json_encode(old('course_ids'), 15, 512) ?>'>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e(collect(old('course_ids'))->contains($course->id) ? 'selected' : ''); ?>>
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['course_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="chapterSelect" class="form-label font-weight-bold">
                                Chapter <span class="text-danger">*</span>
                            </label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required
                                data-old-value="<?php echo e(old('chapter_id')); ?>">
                                <option value="" disabled selected>Select Chapter</option>
                            </select>
                            <?php $__errorArgs = ['chapter_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="lessonSelect" class="form-label font-weight-bold">
                                Lesson <span class="text-danger">*</span>
                            </label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required
                                data-old-value="<?php echo e(old('lesson_id')); ?>">
                                <option value="" disabled selected>Select Lesson</option>
                            </select>
                            <?php $__errorArgs = ['lesson_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Assessment Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter assessment name"
                                value="<?php echo e(old('name')); ?>">
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Time <span class="text-danger">(Minutes)</span>
                            </label>
                            <input type="number" name="time" class="form-control" placeholder="60"
                                value="<?php echo e(old('time', 60)); ?>">
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Status</label>
                            <select name="status" class="select2 form-control">
                                <option value="1" <?php echo e(old('status') == 1 ? 'selected' : ''); ?> selected>Active</option>
                                <option value="2" <?php echo e(old('status') == 2 ? 'selected' : ''); ?>>Inactive</option>
                                <option value="3" <?php echo e(old('status') == 3 ? 'selected' : ''); ?>>Delete</option>
                            </select>
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Start Time</label>
                            <input type="datetime-local" name="start_date_time" class="form-control"
                                value="<?php echo e(old('start_date_time')); ?>">
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">End Time</label>
                            <input type="datetime-local" name="end_date_time" class="form-control"
                                value="<?php echo e(old('end_date_time')); ?>">
                        </div>

                        
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Is Paid Content?</label>
                            <div class="mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="isPaid" id="isPaid"
                                        value="1" <?php echo e(old('isPaid', 1) == 1 ? 'checked' : ''); ?>>
                                    <label class="custom-control-label" for="isPaid">Yes, this is paid content</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-12">
                            <hr class="my-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Create Assessment
                                </button>
                                <a href="<?php echo e(route('admin.assessments.index')); ?>"
                                    class="btn btn-secondary ml-2">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>

        <script>
            $(document).ready(function() {
                // Initialize the combined form handler
                const formHandler = new FormHandler({
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'self_assessment',
                    allowMultipleCourses: true,
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    summernoteUploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>"
                });

                // Restore old values on validation error
                const oldCourseIds = $('#courseSelect').data('old-value');
                const oldChapterId = $('#chapterSelect').data('old-value');
                const oldLessonId = $('#lessonSelect').data('old-value');

                if (oldCourseIds && oldCourseIds.length > 0) {
                    formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                    // Load notes after initialization
                    setTimeout(function() {
                        notesHandler.loadNotes();
                    }, 500);
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/assessment/create.blade.php ENDPATH**/ ?>