<?php $__env->startSection('title', 'OSPE Create'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>OSPE Create</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-white" href="<?php echo e(route('admin.ospestations.index')); ?>">Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="<?php echo e(route('admin.ospestations.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">
                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">
                                Courses <span class="required text-danger">*</span>
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

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="chapterSelect" class="form-label">
                                Chapter <span class="required text-danger">*</span>
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

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="lessonSelect" class="form-label">
                                Lesson <span class="required text-danger">*</span>
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

                        
                        <div class="col-sm-12 mb-3">
                            <label for="note_id" class="form-label">Related Notes (Optional)</label>
                            <select name="note_id" id="note_id" class="form-control select2"
                                    data-old-value="<?php echo e(old('note_id')); ?>">
                                <option value="">Select Note</option>
                            </select>
                            <?php $__errorArgs = ['note_id'];
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

                        <!-- Status -->
                        <div class="col-md-4 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control select2">
                                <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>Pending</option>
                                <option value="1" <?php echo e(old('status') == '1' ? 'selected' : ''); ?>>Active</option>
                                <option value="2" <?php echo e(old('status') == '2' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>

                        <!-- IsPaid -->
                        <div class="col-md-4 mb-3 mt-4">
                            <label>Is Paid</label>
                            <input type="checkbox" name="isPaid" value="1" <?php echo e(old('isPaid', 1) ? 'checked' : ''); ?>>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>
    <script src="<?php echo e(asset('backend/js/notes-handler.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize the combined form handler
            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'ospe',
                allowMultipleCourses: true,
                summernoteSelector: '.summernote',
                summernoteHeight: 300,
                csrfToken: '<?php echo e(csrf_token()); ?>',
                summernoteUploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>"
            });

            // Initialize Notes Handler
            const notesHandler = new NotesHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                noteSelect: '#note_id',
                notesUrl: '/admin/mcqs/get-notes',
                autoLoad: true
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

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/ospe-station/create.blade.php ENDPATH**/ ?>