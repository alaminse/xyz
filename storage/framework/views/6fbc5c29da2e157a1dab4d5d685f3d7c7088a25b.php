<?php $__env->startSection('title', 'Edit SBA'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit SBA</h2>
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
            <form action="<?php echo e(route('admin.sbas.update', $sba->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">Courses <span class="required text-danger">*</span></label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"
                                    <?php echo e($sba->courses->contains($course->id) ? 'selected' : ''); ?>>
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

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="chapterSelect" class="form-label">Chapter <span class="required text-danger">*</span></label>
                        <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                data-old-value="<?php echo e(old('chapter_id', $sba->chapter_id)); ?>">
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

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="lessonSelect" class="form-label">Lesson <span class="required text-danger">*</span></label>
                        <select name="lesson_id" id="lessonSelect" class="select2 form-control" required
                                data-old-value="<?php echo e(old('lesson_id', $sba->lesson_id)); ?>">
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

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="isPaid" class="form-label">IsPaid</label>
                        <br>
                        <input class="mt-2" type="checkbox" name="isPaid" id="isPaid" value="1"
                            <?php echo e(old('isPaid', $sba->isPaid) == 1 ? 'checked' : ''); ?>>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Status <span class="required text-danger">*</span></label>
                        <select name="status" class="select2 form-control">
                            <option value="1" <?php echo e($sba->status == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="2" <?php echo e($sba->status == 2 ? 'selected' : ''); ?>>Inactive</option>
                            <option value="3" <?php echo e($sba->status == 3 ? 'selected' : ''); ?>>Delete</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 mt-3">
                        <button type='submit' class="btn btn-warning">Update</button>
                        <button type='reset' class="btn btn-success">Reset</button>
                        <a href="<?php echo e(route('admin.sbas.show', $sba->id)); ?>" class="btn btn-info">Manage Questions</a>
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
                moduleType: 'sba',
                allowMultipleCourses: true
            });

            const existingCourseIds = <?php echo json_encode($sba->courses->pluck('id')->toArray(), 15, 512) ?>;
            const existingChapterId = <?php echo e($sba->chapter_id); ?>;
            const existingLessonId = <?php echo e($sba->lesson_id); ?>;
            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/sba/edit.blade.php ENDPATH**/ ?>