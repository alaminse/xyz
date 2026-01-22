@extends('layouts.backend')
@section('title', 'Mock Viva Create')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Mock Viva Create</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-white" href="{{ route('admin.mockvivas.index') }}">Back</a></li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="{{ route('admin.mockvivas.store') }}" method="POST">
                    @csrf
                    @include('backend.includes.message')

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
                        <div class="col-md-4 mb-3 mt-4">
                            <button type="button" id="add_group_button" class="btn btn-primary btn-sm mt-3 float-right">Add
                                Question Group</button>
                        </div>
                    </div>

                    <div class="col-sm-12 mb-3">
                        <!-- Debug: Show full old questions array -->
                        @if (old('questions'))
                            <div class="mb-3">
                                <h5>Old Questions JSON (for debugging):</h5>
                                <pre>{{ json_encode(old('questions'), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif

                        <div id="question_groups_container">
                            <!-- Old values if form failed -->
                            @if (old('questions'))
                                @foreach (old('questions') as $groupIndex => $group)
                                    <div class="card mb-3 question-group" data-group-index="{{ $groupIndex }}"
                                        id="question-group-{{ $groupIndex }}">
                                        <div
                                            class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                            <h6 class="mb-0">Question Group {{ $groupIndex + 1 }}</h6>
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm add-question"
                                                    data-group-index="{{ $groupIndex }}">Add Question</button>
                                                <button type="button" class="btn btn-danger btn-sm delete-group"
                                                    data-group-index="{{ $groupIndex }}">Delete Group</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="question-list" data-group-index="{{ $groupIndex }}">
                                                @if (isset($group['questions']))
                                                    @foreach ($group['questions'] as $qIndex => $q)
                                                        <div class="row question-row mb-3"
                                                            data-question-index="{{ $qIndex }}">
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                    name="questions[{{ $groupIndex }}][questions][{{ $qIndex }}][question]"
                                                                    placeholder="Enter Question"
                                                                    value="{{ $q['question'] ?? '' }}" required>
                                                            </div>
                                                            <div class="col-md-2 text-right">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm delete-question">Delete</button>
                                                            </div>
                                                            <div class="col-12">
                                                                <textarea class="form-control summernote"
                                                                    name="questions[{{ $groupIndex }}][questions][{{ $qIndex }}][answer]" placeholder="Enter Answer" required>{{ $q['answer'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
                moduleType: 'mock_viva',
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

            // Dynamic group/question logic
            let groupIndex = $('#question_groups_container .question-group').length || 0;
            let questionCounters = {};

            function createGroupHTML(index) {
                return `
                <div class="card mb-3 question-group" data-group-index="${index}" id="question-group-${index}">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <h6 class="mb-0">Question Group ${index + 1}</h6>
                        <div>
                            <button type="button" class="btn btn-success btn-sm add-question" data-group-index="${index}">Add Question</button>
                            <button type="button" class="btn btn-danger btn-sm delete-group" data-group-index="${index}">Delete Group</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="question-list" data-group-index="${index}"></div>

                    </div>
                </div>
            `;
            }

            function createQuestionHTML(gIndex, qIndex) {
                return `
                <div class="row question-row mb-3" data-question-index="${qIndex}">
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="questions[${gIndex}][questions][${qIndex}][question]" placeholder="Enter Question" required>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" class="btn btn-danger btn-sm delete-question">Delete</button>
                    </div>
                    <div class="col-12 mt-2">
                        <textarea class="form-control summernote" name="questions[${gIndex}][questions][${qIndex}][answer]" placeholder="Enter Answer" required></textarea>
                    </div>
                </div>
            `;
            }

            $('#add_group_button').on('click', function() {
                $('#question_groups_container').append(createGroupHTML(groupIndex));
                questionCounters[groupIndex] = 0;
                groupIndex++;
            });

            $(document).on('click', '.add-question', function() {
                const gIndex = $(this).data('group-index');
                const qIndex = questionCounters[gIndex]++;
                const html = createQuestionHTML(gIndex, qIndex);
                $(`#question-group-${gIndex} .question-list`).append(html);
                $('.summernote').summernote({
                    placeholder: 'Enter your content here',
                    height: 200,
                    callbacks: {
                        onImageUpload: function(files) {
                            var editor = $(this);
                            var data = new FormData();
                            data.append('image', files[0]);
                            $.ajax({
                                url: '{{ route('admin.mockvivas.uploadImage') }}',
                                method: 'POST',
                                data: data,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    $(editor).summernote('insertImage', response
                                        .url);
                                }
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.delete-group', function() {
                const gIndex = $(this).data('group-index');
                $(`#question-group-${gIndex}`).remove();
            });

            $(document).on('click', '.delete-question', function() {
                $(this).closest('.question-row').remove();
            });
        });
    </script>
@endpush
