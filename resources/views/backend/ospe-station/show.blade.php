@extends('layouts.backend')
@section('title', 'Ospe Station Details')

@section('css')

    <style>
        .dynamic-question-item {
            border: 1px solid #dee2e6;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .question-body {
            margin-top: 10px;
        }

        .dynamic-question-item.collapsed .question-body {
            display: none;
        }

        .question-toggle {
            cursor: pointer;
            font-weight: 600;
            color: #007bff;
        }

        .question-group-card {
            margin-bottom: 1.5rem;
            border: 1px solid #007bff;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            position: relative;
        }

        .question-header {
            font-weight: 600;
            font-size: 1.2rem;
            color: #0056b3;
            margin-bottom: 1rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.25rem;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
            position: relative;
            padding-right: 150px;
        }

        .question-header:hover {
            color: #007bff;
            background-color: rgba(0, 123, 255, 0.05);
            padding-left: 5px;
        }

        .question-header::after {
            content: '\f078';
            font-family: 'FontAwesome';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease;
            font-size: 0.9rem;
        }

        .question-header.collapsed::after {
            transform: translateY(-50%) rotate(-90deg);
        }

        .qa-item {
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.4rem;
            padding: 0.75rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
        }

        .question-text {
            background-color: #e2f0fb;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 600;
            user-select: none;
        }

        .answer-text {
            background-color: #d4edda;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            color: #155724;
            font-style: italic;
        }

        .group-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
        }

        .question-group-content {
            margin-top: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title d-flex justify-content-between align-items-center">
                <h2>Ospe Station Details</h2>
                <div>
                    <button type="button" class="btn btn-sm btn-primary me-2" data-toggle="modal"
                        data-target="#addQuestionGroupModal">
                        <i class="fa fa-plus"></i> Add Question Group
                    </button>
                    <a href="{{ route('admin.ospestations.index') }}" class="btn btn-sm btn-success">Back to List</a>
                </div>
            </div>
            <div class="x_content pt-4">
                <div class="mockviva-details mb-4 p-3 border rounded bg-white shadow-sm">
                    <div class="row text-dark">
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Courses</h5>
                            <p class="mb-0">
                                @foreach ($ospe->courses as $course)
                                    <span class="badge bg-primary">{{ $course->name }}</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Chapter</h5>
                            <p class="mb-0">{{ $ospe->chapter->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Lesson</h5>
                            <p class="mb-0">{{ $ospe->lesson->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="mb-1 fw-bold">Status</h5>
                            @php
                                $statusLabels = [
                                    0 => ['Pending', 'badge bg-warning text-dark'],
                                    1 => ['Active', 'badge bg-success'],
                                    2 => ['Inactive', 'badge bg-secondary'],
                                ];
                                $status = $statusLabels[$ospe->status] ?? ['Unknown', 'badge bg-dark'];
                            @endphp
                            <span class="{{ $status[1] }}">{{ $status[0] }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="mb-1 fw-bold">Is Paid</h5>
                            @if ($ospe->isPaid)
                                <span class="badge bg-info text-dark">Yes</span>
                            @else
                                <span class="badge bg-light text-muted">No</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                @forelse ($ospe->questions as $groupIndex => $questionGroup)
                    @php
                        $questions = json_decode($questionGroup->questions, true) ?? [];
                    @endphp
                    <div class="question-group-card">
                        <div class="group-actions">
                            <button type="button" class="btn btn-sm btn-warning edit-group-btn"
                                data-id="{{ $questionGroup->id }}" data-questions='@json($questions)'
                                data-image="{{ $questionGroup->image }}">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-group-btn"
                                data-id="{{ $questionGroup->id }}" data-title="{{ $questionGroup->title }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                        <div class="question-header">Question Group {{ $groupIndex + 1 }}</div>

                        <div class="question-group-content">
                            @if (is_array($questions) && count($questions) > 0)
                                <div class="row">
                                    <div class="col-sm-12 col-md-8">
                                        @foreach ($questions as $qIndex => $q)
                                            <div class="qa-item">
                                                <div class="question-text">Q{{ $qIndex + 1 }}:
                                                    {{ $q['question'] ?? 'N/A' }}</div>
                                                <div class="answer-text">Answer: {!! $q['answer'] ?? 'N/A' !!}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        @if ($questionGroup->image)
                                            <img src="{{ asset('uploads/' . $questionGroup->image) }}" alt="OSPE Image"
                                                class="img-thumbnail"
                                                style="max-height: 300px; width: 100%; object-fit: cover;">
                                        @else
                                            <p class="text-muted">No image available</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No questions in this group.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No question groups available. Click "Add Question Group" to create
                        one.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add Question Group Modal -->
    <div class="modal fade" id="addQuestionGroupModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form action="{{ route('admin.ospestations.questions.store', $ospe->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Question Group</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <!-- Image -->
                        <div class="mb-3">
                            <label>Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" id="addImageInput" class="form-control" accept="image/*"
                                required>
                            <div id="addImagePreview" class="mt-2"></div>
                        </div>

                        <hr>

                        <!-- Questions -->
                        <h6 class="mb-2">Questions</h6>

                        <div id="questionsContainer">

                            <div class="dynamic-question-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="question-toggle">Question 1</span>
                                </div>

                                <div class="question-body">
                                    <div class="mb-2">
                                        <label>Question *</label>
                                        <textarea name="questions[0][question]" class="form-control" required></textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label>Answer *</label>
                                        <textarea name="questions[0][answer]" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <button type="button" class="btn btn-sm btn-success mt-2" id="addQuestionBtn">
                            <i class="fa fa-plus"></i> Add Another Question
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Question Group
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Edit Question Group Modal -->
    <div class="modal fade" id="editQuestionGroupModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form id="editQuestionForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Question Group</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Current Image</label>
                            <div id="currentImagePreview"></div>
                        </div>

                        <div class="mb-3">
                            <label>Change Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <hr>

                        <div id="editQuestionsContainer"></div>

                        <button type="button" class="btn btn-sm btn-success mt-2" id="addEditQuestionBtn">
                            <i class="fa fa-plus"></i> Add Question
                        </button>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this question group?</p>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteGroupForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let questionCount = 1;
            let editQuestionCount = 0;

            /* =========================
               COLLAPSE / EXPAND QUESTION GROUPS ON PAGE
            ========================== */
            $(document).on('click', '.question-header', function(e) {
                // Only collapse if clicking directly on the header text, not on buttons
                if ($(e.target).hasClass('question-header')) {
                    $(this).next('.question-group-content').slideToggle(300);
                    $(this).toggleClass('');
                }
            });

            /* =========================
               ADD QUESTION (ADD MODAL)
            ========================== */
            $('#addQuestionBtn').on('click', function() {
                const html = `
        <div class="dynamic-question-item">
            <div class="d-flex justify-content-between align-items-center">
                <span class="question-toggle">Question ${questionCount + 1}</span>
                <button type="button" class="btn btn-sm btn-danger remove-question-btn">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <div class="question-body">
                <div class="mb-2">
                    <label>Question *</label>
                    <textarea name="questions[${questionCount}][question]" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Answer *</label>
                    <textarea name="questions[${questionCount}][answer]" class="form-control" required></textarea>
                </div>
            </div>
        </div>`;

                $('#questionsContainer').append(html);
                questionCount++;
            });

            /* =========================
               REMOVE QUESTION (ADD)
            ========================== */
            $(document).on('click', '.remove-question-btn', function() {
                if (confirm('Remove this question?')) {
                    $(this).closest('.dynamic-question-item').remove();
                }
            });

            /* =========================
               COLLAPSE / EXPAND MODAL QUESTIONS
            ========================== */
            $(document).on('click', '.question-toggle', function() {
                $(this).closest('.dynamic-question-item').toggleClass('collapsed');
            });

            /* =========================
               IMAGE PREVIEW (ADD MODAL)
            ========================== */
            $('#addImageInput').on('change', function() {
                const file = this.files[0];
                if (!file) {
                    $('#addImagePreview').html('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    $('#addImagePreview').html(
                        `<img src="${e.target.result}" class="img-thumbnail" style="max-height:150px;">`
                    );
                };
                reader.readAsDataURL(file);
            });

            /* =========================
               IMAGE PREVIEW (EDIT MODAL)
            ========================== */
            $(document).on('change', '#editQuestionForm input[name="image"]', function() {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = e => {
                    $('#currentImagePreview').html(
                        `<img src="${e.target.result}" class="img-thumbnail" style="max-height:150px;">`
                    );
                };
                reader.readAsDataURL(file);
            });

            /* =========================
               EDIT GROUP
            ========================== */
            $(document).on('click', '.edit-group-btn', function() {
                const id = $(this).data('id');
                const questions = $(this).data('questions');
                const image = $(this).data('image');

                $('#editQuestionForm').attr(
                    'action',
                    '/admin/ospestations/questions/' + id + '/update'
                );

                // Image preview
                if (image) {
                    $('#currentImagePreview').html(
                        `<img src="/uploads/${image}" class="img-thumbnail" style="max-height:150px;">`
                    );
                } else {
                    $('#currentImagePreview').html('<p class="text-muted">No image</p>');
                }

                // Questions
                $('#editQuestionsContainer').html('');
                editQuestionCount = 0;

                questions.forEach((q, i) => {
                    $('#editQuestionsContainer').append(`
            <div class="dynamic-question-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="question-toggle">Question ${i + 1}</span>
                    <button type="button" class="btn btn-sm btn-danger remove-edit-question-btn">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

                <div class="question-body">
                    <div class="mb-2">
                        <label>Question *</label>
                        <textarea name="questions[${i}][question]" class="form-control" required>${q.question}</textarea>
                    </div>
                    <div class="mb-2">
                        <label>Answer *</label>
                        <textarea name="questions[${i}][answer]" class="form-control" required>${q.answer}</textarea>
                    </div>
                </div>
            </div>
        `);
                    editQuestionCount++;
                });

                $('#editQuestionGroupModal').modal('show');
            });

            /* =========================
               ADD QUESTION (EDIT MODAL)
            ========================== */
            $('#addEditQuestionBtn').on('click', function() {
                $('#editQuestionsContainer').append(`
        <div class="dynamic-question-item">
            <div class="d-flex justify-content-between align-items-center">
                <span class="question-toggle">Question ${editQuestionCount + 1}</span>
                <button type="button" class="btn btn-sm btn-danger remove-edit-question-btn">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <div class="question-body">
                <div class="mb-2">
                    <label>Question *</label>
                    <textarea name="questions[${editQuestionCount}][question]" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Answer *</label>
                    <textarea name="questions[${editQuestionCount}][answer]" class="form-control" required></textarea>
                </div>
            </div>
        </div>
    `);

                editQuestionCount++;
            });

            /* =========================
               REMOVE QUESTION (EDIT)
            ========================== */
            $(document).on('click', '.remove-edit-question-btn', function() {
                if (confirm('Remove this question?')) {
                    $(this).closest('.dynamic-question-item').remove();
                }
            });

            /* =========================
               RESET ADD MODAL
            ========================== */
            $('#addQuestionGroupModal').on('hidden.bs.modal', function() {
                this.querySelector('form').reset();
                $('#addImagePreview').html('');
                questionCount = 1;

                $('#questionsContainer').html(`
        <div class="dynamic-question-item">
            <div class="d-flex justify-content-between align-items-center">
                <span class="question-toggle">Question 1</span>
            </div>

            <div class="question-body">
                <div class="mb-2">
                    <label>Question *</label>
                    <textarea name="questions[0][question]" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Answer *</label>
                    <textarea name="questions[0][answer]" class="form-control" required></textarea>
                </div>
            </div>
        </div>
    `);
            });

            /* =========================
               OPEN DELETE MODAL
            ========================== */
            $(document).on('click', '.delete-group-btn', function() {
                let id = $(this).data('id');
                let title = $(this).data('title');

                $('#deleteGroupName').text(title);

                $('#deleteGroupForm').attr(
                    'action',
                    '/admin/ospestations/questions/' + id + '/delete'
                );

                $('#deleteConfirmModal').modal('show');
            });
        </script>
    @endpush
@endsection
