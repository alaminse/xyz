@extends('layouts.backend')
@section('title', 'Edit Note')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Note <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-warning text-white" href="{{ route('admin.notes.index') }}"> Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.notes.update', $note->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('backend.includes.message')

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">Courses <span class="required text-danger">*</span></label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ (collect(old('course_ids', $note->courses->pluck('id')))->contains($course->id)) ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="chapterSelect" class="form-label">Chapter <span
                                    class="required text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Chapter</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="lessonSelect" class="form-label">Lesson <span
                                    class="required text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Lesson</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="isPaid" class="form-label">IsPaid <span
                                    class="required text-danger">*</span></label>
                            <br>
                            <input type="checkbox" name="isPaid" id="isPaid" class="mt-2" value="1"
                                {{ old('isPaid', $note->isPaid) == 1 ? 'checked' : '' }}>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="title" class="form-label">Note Title <span
                                    class="required text-danger">*</span></label>
                            <input class="form-control" id="title" name="title" placeholder="Enter Note title"
                                value="{{ old('title', $note->title) }}" required />
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="description" class="form-label">Description <span
                                    class="required text-danger">*</span></label>
                            <textarea class="form-control summernote" name="description" cols="30" rows="5" required>{!! old('description', $note->description) !!}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $note->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $note->status == 2 ? 'selected' : '' }}>Inactive</option>
                                <option value="3" {{ $note->status == 3 ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 mt-3">
                            <button type='submit' class="btn btn-primary">Update</button>
                            <button type='reset' class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('backend/js/dependent-dropdown-handler.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Initialize the combined form handler
                const formHandler = new FormHandler({
                    // Dependent dropdown settings
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'note',
                    allowMultipleCourses: true,
                    
                    // Summernote settings
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '{{ csrf_token() }}',
                    summernoteUploadUrl: "{{ route('admin.summernote.upload') }}"
                });

                // Initialize with existing data
                const courseIds = @json(old('course_ids', $note->courses->pluck('id')));
                const chapterId = '{{ old("chapter_id", $note->chapter_id) }}';
                const lessonId = '{{ old("lesson_id", $note->lesson_id) }}';

                formHandler.initializeWithData(courseIds, chapterId, lessonId);
            });
        </script>
    @endpush
@endsection