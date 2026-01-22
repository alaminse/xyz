@extends('layouts.backend')

@section('title', 'Flash Card Create')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section h5 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .required-indicator {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>🎴 Create Flash Card</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <a class="btn btn-warning text-white" href="{{ route('admin.flashs.index') }}">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        @include('backend.includes.message')

                        <form action="{{ route('admin.flashs.store') }}" method="POST" id="flashCardForm">
                            @csrf

                            <div class="form-section">
                                <h5><i class="fa fa-book text-primary"></i> Course & Content Selection</h5>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label class="form-label">
                                            Courses <span class="required text-danger">*</span>
                                        </label>
                                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple
                                            required>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    {{ collect(old('course_ids'))->contains($course->id) ? 'selected' : '' }}>
                                                    {{ $course->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_ids')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label for="chapterSelect" class="form-label">
                                            Chapter <span class="required text-danger">*</span>
                                        </label>
                                        <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                            <option value="" disabled selected>Select Chapter</option>
                                        </select>
                                        @error('chapter_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 col-md-6 mb-3">
                                        <label for="lessonSelect" class="form-label">
                                            Lesson <span class="required text-danger">*</span>
                                        </label>
                                        <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                            <option value="" disabled selected>Select Lesson</option>
                                        </select>
                                        @error('lesson_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5><i class="fa fa-cog text-info"></i> Settings</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="isPaid" class="d-block">Payment Type</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="isPaid" class="custom-control-input"
                                                    id="isPaid" value="1" {{ old('isPaid') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="isPaid">
                                                    This is a <strong>Paid</strong> Flash Card
                                                </label>
                                            </div>
                                            <small class="text-muted">Leave unchecked for free access</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                After creating the flash card, you'll be able to add multiple questions and answers.
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Create Flash Card
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg">
                                    <i class="fa fa-refresh"></i> Reset Form
                                </button>
                                <a href="{{ route('admin.flashs.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/js/dependent-dropdown-handler.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true
            });

            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'flush',
                allowMultipleCourses: true
            });

            // Handle old input for validation errors
            const oldCourseIds = {{ json_encode(old('course_ids', [])) }};
            const oldChapterId = {{ old('chapter_id', 'null') }};
            const oldLessonId = {{ old('lesson_id', 'null') }};

            if (oldCourseIds && oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                setTimeout(function() {
                    notesHandler.loadNotes();
                }, 500);
            }

            // Form validation
            $('#flashCardForm').on('submit', function(e) {
                const courseIds = $('#courseSelect').val();
                const chapterId = $('#chapterSelect').val();
                const lessonId = $('#lessonSelect').val();

                if (!courseIds || courseIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one course');
                    return false;
                }

                if (!chapterId) {
                    e.preventDefault();
                    alert('Please select a chapter');
                    return false;
                }

                if (!lessonId) {
                    e.preventDefault();
                    alert('Please select a lesson');
                    return false;
                }
            });
        });
    </script>
@endpush
