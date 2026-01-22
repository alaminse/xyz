@extends('layouts.backend')
@section('title', 'Assessment Edit')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>
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
                <h2><i class="fa fa-edit"></i> Edit Assessment</h2>
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
                <form action="{{ route('admin.assessments.update', $assessment_course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('backend.includes.message')

                    {{-- Assessment Details Section --}}
                    <div class="section-title">
                        <i class="fa fa-info-circle"></i> Assessment Details
                    </div>

                    <div class="row">
                        @php
                            $course_ids = $assessment_course->courses->pluck('id')->toArray();
                        @endphp


                        {{-- Courses Selection --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Courses <span class="text-danger">*</span>
                            </label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ in_array($course->id, old('course_ids', $course_ids ?? [])) ? 'selected' : '' }}>
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
                            <select name="chapter_id" id="chapterSelect" required class="select2 form-control"
                                    data-old-value="{{ old('chapter_id', $assessment_course->chapter_id) }}">
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
                            <select name="lesson_id" id="lessonSelect" class="select2 form-control" required
                                    data-old-value="{{ old('lesson_id', $assessment_course->lesson_id) }}">
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
                                value="{{ old('name', $assessment_course->name) }}">
                        </div>

                        {{-- Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">
                                Time <span class="text-danger">(Minutes)</span>
                            </label>
                            <input type="number" name="time" class="form-control" placeholder="60"
                                value="{{ old('time', $assessment_course->time) }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Status</label>
                            <select name="status" class="select2 form-control">
                                <option value="1" {{ old('status', $assessment_course->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ old('status', $assessment_course->status) == 2 ? 'selected' : '' }}>Inactive</option>
                                <option value="3" {{ old('status', $assessment_course->status) == 3 ? 'selected' : '' }}>Delete</option>
                            </select>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Start Time</label>
                            <input type="datetime-local" name="start_date_time" class="form-control"
                                value="{{ old('start_date_time', $assessment_course->start_date_time) }}">
                        </div>

                        {{-- End Time --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">End Time</label>
                            <input type="datetime-local" name="end_date_time" class="form-control"
                                value="{{ old('end_date_time', $assessment_course->end_date_time) }}">
                        </div>

                        {{-- IsPaid --}}
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label class="form-label font-weight-bold">Is Paid Content?</label>
                            <div class="mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="isPaid" id="isPaid" value="1"
                                        {{ old('isPaid', $assessment_course->isPaid) == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="isPaid">Yes, this is paid content</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">


                    <div class="row">
                        {{-- Submit Button --}}
                        <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-save"></i> Update Assessment
                                </button>
                                <a href="{{ route('admin.assessments.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

        <script src="{{ asset('backend/js/dependent-dropdown-handler.js') }}"></script>
        <script>
            $(document).ready(function() {

                let questionIndex = '{{ count($questions) }}';
                // Initialize the combined form handler
                const formHandler = new FormHandler({
                    // Dependent dropdown settings
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'self_assessment',
                    allowMultipleCourses: true,

                    // Summernote settings
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '{{ csrf_token() }}',
                    summernoteUploadUrl: "{{ route('admin.summernote.upload') }}"
                });

                // Initialize with existing data
                const courseIds = @json(old('course_ids', $assessment_course->courses->pluck('id')));
                const chapterId = '{{ old("chapter_id", $assessment_course->chapter_id) }}';
                const lessonId = '{{ old("lesson_id", $assessment_course->lesson_id) }}';

                formHandler.initializeWithData(courseIds, chapterId, lessonId);
            });
        </script>
    @endpush
@endsection
