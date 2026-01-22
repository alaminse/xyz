@extends('layouts.backend')
@section('title', 'Assessment Create')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px 20px;
            cursor: pointer;
        }

        .card-body {
            padding: 25px;
            background: #f8f9fa;
        }

        .form-control,
        .select2-container .select2-selection--single {
            border-radius: 6px;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
        }

        .form-check-input:checked+.form-check-label {
            color: #667eea;
            font-weight: 600;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .x_panel {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 14px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="col-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-file-text-o"></i> Create New Assessment</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.assessments.index') }}">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.assessments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('backend.includes.message')

                    {{-- Assessment Details Section --}}
                    <div class="section-title">
                        <i class="fa fa-info-circle"></i> Assessment Details
                    </div>

                    <div class="row">
                        {{-- Courses Selection --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Courses <span class="text-danger">*</span>
                            </label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required
                                data-old-value='@json(old('course_ids'))'>
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

                        {{-- Chapter Selection --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="chapterSelect" class="form-label font-weight-bold">
                                Chapter <span class="text-danger">*</span>
                            </label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required
                                data-old-value="{{ old('chapter_id') }}">
                                <option value="" disabled selected>Select Chapter</option>
                            </select>
                            @error('chapter_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Lesson Selection --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="lessonSelect" class="form-label font-weight-bold">
                                Lesson <span class="text-danger">*</span>
                            </label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required
                                data-old-value="{{ old('lesson_id') }}">
                                <option value="" disabled selected>Select Lesson</option>
                            </select>
                            @error('lesson_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Assessment Name --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Assessment Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter assessment name"
                                value="{{ old('name') }}">
                        </div>

                        {{-- Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Time <span class="text-danger">(Minutes)</span>
                            </label>
                            <input type="number" name="time" class="form-control" placeholder="60"
                                value="{{ old('time', 60) }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Status</label>
                            <select name="status" class="select2 form-control">
                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }} selected>Active</option>
                                <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Inactive</option>
                                <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Delete</option>
                            </select>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Start Time</label>
                            <input type="datetime-local" name="start_date_time" class="form-control"
                                value="{{ old('start_date_time') }}">
                        </div>

                        {{-- End Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">End Time</label>
                            <input type="datetime-local" name="end_date_time" class="form-control"
                                value="{{ old('end_date_time') }}">
                        </div>

                        {{-- IsPaid --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Is Paid Content?</label>
                            <div class="mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="isPaid" id="isPaid"
                                        value="1" {{ old('isPaid', 1) == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isPaid">Yes, this is paid content</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Submit Button --}}
                        <div class="col-12">
                            <hr class="my-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Create Assessment
                                </button>
                                <a href="{{ route('admin.assessments.index') }}"
                                    class="btn btn-secondary ml-2">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('backend/js/dependent-dropdown-handler.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Initialize the combined form handler
                const formHandler = new FormHandler({
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'self_assessment',
                    allowMultipleCourses: true,
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '{{ csrf_token() }}',
                    summernoteUploadUrl: "{{ route('admin.summernote.upload') }}"
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
    @endpush
@endsection
