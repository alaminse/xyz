@extends('layouts.backend')

@section('title', 'MCQ Edit')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .x_panel {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .x_title {
            border-bottom: 2px solid #e8e8e8;
            padding: 20px;
        }
        .form-label {
            font-weight: 600;
            color: #2a3f54;
            margin-bottom: 8px;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            min-height: 40px;
        }
        .btn-group-action {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .info-badge {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin-bottom: 20px;
        }
    </style>
@endsection
@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-edit"></i> Edit MCQ</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="{{ route('admin.mcqs.index') }}">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('backend.includes.message')

            <div class="info-badge">
                <strong><i class="fa fa-info-circle"></i> Note:</strong>
                This form updates only the MCQ metadata. To manage questions, use the "Manage Questions" button below.
            </div>

            <form action="{{ route('admin.mcqs.update', $mcq->id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa fa-graduation-cap"></i> Courses
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $mcq->courses->contains($course->id) ? 'selected' : '' }}>
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
                            <i class="fa fa-folder"></i> Chapter
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                data-old-value="{{ old('chapter_id', $mcq->chapter_id) }}">
                            <option value="" disabled selected>Select Chapter</option>
                        </select>
                        @error('chapter_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="lessonSelect" class="form-label">
                            <i class="fa fa-book"></i> Lesson
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="lesson_id" id="lessonSelect" class="select2 form-control" required
                                data-old-value="{{ old('lesson_id', $mcq->lesson_id) }}">
                            <option value="" disabled selected>Select Lesson</option>
                        </select>
                        @error('lesson_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label for="isPaid" class="form-label">
                            <i class="fa fa-lock"></i> Payment Status
                        </label>
                        <div class="mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="isPaid" id="isPaid" value="1"
                                    {{ old('isPaid', $mcq->isPaid) == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="isPaid">
                                    This is paid content
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa fa-toggle-on"></i> Status
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="status" class="select2 form-control">
                            <option value="1" {{ $mcq->status == 1 ? 'selected' : '' }}>
                                <i class="fa fa-check-circle"></i> Active
                            </option>
                            <option value="2" {{ $mcq->status == 2 ? 'selected' : '' }}>
                                <i class="fa fa-times-circle"></i> Inactive
                            </option>
                            <option value="3" {{ $mcq->status == 3 ? 'selected' : '' }}>
                                <i class="fa fa-trash"></i> Delete
                            </option>
                        </select>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-12 btn-group-action">
                        <button type='submit' class="btn btn-success">
                            <i class="fa fa-save"></i> Update MCQ
                        </button>
                        <button type='reset' class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                        <a href="{{ route('admin.mcqs.show', $mcq->id) }}" class="btn btn-primary">
                            <i class="fa fa-list"></i> Manage Questions
                        </a>
                        <a href="{{ route('admin.mcqs.index') }}" class="btn btn-warning">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/js/dependent-dropdown-handler.js') }}"></script>

    <script>
        $(document).ready(function() {
            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'mcq',
                allowMultipleCourses: true
            });

            const existingCourseIds = @json($mcq->courses->pluck('id')->toArray());
            const existingChapterId = {{ $mcq->chapter_id }};
            const existingLessonId = {{ $mcq->lesson_id }};

            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
@endpush
@endsection
