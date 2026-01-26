@extends('layouts.backend')
@section('title', 'OSPE Edit')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>OSPE Edit</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-white" href="{{ route('admin.ospestations.index') }}">Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="{{ route('admin.ospestations.update', $ospe->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('backend.includes.message')

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">Courses <span class="required text-danger">*</span></label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $ospe->courses->contains($course->id) ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="chapterSelect" class="form-label">Chapter <span
                                    class="required text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                data-old-value="{{ old('chapter_id', $ospe->chapter_id) }}">
                                <option value="" disabled selected>Select Chapter</option>
                            </select>
                            @error('chapter_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="lessonSelect" class="form-label">Lesson <span
                                    class="required text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="select2 form-control" required
                                data-old-value="{{ old('lesson_id', $ospe->lesson_id) }}">
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
                                <option value="0" {{ old('status', $ospe->status) == '0' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="1" {{ old('status', $ospe->status) == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="2" {{ old('status', $ospe->status) == '2' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <!-- IsPaid -->
                        <div class="col-md-4 mb-3 mt-4">
                            <label>Is Paid</label>
                            <input type="checkbox" name="isPaid" value="1"
                                {{ old('isPaid', $ospe->isPaid) ? 'checked' : '' }}>
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
                summernoteHeight: 300,
            });

            // Get existing MCQ data for initialization
            const existingCourseIds = @json($ospe->courses->pluck('id')->toArray());
            const existingChapterId = {{ $ospe->chapter_id }};
            const existingLessonId = {{ $ospe->lesson_id }};

            // Initialize with existing data
            if (existingCourseIds && existingCourseIds.length > 0) {
                formHandler.initializeWithData(existingCourseIds, existingChapterId, existingLessonId);
            }
        });
    </script>
@endpush
