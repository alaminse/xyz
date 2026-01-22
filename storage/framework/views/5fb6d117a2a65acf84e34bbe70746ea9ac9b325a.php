<?php $__env->startSection('title', 'MCQ Edit'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <style>
        .x_panel {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .x_title {
            border-bottom: 2px solid #e8e8e8;
            padding: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #2a3f54;
            margin-bottom: 8px;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            min-height: 40px;
        }
        .btn-group-action {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .info-badge {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin-bottom: 20px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-edit"></i> Edit MCQ</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="<?php echo e(route('admin.mcqs.index')); ?>">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="info-badge">
                <strong><i class="fa fa-info-circle"></i> Note:</strong>
                This form updates only the MCQ metadata. To manage questions, use the "Manage Questions" button below.
            </div>

            <form action="<?php echo e(route('admin.mcqs.update', $mcq->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa fa-graduation-cap"></i> Courses
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"
                                    <?php echo e($mcq->courses->contains($course->id) ? 'selected' : ''); ?>>
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
                            <i class="fa fa-folder"></i> Chapter
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                data-old-value="<?php echo e(old('chapter_id', $mcq->chapter_id)); ?>">
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
                            <i class="fa fa-book"></i> Lesson
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="lesson_id" id="lessonSelect" class="select2 form-control" required
                                data-old-value="<?php echo e(old('lesson_id', $mcq->lesson_id)); ?>">
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

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="isPaid" class="form-label">
                            <i class="fa fa-lock"></i> Payment Status
                        </label>
                        <div class="mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="isPaid" id="isPaid" value="1"
                                    <?php echo e(old('isPaid', $mcq->isPaid) == 1 ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="isPaid">
                                    This is paid content
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa fa-toggle-on"></i> Status
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="status" class="select2 form-control">
                            <option value="1" <?php echo e($mcq->status == 1 ? 'selected' : ''); ?>>
                                <i class="fa fa-check-circle"></i> Active
                            </option>
                            <option value="2" <?php echo e($mcq->status == 2 ? 'selected' : ''); ?>>
                                <i class="fa fa-times-circle"></i> Inactive
                            </option>
                            <option value="3" <?php echo e($mcq->status == 3 ? 'selected' : ''); ?>>
                                <i class="fa fa-trash"></i> Delete
                            </option>
                        </select>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-12 btn-group-action">
                        <button type='submit' class="btn btn-success">
                            <i class="fa fa-save"></i> Update MCQ
                        </button>
                        <button type='reset' class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                        <a href="<?php echo e(route('admin.mcqs.show', $mcq->id)); ?>" class="btn btn-primary">
                            <i class="fa fa-list"></i> Manage Questions
                        </a>
                        <a href="<?php echo e(route('admin.mcqs.index')); ?>" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'mcq',
                allowMultipleCourses: true
            });

            const existingCourseIds = <?php echo json_encode($mcq->courses->pluck('id')->toArray(), 15, 512) ?>;
            const existingChapterId = <?php echo e($mcq->chapter_id); ?>;
            const existingLessonId = <?php echo e($mcq->lesson_id); ?>;

            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/mcq/edit.blade.php ENDPATH**/ ?>