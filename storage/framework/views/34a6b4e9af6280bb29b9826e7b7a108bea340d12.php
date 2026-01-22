<?php $__env->startSection('title', 'Flash Card Create'); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section h5 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .required-indicator {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>🎴 Create Flash Card</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <a class="btn btn-warning text-white" href="<?php echo e(route('admin.flashs.index')); ?>">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <form action="<?php echo e(route('admin.flashs.store')); ?>" method="POST" id="flashCardForm">
                            <?php echo csrf_field(); ?>

                            <div class="form-section">
                                <h5><i class="fa fa-book text-primary"></i> Course & Content Selection</h5>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label class="form-label">
                                            Courses <span class="required text-danger">*</span>
                                        </label>
                                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple
                                            required>
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

                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label for="chapterSelect" class="form-label">
                                            Chapter <span class="required text-danger">*</span>
                                        </label>
                                        <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
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

                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label for="lessonSelect" class="form-label">
                                            Lesson <span class="required text-danger">*</span>
                                        </label>
                                        <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
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
                                </div>
                            </div>

                            <div class="form-section">
                                <h5><i class="fa fa-cog text-info"></i> Settings</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="isPaid" class="d-block">Payment Type</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="isPaid" class="custom-control-input"
                                                    id="isPaid" value="1" <?php echo e(old('isPaid') ? 'checked' : ''); ?>>
                                                <label class="custom-control-label" for="isPaid">
                                                    This is a <strong>Paid</strong> Flash Card
                                                </label>
                                            </div>
                                            <small class="text-muted">Leave unchecked for free access</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                After creating the flash card, you'll be able to add multiple questions and answers.
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Create Flash Card
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg">
                                    <i class="fa fa-refresh"></i> Reset Form
                                </button>
                                <a href="<?php echo e(route('admin.flashs.index')); ?>" class="btn btn-outline-secondary btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true
            });

            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'flush',
                allowMultipleCourses: true
            });

            // Handle old input for validation errors
            const oldCourseIds = <?php echo e(json_encode(old('course_ids', []))); ?>;
            const oldChapterId = <?php echo e(old('chapter_id', 'null')); ?>;
            const oldLessonId = <?php echo e(old('lesson_id', 'null')); ?>;

            if (oldCourseIds && oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                setTimeout(function() {
                    notesHandler.loadNotes();
                }, 500);
            }

            // Form validation
            $('#flashCardForm').on('submit', function(e) {
                const courseIds = $('#courseSelect').val();
                const chapterId = $('#chapterSelect').val();
                const lessonId = $('#lessonSelect').val();

                if (!courseIds || courseIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one course');
                    return false;
                }

                if (!chapterId) {
                    e.preventDefault();
                    alert('Please select a chapter');
                    return false;
                }

                if (!lessonId) {
                    e.preventDefault();
                    alert('Please select a lesson');
                    return false;
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/flash/create.blade.php ENDPATH**/ ?>