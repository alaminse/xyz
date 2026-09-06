@extends('layouts.backend')

@section('title', 'Edit Note')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Note</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="{{ route('admin.notes.index') }}"> Back</a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="{{ route('admin.notes.update', $note->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('backend.includes.message')

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="form-label">Courses <span class="required text-danger">*</span></label>
                        <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ $note->courses->contains($course->id) ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_ids')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="chapterSelect" class="form-label">Chapter <span class="required text-danger">*</span></label>
                        <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                data-old-value="{{ old('chapter_id', $note->chapter_id) }}">
                            <option value="" disabled selected>Select Chapter</option>
                        </select>
                        @error('chapter_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="lessonSelect" class="form-label">Lesson</label>
                        <select name="lesson_id" id="lessonSelect" class="select2 form-control"
                                data-old-value="{{ old('lesson_id', $note->lesson_id) }}">
                            <option value="">Select Lesson (optional)</option>
                        </select>
                        @error('lesson_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label for="isPaid" class="form-label">IsPaid</label>
                        <br>
                        <input class="mt-2" type="checkbox" name="isPaid" id="isPaid" value="1"
                            {{ old('isPaid', $note->isPaid) == 1 ? 'checked' : '' }}>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Status <span class="required text-danger">*</span></label>
                        <select name="status" class="select2 form-control">
                            <option value="1" {{ $note->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $note->status == 2 ? 'selected' : '' }}>Inactive</option>
                            <option value="3" {{ $note->status == 3 ? 'selected' : '' }}>Delete</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 mt-3">
                        <button type='submit' class="btn btn-warning">Update</button>
                        <button type='reset' class="btn btn-success">Reset</button>
                        <a href="{{ route('admin.notes.show', $note->id) }}" class="btn btn-info">Manage Sections</a>
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
                moduleType: 'note',
                allowMultipleCourses: true
            });

            const existingCourseIds = @json($note->courses->pluck('id')->toArray());
            const existingChapterId = {{ $note->chapter_id }};
            const existingLessonId = {{ $note->lesson_id ?? 'null' }};
            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
@endpush
@endsection
