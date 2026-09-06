<?php $__env->startSection('title', 'Show Flash Card'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .question-card {
            transition: all 0.3s ease;
            border-left: 4px solid #28a745;
            margin-bottom: 15px;
        }

        .question-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .question-card .toggle-icon {
            transition: transform 0.3s ease;
        }

        .question-card .card-header[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }

        .question-number {
            background: #28a745;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">🎴 Flash Card Details</h2>
                        <div>
                            <button class="btn btn-success btn-sm" id="add-question" data-toggle="modal"
                                data-target="#questionModal">
                                <i class="fa fa-plus-circle"></i> Add Question
                            </button>
                            <a href="<?php echo e(route('admin.flashs.edit', $flash->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo e(route('admin.flashs.index')); ?>" class="btn btn-warning btn-sm text-white">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="x_content">
                        <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- META INFO -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card meta-card shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-book"></i> Courses
                                        </small>
                                        <?php $__empty_1 = true; $__currentLoopData = $flash->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <span class="badge badge-primary badge-course"><?php echo e($course->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card meta-card shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-list"></i> Chapter
                                        </small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($flash->chapter?->name ?? 'N/A'); ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card meta-card shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-bookmark"></i> Lesson
                                        </small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($flash->lesson?->name ?? 'N/A'); ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card meta-card shadow-sm">
                                    <div class="card-body">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fa fa-layer-group"></i> Total Questions
                                        </small>
                                        <h6 class="mb-0 font-weight-bold"><?php echo e($flash->questions->count()); ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QUESTIONS LIST -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0 font-weight-bold">
                                    <i class="fa fa-layer-group text-success"></i> Flash Card Questions
                                </h5>
                            </div>

                            <div class="card-body">
                                <?php $__empty_1 = true; $__currentLoopData = $flash->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="card question-card">

                                        <!-- HEADER -->
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center"
                                            data-toggle="collapse" data-target="#questionCollapse<?php echo e($question->id); ?>"
                                            aria-expanded="false" style="cursor:pointer">

                                            <div class="d-flex align-items-center">
                                                <span class="question-number"><?php echo e($index + 1); ?></span>
                                                <h6 class="mb-0">
                                                    <?php echo e(\Illuminate\Support\Str::limit($question->question, 80)); ?>

                                                </h6>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-primary edit-question mr-1"
                                                    data-id="<?php echo e($question->id); ?>" onclick="event.stopPropagation();"
                                                    title="Edit Question">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger delete-question mr-2"
                                                    data-id="<?php echo e($question->id); ?>" onclick="event.stopPropagation();"
                                                    title="Delete Question">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <i class="fa fa-chevron-down toggle-icon"></i>
                                            </div>
                                        </div>

                                        <!-- BODY -->
                                        <div id="questionCollapse<?php echo e($question->id); ?>" class="collapse">
                                            <div class="card-body">
                                                <div class="row g-0 border rounded overflow-hidden">

                                                    <!-- QUESTION -->
                                                    <div
                                                        class="col-12 col-md-3 bg-light border-end d-flex align-items-center p-3 fw-semibold">
                                                        Question
                                                    </div>
                                                    <div class="col-12 col-md-9 p-3">
                                                        <div class="fw-bold">
                                                            <?php echo e($question->question); ?>

                                                        </div>
                                                    </div>

                                                    <!-- ANSWER -->
                                                    <div
                                                        class="col-12 col-md-3 bg-light border-top border-end d-flex align-items-center p-3 fw-semibold">
                                                        Answer
                                                    </div>
                                                    <div class="col-12 col-md-9 border-top p-3">
                                                        <?php echo $question->answer; ?>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="empty-state">
                                        <i class="fa fa-inbox"></i>
                                        <h5 class="text-muted">No Questions Yet</h5>
                                        <p class="text-muted mb-3">Start building your flash card by adding questions.</p>
                                        <button class="btn btn-success" data-toggle="modal" data-target="#questionModal">
                                            <i class="fa fa-plus"></i> Add First Question
                                        </button>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-layer-group"></i>
                        <span id="modalTitle">Add Flash Card Question</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="questionForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="question_id" name="question_id">
                    <div class="modal-body">
                        <div class="alert alert-warning d-none" id="duplicateAlert">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Duplicate Found!</strong> This question already exists.
                            <button type="button" class="btn btn-sm btn-info float-right" id="loadExisting">
                                Load Existing Data
                            </button>
                        </div>

                        <div class="form-group">
                            <label for="question">
                                Question <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="question" id="question" class="form-control form-control-lg"
                                required maxlength="1000" placeholder="Enter your question here...">
                            <small class="form-text text-muted">
                                <i class="fa fa-info-circle"></i> Maximum 1000 characters
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="answer">
                                Answer <span class="text-danger">*</span>
                            </label>
                            <textarea name="answer" id="answer" class="form-control summernote" required></textarea>
                            <small class="form-text text-muted">
                                <i class="fa fa-info-circle"></i> Use the editor to format your answer
                            </small>
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
    <script>
        $(document).ready(function() {

            const flashId = <?php echo e($flash->id); ?>;
            let isEdit = false;

            // Summernote
            $('#answer').summernote({
                height: 200
            });

            /* ======================
                ADD / UPDATE QUESTION
            ====================== */
            $('#questionForm').on('submit', function (e) {
                e.preventDefault();

                let questionId = $('#question_id').val();

                let url = questionId
                    ? `/admin/flashs/questions/${questionId}`     // UPDATE
                    : `/admin/flashs/${flashId}/questions`;       // STORE

                $.ajax({
                    url: url,
                    type: 'POST', // ✅ matches BOTH routes
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        question: $('#question').val(),
                        answer: $('#answer').summernote('code')
                    },
                    success: function (res) {
                        console.log(res);

                        if (res.success) {
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        alert('Something went wrong');
                    }
                });
            });
            // $('#questionForm').on('submit', function(e) {
            //     e.preventDefault();

            //     let questionId = $('#question_id').val();
            //     let url = questionId ?
            //         `/admin/flashs/questions/${questionId}` :
            //         `/admin/flashs/${flashId}/questions`;

            //     let method = questionId ? 'PUT' : 'POST';

            //     // $.ajax({
            //     //     url: url,
            //     //     type: 'POST',
            //     //     data: {
            //     //         _token: '<?php echo e(csrf_token()); ?>',
            //     //         _method: method,
            //     //         question: $('#question').val(),
            //     //         answer: $('#answer').summernote('code')
            //     //     },
            //     //     success: function(res) {
            //     //         if (res.success) {
            //     //             location.reload();
            //     //         }
            //     //     },
            //     //     error: function() {
            //     //         alert('Something went wrong');
            //     //     }
            //     // });
            // });

            /* ======================
                EDIT QUESTION
            ====================== */
            $('.edit-question').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                isEdit = true;
                let id = $(this).data('id');

                $.ajax({
                    url: `/admin/flashs/questions/${id}`,
                    type: 'GET',
                    success: function(res) {
                        $('#modalTitle').text('Edit Flash Card Question');
                        $('#question_id').val(res.question.id);
                        $('#question').val(res.question.question);
                        $('#answer').summernote('code', res.question.answer);
                        $('#questionModal').modal('show');
                    },
                    error: function() {
                        alert('Failed to load data');
                    }
                });
            });

            /* ======================
                DELETE QUESTION
            ====================== */
            $('.delete-question').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!confirm('Are you sure to delete this question?')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: `/admin/flashs/questions/${id}`,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        _method: 'DELETE'
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            /* ======================
                MODAL RESET (IMPORTANT)
            ====================== */
            $('#add-question').on('hidden.bs.modal', function() {
                if (!isEdit) {
                    $('#questionForm')[0].reset();
                    $('#answer').summernote('code', '');
                    $('#question_id').val('');
                }
                isEdit = false;
            });

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/flash/show.blade.php ENDPATH**/ ?>