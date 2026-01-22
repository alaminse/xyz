@extends('layouts.backend')

@section('title', 'Edit Flash Card')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Flash Card</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="{{ route('admin.flashs.show', $flash->id) }}">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form action="{{ route('admin.flashs.update', $flash->id) }}" method="POST">
                @csrf
                @include('backend.includes.message')

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa fa-graduation-cap"></i> Courses
                            <span class="required text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $flash->courses->contains($course->id) ? 'selected' : '' }}>
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
                                data-old-value="{{ old('chapter_id', $flash->chapter_id) }}">
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
                                data-old-value="{{ old('lesson_id', $flash->lesson_id) }}">
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
                                <input type="checkbox" name="isPaid" class="form-check-input" id="isPaid"
                                    value="1" {{ $flash->isPaid ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Flash Card</button>
                <a href="{{ route('admin.flashs.show', $flash->id) }}" class="btn btn-secondary">Cancel</a>
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
                moduleType: 'flush',
                allowMultipleCourses: true
            });


            const existingCourseIds = @json($flash->courses->pluck('id')->toArray());
            const existingChapterId = {{ $flash->chapter_id }};
            const existingLessonId = {{ $flash->lesson_id }};

            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
@endpush
@endsection
