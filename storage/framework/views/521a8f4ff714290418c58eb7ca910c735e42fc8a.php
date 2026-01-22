<?php $__env->startSection('title', 'Assessment Details'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .assessment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            padding: 30px;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .info-card-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .question-card {
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .question-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .question-body {
            padding: 20px;
            background: #f8f9fa;
        }

        .option-item {
            background: white;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 2px solid #e9ecef;
        }

        .option-item.correct {
            border-color: #28a745;
            background: #d4edda;
        }

        .modal-lg {
            max-width: 900px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        
        <div class="assessment-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-3"><?php echo e($assessment_course->name); ?></h1>
                    <div class="d-flex flex-wrap gap-3">
                        <span><i class="fa fa-clock-o"></i> <?php echo e($assessment_course->time); ?> Minutes</span>
                        <span><i class="fa fa-question-circle"></i> <?php echo e($questions->count()); ?> Questions</span>
                        <span><i class="fa fa-star"></i> <?php echo e($assessment_course->total_marks); ?> Marks</span>
                    </div>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.assessments.edit', $assessment_course->id)); ?>" class="btn btn-light btn-sm">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo e(route('admin.assessments.index')); ?>" class="btn btn-outline-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-lg-4 mb-4">
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fa fa-info-circle"></i> Assessment Information
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <?php if($assessment_course->status == 1): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Inactive</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <strong>Duration:</strong> <?php echo e($assessment_course->time); ?> Minutes
                    </div>

                    <div class="mb-3">
                        <strong>Total Questions:</strong> <?php echo e($questions->count()); ?>

                    </div>

                    <div class="mb-3">
                        <strong>Total Marks:</strong> <?php echo e($assessment_course->total_marks); ?>

                    </div>

                    <div class="mb-3">
                        <strong>Chapter:</strong> <?php echo e($assessment_course->chapter->name ?? 'N/A'); ?>

                    </div>

                    <div class="mb-3">
                        <strong>Lesson:</strong> <?php echo e($assessment_course->lesson->name ?? 'N/A'); ?>

                    </div>

                    <div class="mb-3">
                        <strong>Payment:</strong>
                        <?php if($assessment_course->isPaid): ?>
                            <span class="badge badge-warning">Paid</span>
                        <?php else: ?>
                            <span class="badge badge-success">Free</span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fa fa-book"></i> Associated Courses
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = explode(', ', $assessment_course->course_names); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <span class="badge bg-success me-1 mb-1 px-2 text-white"><?php echo e($courseName); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted">No courses</p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="col-lg-8">
                <div class="info-card">
                    <div class="info-card-title d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-list"></i> Questions (<?php echo e($questions->count()); ?>)</span>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#addQuestionModal">
                            <i class="fa fa-plus"></i> Add Question
                        </button>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $questionData = json_decode($question->questions);
                            $questionType = $question->question_type;
                        ?>

                        <div class="question-card">
                            <div class="question-header" data-toggle="collapse"
                                data-target="#question-<?php echo e($index); ?>">
                                <div>
                                    <span class="badge badge-light mr-2"><?php echo e($index + 1); ?></span>
                                    <?php echo e(Str::limit(strip_tags($questionData->question ?? 'Question'), 80)); ?>

                                </div>
                                <div>
                                    <span class="badge badge-warning"><?php echo e(strtoupper($questionType)); ?></span>
                                    <i class="fa fa-chevron-down ml-2"></i>
                                </div>
                            </div>

                            <div id="question-<?php echo e($index); ?>" class="collapse">
                                <div class="question-body">
                                    
                                    <div class="mb-3">
                                        <strong>Question:</strong>
                                        <div class="mt-2"><?php echo $questionData->question ?? 'N/A'; ?></div>
                                    </div>

                                    
                                    <div class="d-flex gap-3 mb-3">
                                        <span><strong>Marks: </strong> +<?php echo e($questionData->mark_per_question ?? 0); ?> </span>
                                        <span class="pl-3   "><strong> Negative: </strong> -<?php echo e($questionData->minus_mark ?? 0); ?></span>
                                    </div>

                                    
                                    <div class="mb-3">
                                        <strong>Options:</strong>
                                        <?php if($questionType === 'sba'): ?>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php
                                                    $optionKey = 'option' . $i;
                                                    $isCorrect = ($questionData->correct_option ?? '') === $optionKey;
                                                ?>
                                                <?php if(isset($questionData->$optionKey) && !empty($questionData->$optionKey)): ?>
                                                    <div class="option-item <?php echo e($isCorrect ? 'correct' : ''); ?>">
                                                        <strong><?php echo e(chr(64 + $i)); ?>.</strong>
                                                        <?php echo e($questionData->$optionKey); ?>

                                                        <?php if($isCorrect): ?>
                                                            <span class="badge badge-success float-right">Correct</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        <?php elseif($questionType === 'mcq'): ?>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php
                                                    $optionKey = 'option' . $i;
                                                    $answerKey = 'answers' . $i;
                                                    $isCorrect =
                                                        isset($questionData->$answerKey) &&
                                                        $questionData->$answerKey == 1;
                                                ?>
                                                <?php if(isset($questionData->$optionKey) && !empty($questionData->$optionKey)): ?>
                                                    <div class="option-item <?php echo e($isCorrect ? 'correct' : ''); ?>">
                                                        <strong><?php echo e(chr(64 + $i)); ?>.</strong>
                                                        <?php echo e($questionData->$optionKey); ?>

                                                        <span
                                                            class="badge badge-<?php echo e($isCorrect ? 'success' : 'danger'); ?> float-right">
                                                            <?php echo e($isCorrect ? 'True' : 'False'); ?>

                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <?php if(isset($questionData->explanation) && !empty($questionData->explanation)): ?>
                                        <div class="alert alert-info">
                                            <strong><i class="fa fa-lightbulb-o"></i> Explanation:</strong>
                                            <div class="mt-2"><?php echo $questionData->explanation; ?></div>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-primary edit-question"
                                            data-question-id="<?php echo e($question->id); ?>"
                                            data-question-data='<?php echo json_encode($questionData, 15, 512) ?>'
                                            data-question-type="<?php echo e($questionType); ?>">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-question"
                                            data-question-id="<?php echo e($question->id); ?>">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No questions added yet. Click "Add Question" to get started.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-plus-circle"></i> <span id="modalTitle">Add New Question</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="questionForm" action="<?php echo e(route('admin.assessments.question.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="assessment_id" value="<?php echo e($assessment_course->id); ?>">
                    <input type="hidden" name="question_id" id="questionId">

                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label>Question Type <span class="text-danger">*</span></label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="type_mcq" name="type" value="mcq"
                                        class="custom-control-input" checked>
                                    <label class="custom-control-label" for="type_mcq">MCQ</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="type_sba" name="type" value="sba"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="type_sba">SBA</label>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label>Question <span class="text-danger">*</span></label>
                            <textarea name="question" id="questionText" class="form-control summernote" required></textarea>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Marks Per Question <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="mark_per_question" id="markPerQuestion"
                                        class="form-control" value="0.4" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minus Mark</label>
                                    <input type="number" step="0.01" name="minus_mark" id="minusMark"
                                        class="form-control" value="0.05">
                                </div>
                            </div>
                        </div>

                        
                        <div id="optionsContainer">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <div class="form-group option-group">
                                    <label>Option <?php echo e($i); ?> <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="text" name="option<?php echo e($i); ?>"
                                                id="option<?php echo e($i); ?>" class="form-control">
                                        </div>
                                        <div class="col-md-4" id="mcqAnswer<?php echo e($i); ?>">
                                            <select name="answers<?php echo e($i); ?>" class="form-control">
                                                <option value="">Answer</option>
                                                <option value="1">True</option>
                                                <option value="0">False</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        
                        <div class="form-group" id="sbaCorrectAnswer" style="display:none;">
                            <label>Correct Answer <span class="text-danger">*</span></label>
                            <select name="correct_option" id="correctOption" class="form-control">
                                <option value="">Select Correct Option</option>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="option<?php echo e($i); ?>">Option <?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        
                        <div class="form-group">
                            <label>Explanation</label>
                            <textarea name="explanation" id="explanationText" class="form-control summernote" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Save Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote for Question field
            const questionSummernote = new SummernoteHandler({
                selector: '.summernote',
                height: 200,
                csrfToken: '<?php echo e(csrf_token()); ?>',
                uploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>",
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Question Type Toggle
            $('input[name="type"]').change(function() {
                const type = $(this).val();
                if (type === 'sba') {
                    $('[id^="mcqAnswer"]').hide();
                    $('#sbaCorrectAnswer').show();
                    $('#markPerQuestion').val('2');
                    $('#minusMark').val('0');
                } else {
                    $('[id^="mcqAnswer"]').show();
                    $('#sbaCorrectAnswer').hide();
                    $('#markPerQuestion').val('0.4');
                    $('#minusMark').val('0.05');
                }
            });

            // Edit Question
            $(document).on('click', '.edit-question', function() {
                const questionId = $(this).data('question-id');
                const questionData = $(this).data('question-data');
                const questionType = $(this).data('question-type');

                $('#modalTitle').text('Edit Question');
                $('#questionId').val(questionId);
                $('#questionForm').attr('action', '<?php echo e(route('admin.assessments.question.update', '')); ?>/' +
                    questionId);

                // Set question type
                $(`input[name="type"][value="${questionType}"]`).prop('checked', true).trigger('change');

                // Set question text using Summernote
                $('#questionText').summernote('code', questionData.question || '');
                $('#markPerQuestion').val(questionData.mark_per_question || '');
                $('#minusMark').val(questionData.minus_mark || '');

                // Set options
                for (let i = 1; i <= 5; i++) {
                    $(`#option${i}`).val(questionData[`option${i}`] || '');
                    if (questionType === 'mcq') {
                        $(`select[name="answers${i}"]`).val(questionData[`answers${i}`] || '');
                    }
                }

                if (questionType === 'sba') {
                    $('#correctOption').val(questionData.correct_option || '');
                }

                // Set explanation using Summernote
                $('#explanationText').summernote('code', questionData.explanation || '');

                $('#addQuestionModal').modal('show');
            });

            // Reset form on modal close
            $('#addQuestionModal').on('hidden.bs.modal', function() {
                $('#modalTitle').text('Add New Question');
                $('#questionId').val('');
                $('#questionForm').attr('action', '<?php echo e(route('admin.assessments.question.store')); ?>');
                $('#questionForm')[0].reset();

                // Clear Summernote content
                $('#questionText').summernote('code', '');
                $('#explanationText').summernote('code', '');

                $('input[name="type"][value="mcq"]').prop('checked', true).trigger('change');
            });

            // Delete Question
            $(document).on('click', '.delete-question', function() {
                if (!confirm('Are you sure you want to delete this question?')) return;

                const questionId = $(this).data('question-id');

                $.ajax({
                    url: '<?php echo e(route('admin.assessments.question.delete', '')); ?>/' + questionId,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        assessment_id: '<?php echo e($assessment_course->id); ?>'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Failed to delete question');
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/assessment/show.blade.php ENDPATH**/ ?>