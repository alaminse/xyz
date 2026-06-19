@extends('frontend.dashboard.app')
@section('title', 'Written Assessment')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')

<div class="card mb-3">
    <h5 class="p-3">Written Assessment</h5>
</div>

<div class="row mt-4">
    <div class="col-12 mx-auto">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="btn btn-sm btn-warning"
                        href="{{ route('writtens.index', ['course' => $course_slug]) }}">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>
                    <div>
                        <span class="badge bg-info text-dark fs-6">
                            Progress: <span id="completedCount">{{ $completedGroups }}</span> / {{ $totalGroups }}
                        </span>
                        <div class="progress mt-1" style="height:6px; width:150px;">
                            <div class="progress-bar bg-success" id="progressBar"
                                style="width: {{ $totalGroups > 0 ? round(($completedGroups / $totalGroups) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="question-container">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" disabled id="prevBtn">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                            </button>
                        </div>
                        <div class="p-2">
                            <h6>Group <span id="currentGroupNum">1</span> of {{ $totalGroups }}</h6>
                        </div>
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" id="nextBtn"
                                {{ $totalGroups <= 1 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    @foreach($questionGroups as $gi => $group)
                    <div class="question-card" id="group-{{ $gi }}" style="display:none;"
                        data-group-id="{{ $group->id }}">

                        @if($group->isPaid && $isLocked)
                        <div class="p-3 rounded text-center"
                            style="background:#fff8e1; border:2px dashed #ffc107; border-radius:8px;">
                            <i class="bi bi-lock-fill text-warning fs-4"></i>
                            <p class="mb-2 mt-1 small">This group is locked</p>
                            <a href="{{ route('courses.checkout', ['course' => $course_slug]) }}"
                                class="btn btn-warning btn-sm fw-bold">
                                <i class="bi bi-unlock-fill me-1"></i> Upgrade to Premium
                            </a>
                        </div>
                        @else
                        @foreach($group->questions as $qi => $qa)
                        <div class="mt-3 p-3 border rounded text-dark">
                            <div class="question-text p-2 mb-2 rounded"
                                style="cursor:pointer; font-weight:bold; background-color:#e2f0fb;">
                                {{ $qi + 1 }}: {{ $qa['question'] }}
                            </div>
                            <div class="answer-text p-2 rounded"
                                style="display:none; background-color:#d4edda;">
                                {!! $qa['answer'] !!}
                            </div>
                        </div>
                        @endforeach

                        @php
                            $isDone = in_array($group->id, $completedGroupIds);
                        @endphp
                        <div class="text-center mt-3">
                            <button type="button"
                                class="btn btn-sm {{ $isDone ? 'btn-success' : 'btn-outline-success' }} mark-done-btn"
                                data-group-id="{{ $group->id }}"
                                data-course-id="{{ $course->id }}"
                                data-chapter-id="{{ $chapter->id }}"
                                data-lesson-id="{{ $lesson?->id }}"
                                {{ $isDone ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle{{ $isDone ? '-fill' : '' }} me-1"></i>
                                {{ $isDone ? 'Completed' : 'Mark as Done' }}
                            </button>
                        </div>
                        @endif

                    </div>
                    @endforeach

                    <div class="mt-4 text-center" id="finishSection" style="display:none;">
                        <a href="{{ route('writtens.index', ['course' => $course_slug]) }}"
                            class="btn button-yellow px-5">
                            Finish
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        let groups      = $('.question-card');
        let currentIndex = 0;
        let completed   = {{ $completedGroups }};
        let total       = {{ $totalGroups }};

        function showGroup(index) {
            groups.hide();
            $('#finishSection').hide();
            $(groups[index]).fadeIn();
            $('#currentGroupNum').text(index + 1);
            $('#prevBtn').prop('disabled', index === 0);
            $('#nextBtn').prop('disabled', index === groups.length - 1);
            if (index === groups.length - 1) {
                $('#finishSection').fadeIn();
            }
        }

        $(document).on('click', '.question-text', function() {
            $(this).next('.answer-text').slideToggle();
        });

        $('#nextBtn').on('click', function() {
            if (currentIndex < groups.length - 1) {
                currentIndex++;
                showGroup(currentIndex);
            }
        });

        $('#prevBtn').on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showGroup(currentIndex);
            }
        });

        $(document).on('click', '.mark-done-btn', function() {
            const btn       = $(this);
            const groupId   = btn.data('group-id');
            const courseId  = btn.data('course-id');
            const chapterId = btn.data('chapter-id');
            const lessonId  = btn.data('lesson-id');

            $.ajax({
                url: '{{ route('writtens.progress.save') }}',
                method: 'POST',
                data: {
                    _token:             '{{ csrf_token() }}',
                    course_id:          courseId,
                    chapter_id:         chapterId,
                    lesson_id:          lessonId,
                    question_group_id:  groupId,
                },
                success: function(res) {
                    if (res.success) {
                        completed++;
                        const pct = Math.round((completed / total) * 100);
                        $('#completedCount').text(completed);
                        $('#progressBar').css('width', pct + '%');

                        btn.removeClass('btn-outline-success')
                           .addClass('btn-success')
                           .prop('disabled', true)
                           .html('<i class="bi bi-check-circle-fill me-1"></i> Completed');
                    }
                }
            });
        });

        showGroup(currentIndex);
    });
</script>
@endpush
@endsection
