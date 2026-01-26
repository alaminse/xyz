@extends('layouts.backend')
@section('title', 'OSPE Create')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>OSPE Create</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-white" href="{{ route('admin.ospestations.index') }}">Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="{{ route('admin.ospestations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('backend.includes.message')

                    <div class="row">
                        {{-- Courses Selection --}}
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">
                                Courses <span class="required text-danger">*</span>
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
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="chapterSelect" class="form-label">
                                Chapter <span class="required text-danger">*</span>
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
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="lessonSelect" class="form-label">
                                Lesson <span class="required text-danger">*</span>
                            </label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required
                                data-old-value="{{ old('lesson_id') }}">
                                <option value="" disabled selected>Select Lesson</option>
                            </select>
                            @error('lesson_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-4 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control select2">
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- IsPaid -->
                        <div class="col-md-4 mb-3 mt-4">
                            <label>Is Paid</label>
                            <input type="checkbox" name="isPaid" value="1" {{ old('isPaid', 1) ? 'checked' : '' }}>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
                moduleType: 'ospe',
                allowMultipleCourses: true,
            });
            // Restore old values on validation error
            const oldCourseIds = $('#courseSelect').data('old-value');
            const oldChapterId = $('#chapterSelect').data('old-value');
            const oldLessonId = $('#lessonSelect').data('old-value');

            if (oldCourseIds && oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
            }
        });
    </script>
@endpush
