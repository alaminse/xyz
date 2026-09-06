<?php $__env->startSection('title', 'Show SBA'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .question-card {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }
        .question-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
                    <h2 class="mb-0">📘 SBA Details</h2>
                    <div>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#questionModal">
                            <i class="fa fa-plus"></i> Add Question
                        </button>
                        <a href="<?php echo e(route('admin.sbas.index')); ?>" class="btn btn-warning btn-sm text-white">
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
                                    <?php $__empty_1 = true; $__currentLoopData = $sba->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                    <h6 class="mb-0 font-weight-bold"><?php echo e($sba->chapter?->name ?? 'N/A'); ?></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <small class="text-muted">Lesson</small>
                                    <h6 class="mb-0 font-weight-bold"><?php echo e($sba->lesson?->name ?? 'N/A'); ?></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <small class="text-muted">Total Questions</small>
                                    <h6 class="mb-0 font-weight-bold"><?php echo e($sba->questions->count()); ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QUESTIONS LIST -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fa fa-question-circle text-primary"></i> Questions
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $sba->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="question-card card mb-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Question #<?php echo e($index + 1); ?></h6>
                                        <div>
                                            <button class="btn btn-sm btn-primary edit-question" data-id="<?php echo e($question->id); ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-question" data-id="<?php echo e($question->id); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Question:</strong>
                                            <div><?php echo $question->question; ?></div>
                                        </div>

                                        <div class="row">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <div class="col-md-6 mb-2">
                                                    <span class="badge badge-<?php echo e($question->correct_option == 'option'.$i ? 'success' : 'secondary'); ?>">
                                                        Option <?php echo e($i); ?>

                                                    </span>
                                                    <?php echo e($question->{'option'.$i}); ?>

                                                </div>
                                            <?php endfor; ?>
                                        </div>

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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="alert alert-info">No questions added yet. Click "Add Question" button to add questions.</div>
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
                        <textarea name="question" id="question" class="form-control summernote" required></textarea>
                    </div>

                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <div class="form-group">
                            <label>Option <?php echo e($i); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="option<?php echo e($i); ?>" id="option<?php echo e($i); ?>" class="form-control" required>
                        </div>
                    <?php endfor; ?>

                    <div class="form-group">
                        <label>Correct Option <span class="text-danger">*</span></label>
                        <select name="correct_option" id="correct_option" class="form-control" required>
                            <option value="">Choose One</option>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <option value="option<?php echo e($i); ?>">Option <?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

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
    const sbaId = <?php echo e($sba->id); ?>;
    const sbaChapterId = <?php echo e($sba->chapter_id ?? 'null'); ?>;
    const sbaLessonId = <?php echo e($sba->lesson_id ?? 'null'); ?>;
    const sbaCourseIds = <?php echo json_encode($sba->courses->pluck('id')->toArray(), 15, 512) ?>;
    let existingQuestionData = null;
    let noteSelect2Initialized = false;

    console.log('SBA Course IDs:', sbaCourseIds);
    console.log('SBA Chapter ID:', sbaChapterId);
    console.log('SBA Lesson ID:', sbaLessonId);

    // Initialize Summernote
    $('.summernote').summernote({
        height: 250,
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

        if (sbaCourseIds.length > 0 && sbaChapterId) {
            // Load notes based on SBA's courses, chapter and lesson
            $.ajax({
                url: '/admin/sbas/get-notes',
                method: 'GET',
                data: {
                    course_ids: sbaCourseIds,
                    chapter_id: sbaChapterId,
                    lesson_id: sbaLessonId
                },
                success: function(response) {
                    console.log('Notes loaded:', response);

                    // Clear existing options except the first one
                    $('#note_id').find('option:not(:first)').remove();

                    // Populate notes
                    if (response.notes && response.notes.length > 0) {
                        $.each(response.notes, function(index, note) {
                            var newOption = new Option(note.title, note.id, false, false);
                            $('#note_id').append(newOption);
                        });
                        console.log('Notes populated successfully:', response.notes.length, 'notes');
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
            console.warn('Missing required data - Course IDs:', sbaCourseIds, 'Chapter ID:', sbaChapterId);
        }
    });

    // Check for duplicate when question loses focus
    $('#question').on('summernote.blur', function() {
        const question = $(this).summernote('code');
        const excludeId = $('#question_id').val();

        if (question.length > 10) {
            $.ajax({
                url: `/admin/sbas/${sbaId}/check-duplicate`,
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
        const url = questionId
            ? `/admin/sbas/questions/${questionId}`
            : `/admin/sbas/${sbaId}/questions`;

        const formData = {
            _token: '<?php echo e(csrf_token()); ?>',
            question: $('#question').summernote('code'),
            note_id: $('#note_id').val() || null,
            option1: $('#option1').val(),
            option2: $('#option2').val(),
            option3: $('#option3').val(),
            option4: $('#option4').val(),
            option5: $('#option5').val(),
            correct_option: $('#correct_option').val(),
            explain: $('#explain').summernote('code'),
        };

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
            url: `/admin/sbas/questions/${questionId}`,
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
            url: `/admin/sbas/questions/${questionId}`,
            method: 'DELETE',
            data: { _token: '<?php echo e(csrf_token()); ?>' },
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
        $('#question').summernote('code', '');
        $('#explain').summernote('code', '');
        $('#question_id').val('');

        // Reset Select2
        if (noteSelect2Initialized) {
            $('#note_id').val(null).trigger('change');
        }

        $('#duplicateAlert').addClass('d-none');
        existingQuestionData = null;
    });

    function fillFormData(data) {
        console.log('Filling form with data:', data);

        $('#question_id').val(data.id);
        $('#question').summernote('code', data.question);
        $('#option1').val(data.option1);
        $('#option2').val(data.option2);
        $('#option3').val(data.option3);
        $('#option4').val(data.option4);
        $('#option5').val(data.option5);
        $('#correct_option').val(data.correct_option);
        $('#explain').summernote('code', data.explain);

        // Set note_id if exists - wait a bit for notes to load
        setTimeout(function() {
            if (data.note_id) {
                console.log('Setting note_id to:', data.note_id);

                // Check if the option exists
                if ($('#note_id').find(`option[value="${data.note_id}"]`).length > 0) {
                    $('#note_id').val(data.note_id).trigger('change');
                    console.log('Note ID set successfully');
                } else {
                    console.warn('Note option not found');
                }
            }
        }, 800);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/sba/show.blade.php ENDPATH**/ ?>