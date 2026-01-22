<?php $__env->startSection('title', 'Mock Viva Edit'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Mock Viva</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-white" href="<?php echo e(route('admin.mockvivas.index')); ?>">Back</a></li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="<?php echo e(route('admin.mockvivas.update', $mock->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label class="form-label">Courses <span class="required text-danger">*</span></label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e((collect(old('course_ids', $mock->courses->pluck('id')))->contains($course->id)) ? 'selected' : ''); ?>>
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="chapterSelect" class="form-label">Chapter <span
                                    class="required text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Chapter</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-2">
                            <label for="lessonSelect" class="form-label">Lesson <span
                                    class="required text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Lesson</option>
                            </select>
                        </div>


                        <!-- Status -->
                        <div class="col-md-4 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control select2">
                                <option value="0" <?php echo e(old('status', $mock->status ?? '') == '0' ? 'selected' : ''); ?>>
                                    Pending</option>
                                <option value="1" <?php echo e(old('status', $mock->status ?? '') == '1' ? 'selected' : ''); ?>>
                                    Active</option>
                                <option value="2" <?php echo e(old('status', $mock->status ?? '') == '2' ? 'selected' : ''); ?>>
                                    Inactive</option>
                            </select>
                        </div>

                        <!-- IsPaid -->
                        <div class="col-md-4 mb-3 mt-4">
                            <label>Is Paid</label>
                            <input type="checkbox" name="isPaid" value="1"
                                <?php echo e(old('isPaid', $mock->isPaid ?? 0) ? 'checked' : ''); ?>>
                        </div>

                        <div class="col-md-4 mb-3 mt-4">
                            <button type="button" id="add_group_button" class="btn btn-primary btn-sm mt-3 float-right">Add
                                Question Group</button>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <div id="question_groups_container">
                            <?php
                                // Prepare old or existing questions array
                                $questionGroups = old(
                                    'questions',
                                    $mock->questions
                                        ->map(function ($q) {
                                            return ['questions' => json_decode($q->questions, true)];
                                        })
                                        ->toArray() ?? [],
                                );
                            ?>

                            <?php $__currentLoopData = $questionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card mb-3 question-group" data-group-index="<?php echo e($groupIndex); ?>"
                                    id="question-group-<?php echo e($groupIndex); ?>">
                                    <div
                                        class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                        <h6 class="mb-0">Question Group <?php echo e($groupIndex + 1); ?></h6>
                                        <div>
                                            <button type="button" class="btn btn-success btn-sm add-question"
                                                data-group-index="<?php echo e($groupIndex); ?>">Add Question</button>
                                            <button type="button" class="btn btn-danger btn-sm delete-group"
                                                data-group-index="<?php echo e($groupIndex); ?>">Delete Group</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="question-list" data-group-index="<?php echo e($groupIndex); ?>">
                                            <?php if(isset($group['questions'])): ?>
                                                <?php $__currentLoopData = $group['questions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qIndex => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="row question-row mb-3"
                                                        data-question-index="<?php echo e($qIndex); ?>">
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control"
                                                                name="questions[<?php echo e($groupIndex); ?>][questions][<?php echo e($qIndex); ?>][question]"
                                                                placeholder="Enter Question"
                                                                value="<?php echo e($q['question'] ?? ''); ?>" required>
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm delete-question">Delete</button>
                                                        </div>
                                                        <div class="col-12">
                                                            <textarea class="form-control summernote"
                                                                name="questions[<?php echo e($groupIndex); ?>][questions][<?php echo e($qIndex); ?>][answer]" placeholder="Enter Answer" required><?php echo e($q['answer'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-sm-12">
                        <button type="submit"
                            class="btn btn-success btn-sm"><?php echo e(isset($mock) ? 'Update' : 'Submit'); ?></button>
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
                    moduleType: 'mock_viva',
                    allowMultipleCourses: true,

                    // Summernote settings
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    summernoteUploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>"
                });

                // Initialize with existing data
                const courseIds = <?php echo json_encode(old('course_ids', $mock->courses->pluck('id')), 512) ?>;
                const chapterId = '<?php echo e(old("chapter_id", $mock->chapter_id)); ?>';
                const lessonId = '<?php echo e(old("lesson_id", $mock->lesson_id)); ?>';

                formHandler.initializeWithData(courseIds, chapterId, lessonId);

            // Dynamic group/question logic
            let groupIndex = $('#question_groups_container .question-group').length || 0;
            let questionCounters = {};

            // Initialize question counters for each group from existing data
            $('#question_groups_container .question-group').each(function() {
                let gIndex = $(this).data('group-index');
                let count = $(this).find('.question-row').length;
                questionCounters[gIndex] = count;
            });

            function createGroupHTML(index) {
                return `
                <div class="card mb-3 question-group" data-group-index="${index}" id="question-group-${index}">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h6 class="mb-0">Question Group ${index + 1}</h6>
                        <div>
                            <button type="button" class="btn btn-success btn-sm add-question" data-group-index="${index}">Add Question</button>
                            <button type="button" class="btn btn-danger btn-sm delete-group" data-group-index="${index}">Delete Group</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="question-list" data-group-index="${index}"></div>
                    </div>
                </div>
            `;
            }

            function createQuestionHTML(gIndex, qIndex) {
                return `
                <div class="row question-row mb-3" data-question-index="${qIndex}">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="questions[${gIndex}][questions][${qIndex}][question]" placeholder="Enter Question" required>
                    </div>
                    <div class="col-md-5">
                        <textarea class="form-control summernote" name="questions[${gIndex}][questions][${qIndex}][answer]" placeholder="Enter Answer" required></textarea>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-danger btn-sm delete-question">Delete</button>
                    </div>
                </div>
            `;
            }

            $('#add_group_button').on('click', function() {
                $('#question_groups_container').append(createGroupHTML(groupIndex));
                questionCounters[groupIndex] = 0;
                groupIndex++;
            });

            $(document).on('click', '.add-question', function() {
                const gIndex = $(this).data('group-index');
                const qIndex = questionCounters[gIndex] || 0;
                questionCounters[gIndex] = qIndex + 1;
                const html = createQuestionHTML(gIndex, qIndex);
                $(`#question-group-${gIndex} .question-list`).append(html);
                $('.summernote').summernote({
                    placeholder: 'Enter your content here',
                    height: 200,
                    callbacks: {
                        onImageUpload: function(files) {
                            var editor = $(this);
                            var data = new FormData();
                            data.append('image', files[0]);
                            $.ajax({
                                url: '<?php echo e(route('admin.mockvivas.uploadImage')); ?>',
                                method: 'POST',
                                data: data,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    $(editor).summernote('insertImage', response
                                        .url);
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.delete-group', function() {
                const gIndex = $(this).data('group-index');
                $(`#question-group-${gIndex}`).remove();
            });

            $(document).on('click', '.delete-question', function() {
                $(this).closest('.question-row').remove();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/mockviva/edit.blade.php ENDPATH**/ ?>