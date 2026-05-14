<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Create</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="<?php echo e(route('admin.sbas.index')); ?>"> Back</a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?php echo e(route('admin.sbas.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            Courses <span class="required text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>" <?php echo e((collect(old('course_ids'))->contains($course->id)) ? 'selected' : ''); ?>>
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

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="isPaid">IsPaid <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input type="checkbox" name="isPaid" class="form-check-input" id="isPaid" value="1">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create SBA</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

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
                moduleType: 'sba',
                allowMultipleCourses: true
            });

            const oldCourseIds = <?php echo e(json_encode(old('course_ids', []))); ?>;
            const oldChapterId = <?php echo e(old('chapter_id', 'null')); ?>;
            const oldLessonId = <?php echo e(old('lesson_id', 'null')); ?>;

            if (oldCourseIds && oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/sba/create.blade.php ENDPATH**/ ?>