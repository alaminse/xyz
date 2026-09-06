<?php $__env->startSection('title', 'Edit Note'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Note</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="<?php echo e(route('admin.notes.index')); ?>"> Back</a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="<?php echo e(route('admin.notes.update', $note->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">Courses <span class="required text-danger">*</span></label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"
                                    <?php echo e($note->courses->contains($course->id) ? 'selected' : ''); ?>>
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
                                data-old-value="<?php echo e(old('chapter_id', $note->chapter_id)); ?>">
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
                        <label for="lessonSelect" class="form-label">Lesson</label>
                        <select name="lesson_id" id="lessonSelect" class="select2 form-control"
                                data-old-value="<?php echo e(old('lesson_id', $note->lesson_id)); ?>">
                            <option value="">Select Lesson (optional)</option>
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
                            <?php echo e(old('isPaid', $note->isPaid) == 1 ? 'checked' : ''); ?>>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Status <span class="required text-danger">*</span></label>
                        <select name="status" class="select2 form-control">
                            <option value="1" <?php echo e($note->status == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="2" <?php echo e($note->status == 2 ? 'selected' : ''); ?>>Inactive</option>
                            <option value="3" <?php echo e($note->status == 3 ? 'selected' : ''); ?>>Delete</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 mt-3">
                        <button type='submit' class="btn btn-warning">Update</button>
                        <button type='reset' class="btn btn-success">Reset</button>
                        <a href="<?php echo e(route('admin.notes.show', $note->id)); ?>" class="btn btn-info">Manage Sections</a>
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
                moduleType: 'note',
                allowMultipleCourses: true
            });

            const existingCourseIds = <?php echo json_encode($note->courses->pluck('id')->toArray(), 15, 512) ?>;
            const existingChapterId = <?php echo e($note->chapter_id); ?>;
            const existingLessonId = <?php echo e($note->lesson_id ?? 'null'); ?>;
            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/note/edit.blade.php ENDPATH**/ ?>