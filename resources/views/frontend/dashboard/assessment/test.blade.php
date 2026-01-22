@extends('frontend.dashboard.app')
@section('title', 'Assessment')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/self_assessment.css') }}">

@endsection

@section('content')
    <div class="assessment-container">
        {{-- Header Section --}}
        <div class="card mb-3">
            <div class="card-body p-1 p-sm-2">
                <div
                    class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                    <div class="flex-grow-1">
                        <h5 class="card-title fs-6 fs-sm-5">{{ $assessment->name }}</h5>
                        {{-- <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">{{ $course->name }}</li>
                                <li class="breadcrumb-item">{{ $assessment->chapter?->name }}</li>
                                @if ($assessment->lesson)
                                    <li class="breadcrumb-item active text-mute" aria-current="page">
                                        {{ $assessment->lesson?->name }}
                                    </li>
                                @endif
                            </ol>
                        </nav> --}}
                    </div>
                    <div class="flex-shrink-0">
                        {{-- @dump($assessment) --}}
                        <a href="{{ route('assessments.index', $course->slug) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card mb-3 p-2">
            <h4>{{ $assessment->name }}</h4>
        </div> --}}
        <div class="row">
            <!-- Main Question Area -->
            <div class="col-lg-8 col-md-7">
                <div id="questions-container">
                    @foreach ($questions as $index => $question)
                        @php
                            $q = json_decode($question->questions);
                        @endphp

                        @if ($question->question_type == 'sba')
                            <div class="sba-question" data-index="{{ $index }}" data-type="sba"
                                data-questionId="{{ $question->id }}" style="{{ $index == 0 ? '' : 'display:none;' }}">

                                <div class="question-card">
                                    <!-- Question Header -->
                                    <div class="question-header">
                                        <div class="question-number">
                                            <i class="fas fa-question-circle"></i> Question {{ $index + 1 }}
                                        </div>
                                        <div class="question-text">{!! $q->question !!}</div>
                                    </div>

                                    <input type="hidden" name="question[]" value="{{ $question->id }}">
                                    <input type="hidden" name="type[]" value="{{ $question->question_type }}">

                                    <!-- Options -->
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if (!empty($q->{'option' . $i}))
                                            <div class="option-container d-flex align-items-center"
                                                data-option="option{{ $i }}"
                                                onclick="selectOption{{ $index }}({{ $i }})">
                                                <input type="radio" id="option{{ $i }}_{{ $index }}"
                                                    name="question{{ $index }}" value="option{{ $i }}">
                                                <label for="option{{ $i }}_{{ $index }}">
                                                    {{ $q->{'option' . $i} }}
                                                </label>
                                            </div>
                                        @endif
                                    @endfor

                                    <!-- Buttons -->
                                    <div class="button-group">
                                        @if ($index > 0)
                                            <button type="button" class="btn btn-assessment btn-secondary back">
                                                <i class="fas fa-arrow-left"></i> Previous
                                            </button>
                                        @endif

                                        @if ($index < count($questions) - 1)
                                            <button type="button" class="btn btn-assessment next">
                                                Next <i class="fas fa-arrow-right"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-assessment btn-submit submit">
                                                <i class="fas fa-check-circle"></i> Submit Assessment
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <script>
                                    function selectOption{{ $index }}(optionNum) {
                                        const radio = document.getElementById('option' + optionNum + '_{{ $index }}');
                                        radio.checked = true;

                                        // Trigger change event using jQuery
                                        $(radio).trigger('change');

                                        // Visual feedback
                                        const container = document.querySelector('[data-index="{{ $index }}"]');
                                        container.querySelectorAll('.option-container').forEach(el => {
                                            el.classList.remove('selected');
                                        });
                                        event.currentTarget.classList.add('selected');
                                    }
                                </script>
                            </div>
                        @elseif ($question->question_type == 'mcq')
                            <div class="mcq-question" data-index="{{ $index }}"
                                data-questionId="{{ $question->id }}" data-type="mcq"
                                style="{{ $index == 0 ? '' : 'display:none;' }}">

                                <div class="question-card">
                                    <!-- Question Header -->
                                    <div class="question-header">
                                        <div class="question-number">
                                            <i class="fas fa-list-check"></i> Question {{ $index + 1 }}
                                        </div>
                                        <div class="question-text">{!! $q->question !!}</div>
                                    </div>

                                    <input type="hidden" name="question[]" value="{{ $question->id }}">
                                    <input type="hidden" name="type[]" value="{{ $question->question_type }}">

                                    <!-- MCQ Options -->
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if (!empty($q->{'option' . $i}))
                                            <div class="mcq-option-row">
                                                <div class="mcq-option-text">
                                                    {{ $q->{'option' . $i} }}
                                                </div>
                                                <div class="mcq-radio-group">
                                                    <div class="mcq-radio-item" data-option="option{{ $i }}">
                                                        <input type="radio"
                                                            id="option{{ $index }}{{ $i }}_true"
                                                            name="question{{ $index }}[option{{ $i }}]"
                                                            value="true">
                                                        <label for="option{{ $index }}{{ $i }}_true">
                                                            <i class="fas fa-check"></i> True
                                                        </label>
                                                    </div>
                                                    <div class="mcq-radio-item" data-option="option{{ $i }}">
                                                        <input type="radio"
                                                            id="option{{ $index }}{{ $i }}_false"
                                                            name="question{{ $index }}[option{{ $i }}]"
                                                            value="false">
                                                        <label for="option{{ $index }}{{ $i }}_false">
                                                            <i class="fas fa-times"></i> False
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endfor

                                    <!-- Buttons -->
                                    <div class="button-group">
                                        @if ($index > 0)
                                            <button type="button" class="btn btn-assessment btn-secondary back">
                                                <i class="fas fa-arrow-left"></i> Previous
                                            </button>
                                        @endif

                                        @if ($index < count($questions) - 1)
                                            <button type="button" class="btn btn-assessment next">
                                                Next <i class="fas fa-arrow-right"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-assessment btn-submit submit">
                                                <i class="fas fa-check-circle"></i> Submit Assessment
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-5">
                <div class="sidebar-card">
                    <!-- Progress Section -->
                    <div class="progress-section">
                        <h4 class="sidebar-title">
                            <i class="fas fa-chart-line"></i> Your Progress
                        </h4>
                        <div id="progress-count">

                        </div>
                        <ul id="selected-options-count" class="progress-list"></ul>
                        <div id="submitButton">

                        </div>
                    </div>

                    <!-- Timer Section -->
                    <div class="timer-container">
                        <div class="timer-label">
                            <i class="fas fa-clock"></i> Time Remaining
                        </div>
                        <div id="timer" class="timer-display">00:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- Submit Confirmation Modal --}}
    <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="submitConfirmModalLabel">
                        <i class="fas fa-paper-plane"></i> Submit Assessment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-center mb-3">Are you sure you want to submit your assessment?</h6>
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Total Questions:</strong></span>
                            <span id="modal-total-questions">{{ count($questions) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Answered:</strong></span>
                            <span id="modal-answered-questions" class="text-success">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>Remaining:</strong></span>
                            <span id="modal-remaining-questions" class="text-danger">0</span>
                        </div>
                    </div>
                    <p class="text-muted text-center mb-0">
                        <small><i class="fas fa-info-circle"></i> After submitting, you will not be able to make any changes</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmSubmitBtn">
                        <i class="fas fa-check-circle"></i> Yes, Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        // Assessment configuration
        const assessmentConfig = {
            assessmentId: '{{ $assessment->id }}',
            courseId: '{{ $course->id }}',
            questions: {!! json_encode($questions) !!},
            time: parseInt('{{ $assessment->time }}'),
            csrfToken: '{{ csrf_token() }}',
            submitRoute: '{{ route('assessments.submit') }}'
        };
    </script>
    <script src="{{ asset('frontend/js/assessment_test.js') }}"></script>
@endpush
@endsection
