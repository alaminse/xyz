<?php $__env->startSection('title', 'Show MCQ'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .question-card {
            transition: all 0.3s ease;
            border-left: 4px solid #17a2b8;
        }

        .question-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .option-badge {
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-right: 10px;
        }

        .question-card .toggle-icon {
            transition: transform 0.3s ease;
        }

        .question-card .card-header[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }

        /* Select2 Modal Fix */
        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">📝 MCQ Details</h2>
                        <div>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#questionModal">
                                <i class="fa fa-plus"></i> Add Question
                            </button>
                            <a href="<?php echo e(route('admin.mcqs.index')); ?>" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="x_content">
                        <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- META INFO -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted">Courses</small><br>
                                        <?php $__empty_1 = true; $__currentLoopData = $mcq->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <span class="badge badge-primary mr-1"><?php echo e($course->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted">Chapter</small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($mcq->chapter?->name ?? 'N/A'); ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted">Lesson</small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($mcq->lesson?->name ?? 'N/A'); ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted">Total Questions</small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($mcq->questions->count()); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QUESTIONS LIST -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0 font-weight-bold">
                                    <i class="fa fa-question-circle text-info"></i> Questions
                                </h5>
                            </div>

                            <div class="card-body">
                                <?php $__empty_1 = true; $__currentLoopData = $mcq->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="card question-card mb-3">

                                        <!-- HEADER -->
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center"
                                            data-toggle="collapse" data-target="#questionCollapse<?php echo e($question->id); ?>"
                                            aria-expanded="false" style="cursor:pointer">

                                            <h6 class="mb-0">
                                                Q<?php echo e($index + 1); ?>.
                                                <?php echo e(\Illuminate\Support\Str::limit($question->question, 70)); ?>

                                            </h6>

                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-primary edit-question mr-1"
                                                    data-id="<?php echo e($question->id); ?>" onclick="event.stopPropagation();">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger delete-question mr-2"
                                                    data-id="<?php echo e($question->id); ?>" onclick="event.stopPropagation();">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <i class="fa fa-chevron-down toggle-icon"></i>
                                            </div>
                                        </div>

                                        <!-- BODY -->
                                        <div id="questionCollapse<?php echo e($question->id); ?>" class="collapse">
                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <strong>Question:</strong>
                                                    <div><?php echo e($question->question); ?></div>
                                                </div>

                                                <table class="table table-bordered table-sm">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="10%">SL</th>
                                                            <th width="70%">Option</th>
                                                            <th width="20%" class="text-center">Answer</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <tr>
                                                                <td><strong><?php echo e($i); ?></strong></td>
                                                                <td><?php echo e($question->{"option{$i}"}); ?></td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge <?php echo e($question->{"answer{$i}"} ? 'badge-success' : 'badge-danger'); ?>">
                                                                        <?php echo e($question->{"answer{$i}"} ? 'True' : 'False'); ?>

                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endfor; ?>
                                                    </tbody>
                                                </table>

                                                <?php if($question->note): ?>
                                                    <div class="mb-3">
                                                        <strong>Related Note:</strong>
                                                        <div class="mb-2"></div>
                                                        <h6><?php echo e($question->note?->title); ?></h6>
                                                        <div class="border p-3 bg-light rounded"><?php echo $question->note?->description; ?>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="mt-3">
                                                    <strong>Explanation:</strong>
                                                    <div class="border p-3 bg-light rounded"><?php echo $question->explain; ?></div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="alert alert-info text-center">
                                        No questions added yet. Click "Add Question" to begin.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Question</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="questionForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="question_id" name="question_id">
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="duplicateAlert">
                            This question already exists!
                            <button type="button" class="btn btn-sm btn-info" id="loadExisting">Load Existing</button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="note_id" class="form-label">Related Notes (Optional)</label>
                            <select name="note_id" id="note_id" class="form-control">
                                <option value="">Select Note</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" id="question" class="form-control" required
                                maxlength="1000">
                        </div>

                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <div class="form-group">
                                <label>Option <?php echo e($i); ?> <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="text" name="option<?php echo e($i); ?>"
                                            id="option<?php echo e($i); ?>" class="form-control" required
                                            maxlength="500">
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="answer<?php echo e($i); ?>"
                                                id="answer<?php echo e($i); ?>" class="form-check-input" value="1">
                                            <label class="form-check-label" for="answer<?php echo e($i); ?>">True</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <div class="form-group">
                            <label>Explanation <span class="text-danger">*</span></label>
                            <textarea name="explain" id="explain" class="form-control summernote" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            const mcqId = <?php echo e($mcq->id); ?>;
            const mcqChapterId = <?php echo e($mcq->chapter_id ?? 'null'); ?>;
            const mcqLessonId = <?php echo e($mcq->lesson_id ?? 'null'); ?>;
            const mcqCourseIds = <?php echo json_encode($mcq->courses->pluck('id')->toArray(), 15, 512) ?>;
            let existingQuestionData = null;
            let noteSelect2Initialized = false;

            console.log('MCQ Course IDs:', mcqCourseIds);
            console.log('MCQ Chapter ID:', mcqChapterId);
            console.log('MCQ Lesson ID:', mcqLessonId);

            // Initialize Summernote
            $('#explain').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'table']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Function to initialize Select2
            function initializeNoteSelect2() {
                if (noteSelect2Initialized) {
                    $('#note_id').select2('destroy');
                }

                $('#note_id').select2({
                    dropdownParent: $('#questionModal'),
                    placeholder: 'Select Note',
                    allowClear: true,
                    width: '100%'
                });

                noteSelect2Initialized = true;
                console.log('Select2 initialized');
            }

            // Load notes when modal opens
            $('#questionModal').on('shown.bs.modal', function() {
                console.log('Modal opened - Loading notes...');

                // Initialize Select2 first
                initializeNoteSelect2();

                if (mcqCourseIds.length > 0 && mcqChapterId) {
                    // Load notes based on MCQ's courses, chapter and lesson
                    $.ajax({
                        url: '/admin/mcqs/get-notes',
                        method: 'GET',
                        data: {
                            course_ids: mcqCourseIds,
                            chapter_id: mcqChapterId,
                            lesson_id: mcqLessonId
                        },
                        success: function(response) {
                            console.log('Notes loaded:', response);

                            // Clear existing options except the first one
                            $('#note_id').find('option:not(:first)').remove();

                            // Populate notes
                            if (response.notes && response.notes.length > 0) {
                                $.each(response.notes, function(index, note) {
                                    var newOption = new Option(note.title, note.id,
                                        false, false);
                                    $('#note_id').append(newOption);
                                });
                                console.log('Notes populated successfully:', response.notes
                                    .length, 'notes');
                            } else {
                                console.log('No notes found');
                            }

                            // Trigger change to refresh select2
                            $('#note_id').trigger('change');
                        },
                        error: function(xhr) {
                            console.error('Error loading notes:', xhr);
                        }
                    });
                } else {
                    console.warn('Missing required data - Course IDs:', mcqCourseIds, 'Chapter ID:',
                        mcqChapterId);
                }
            });

            // Check for duplicate when question loses focus
            $('#question').on('blur', function() {
                const question = $(this).val();
                const excludeId = $('#question_id').val();

                if (question.length > 10) {
                    $.ajax({
                        url: `/admin/mcqs/${mcqId}/check-duplicate`,
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            question: question,
                            exclude_id: excludeId
                        },
                        success: function(response) {
                            if (response.exists) {
                                existingQuestionData = response.question;
                                $('#duplicateAlert').removeClass('d-none');
                            } else {
                                existingQuestionData = null;
                                $('#duplicateAlert').addClass('d-none');
                            }
                        }
                    });
                }
            });

            // Load existing question data
            $('#loadExisting').on('click', function() {
                if (existingQuestionData) {
                    fillFormData(existingQuestionData);
                    $('#duplicateAlert').addClass('d-none');
                }
            });

            // Submit question form
            $('#questionForm').on('submit', function(e) {
                e.preventDefault();
                const questionId = $('#question_id').val();
                const url = questionId ?
                    `/admin/mcqs/questions/${questionId}` :
                    `/admin/mcqs/${mcqId}/questions`;

                const formData = {
                    _token: '<?php echo e(csrf_token()); ?>',
                    question: $('#question').val(),
                    note_id: $('#note_id').val() || null,
                    explain: $('#explain').summernote('code')
                };

                // Add options and answers
                for (let i = 1; i <= 5; i++) {
                    formData[`option${i}`] = $(`#option${i}`).val();
                    formData[`answer${i}`] = $(`#answer${i}`).is(':checked') ? 1 : 0;
                }

                console.log('Submitting form data:', formData);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log('Response:', response);
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        if (xhr.status === 422) {
                            alert(xhr.responseJSON.message);
                        }
                    }
                });
            });

            // Edit question
            $('.edit-question').on('click', function() {
                const questionId = $(this).data('id');

                $.ajax({
                    url: `/admin/mcqs/questions/${questionId}`,
                    method: 'GET',
                    success: function(response) {
                        console.log('Question data loaded for editing:', response);
                        fillFormData(response.question);
                        $('#questionModal').modal('show');
                    }
                });
            });

            // Delete question
            $('.delete-question').on('click', function() {
                if (!confirm('Are you sure you want to delete this question?')) return;

                const questionId = $(this).data('id');

                $.ajax({
                    url: `/admin/mcqs/questions/${questionId}`,
                    method: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });

            // Reset form when modal closes
            $('#questionModal').on('hidden.bs.modal', function() {
                $('#questionForm')[0].reset();
                $('#explain').summernote('code', '');
                $('#question_id').val('');

                // Reset Select2
                if (noteSelect2Initialized) {
                    $('#note_id').val(null).trigger('change');
                }

                $('#duplicateAlert').addClass('d-none');
                existingQuestionData = null;

                for (let i = 1; i <= 5; i++) {
                    $(`#answer${i}`).prop('checked', false);
                }
            });

            function fillFormData(data) {
                console.log('Filling form with data:', data);

                $('#question_id').val(data.id);
                $('#question').val(data.question);
                $('#explain').summernote('code', data.explain || '');

                // Set note_id if exists - wait a bit for notes to load
                setTimeout(function() {
                    if (data.note_id) {
                        console.log('Setting note_id to:', data.note_id);

                        // Check if the option exists
                        if ($('#note_id').find(`option[value="${data.note_id}"]`).length > 0) {
                            $('#note_id').val(data.note_id).trigger('change');
                            console.log('Note ID set successfully');
                        } else {
                            console.warn('Note option not found, creating it...');
                            // If the option doesn't exist, we might need to create it
                            // This can happen if the note is loaded before the options are populated
                        }
                    }
                }, 800);

                for (let i = 1; i <= 5; i++) {
                    $(`#option${i}`).val(data[`option${i}`] || '');
                    $(`#answer${i}`).prop('checked', data[`answer${i}`] == 1);
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/mcq/show.blade.php ENDPATH**/ ?>