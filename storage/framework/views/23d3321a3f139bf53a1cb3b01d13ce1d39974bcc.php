<?php $__env->startSection('title', 'Written Assessment'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Written Assessment <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-warning text-white" href="<?php echo e(route('admin.writtenassessments.index')); ?>"> Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?php echo e(route('admin.writtenassessments.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">Courses <span class="required text-danger">*</span></label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e((collect(old('course_ids'))->contains($course->id)) ? 'selected' : ''); ?>>
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="chapterSelect" class="form-label">Chapter <span
                                    class="required text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Chapter</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="lessonSelect" class="form-label">Lesson <span
                                    class="required text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Lesson</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="isPaid" class="form-label">IsPaid <span
                                    class="required text-danger">*</span></label>
                            <br>
                            <input type="checkbox" name="isPaid" id="isPaid" class="mt-2" value="1"
                                <?php echo e(old('isPaid') == 1 ? 'checked' : ''); ?>>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="question" class="form-label">Question <span
                                    class="required text-danger">*</span></label>
                            <input class="form-control" id="question" name="question" placeholder="Enter Question"
                                value="<?php echo e(old('question')); ?>" />
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="answer" class="form-label">Answer <span
                                    class="required text-danger">*</span></label>
                            <textarea class="form-control summernote" name="answer" cols="30" rows="5" required><?php echo old('answer'); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 mt-3">
                            <button type='submit' class="btn btn-primary">Create</button>
                            <button type='reset' class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>
        <script>
            $(document).ready(function() {
                // Initialize the combined form handler
                const formHandler = new FormHandler({
                    // Dependent dropdown settings
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'written',
                    allowMultipleCourses: true,

                    // Summernote settings
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    summernoteUploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>"
                });

                // Restore old values on validation error
                const oldCourseIds = <?php echo json_encode(old('course_ids', []), 512) ?>;
                const oldChapterId = '<?php echo e(old("chapter_id")); ?>';
                const oldLessonId = '<?php echo e(old("lesson_id")); ?>';

                if (oldCourseIds && oldCourseIds.length > 0) {
                    formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/written-assessment/create.blade.php ENDPATH**/ ?>