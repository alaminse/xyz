@extends('layouts.backend')

@section('title', 'MCQ Create')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>MCQ Create</h2>
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
            <form action="{{ route('admin.mcqs.store') }}" method="POST">
                @csrf
                @include('backend.includes.message')

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            Courses <span class="required text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ (collect(old('course_ids'))->contains($course->id)) ? 'selected' : '' }}>
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="isPaid">IsPaid <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input type="checkbox" name="isPaid" class="form-check-input" id="isPaid" value="1">
                            </div>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary">Create MCQ</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
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
            const oldCourseIds = {{ json_encode(old('course_ids', [])) }};
            const oldChapterId = {{ old('chapter_id', 'null') }};
            const oldLessonId = {{ old('lesson_id', 'null') }};

            if (oldCourseIds && oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                setTimeout(function() {
                    notesHandler.loadNotes();
                }, 500);
            }
        });
    </script>
@endpush
@endsection
