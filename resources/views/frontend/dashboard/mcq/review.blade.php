@extends('frontend.dashboard.app')
@section('title', 'MCQ Review')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/summernote_show.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mcq.css') }}">
@endsection

@section('content')
    {{-- Header Section --}}
    <div class="card mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">Mcq Test Review</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">{{ $quiz->course?->name }}</li>
                            <li class="breadcrumb-item">{{ $quiz->chapter?->name }}</li>
                            @if ($quiz->lesson)
                                <li class="breadcrumb-item active text-mute" aria-current="page">{{ $quiz->lesson?->name }}
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('mcqs.index', ['course' => $quiz->course?->slug]) }}"
                        class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="test-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <h6 class="text-white mb-0">
                        Question <span id="current-question-number">1</span> of
                        <span id="total-question-number">{{ count($questions) }}</span>
                    </h6>
                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>
                </div>

                <div id="sba-container">
                    @php
                        $correct = 0;
                    @endphp

                    @foreach ($questions as $index => $question)
                        <div class="mcq-question" data-index="{{ $index }}">
                            <div class="question-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">Question {{ $index + 1 }}</span>
                                </div>

                                <h5 class="mb-4">{{ $question->question }}</h5>

                                @foreach ($question->answers as $i => $answer)
                                    <div class="option-item {{ $answer->is_correct == true ? 'correct' : 'wrong' }} option-container mb-2" data-option="{{ $i }}"
                                        data-correct="{{ $answer->correct }}">

                                        <div class="row align-items-center">

                                            <!-- Option text -->
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <strong class="option-text">
                                                    {{ $answer->option_text }}
                                                </strong>
                                            </div>

                                            <!-- Controls / Result -->
                                            <div class="col-12 col-md-6 text-md-end">

                                                <div class="option-controls d-flex flex-column align-items-md-end gap-1">

                                                    <!-- Correct label -->
                                                    <span class="correct-label {{ $answer->selected == $answer->correct ? '' : 'd-none' }} badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        Correct
                                                    </span>

                                                    <!-- Chosen label -->
                                                    <span class="chosen-label  {{ $answer->selected != $answer->correct ? '' : 'd-none' }}  badge bg-danger">
                                                         You chose: <strong>{{ $answer->selected == 1 ? 'True' : 'False' }}</strong><br>
                                                        Correct: <strong>{{ $answer->correct == 1 ? 'True' : 'False' }}</strong>
                                                    </span>

                                                    <!-- Radio buttons (only before submit) -->
                                                    <div class="answer-inputs d-flex gap-2">
                                                        <label class="mb-0">
                                                            <input type="radio"
                                                                name="question-{{ $index }}-{{ $i }}"
                                                                value="1"
                                                                {{ $answer->selected == 1 ? 'checked' : '' }}>
                                                            <span class="badge bg-success">True</span>
                                                        </label>

                                                        <label class="mb-0">
                                                            <input type="radio"
                                                                name="question-{{ $index }}-{{ $i }}"
                                                                value="0"
                                                                {{ $answer->selected == 0 ? 'checked' : '' }}
                                                                >
                                                            <span class="badge bg-danger">False</span>
                                                        </label>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                                @if (!empty($question->explain))
                                    <div class="explanation-card">
                                        <h6><i class="bi bi-lightbulb text-warning"></i> Explanation</h6>
                                        <div>{!! $question->explain !!}</div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                        <i class="bi bi-arrow-left-circle"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </button>
                                </div>

                                @if (!empty($question->note_description))
                                    <div class="note-preview">
                                        <h6><i class="bi bi-journal-text text-info"></i> Related Note</h6>
                                        @if (!empty($question->note_title))
                                            <strong>{{ $question->note_title }}</strong>
                                        @endif
                                        <div class="mt-2">{!! $question->note_description !!}</div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                        <i class="bi bi-arrow-left-circle"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="status-card">
                <div class="score-display">
                    <h6 class="mb-2">Final Score</h6>
                    <h3>
                        <span id="scorePercentage">
                            {{ $data['total'] > 0 ? number_format(($correct / $data['total']) * 100, 2) : 0 }}
                        </span>%
                    </h3>
                    <div class="mt-3">
                        <small>{{ $correct }} out of {{ $data['total'] }} correct</small>
                    </div>
                </div>

                <div class="mb-3">
                    @php
                        $percentage = $data['total'] > 0 ? ($correct / $data['total']) * 100 : 0;
                        $progressClass =
                            $percentage >= 75
                                ? 'bg-success'
                                : ($percentage >= 50
                                    ? 'bg-warning'
                                    : ($percentage >= 25
                                        ? 'bg-info'
                                        : 'bg-danger'));
                    @endphp
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated {{ $progressClass }}"
                            role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($percentage, 0) }}%
                        </div>
                    </div>
                </div>

                <h6 class="mb-3"><i class="bi bi-list-check"></i> Questions</h6>
                <div class="questions-list">

                    @foreach ($questions as $index => $answer)
                        <div class="question-link" data-index="{{ $index }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <small>
                                        <span class="text-warning fw-bold">Q {{ $index + 1 }}.</span>
                                        {{ Str::limit($answer->question, 50) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                const totalQuestions = $('.mcq-question').length;
                let currentIndex = 0;

                function showQuestion(index) {
                    if (index < 0 || index >= totalQuestions) return;

                    $('.mcq-question').hide();
                    $('.mcq-question[data-index="' + index + '"]').fadeIn(200);

                    currentIndex = index;
                    updateUI();
                }

                function updateUI() {
                    $('#current-question-number').text(currentIndex + 1);

                    $('#previous-button').prop('disabled', currentIndex === 0);
                    $('#next-button').prop('disabled', currentIndex === totalQuestions - 1);

                    $('.question-link').removeClass('active');
                    $('.question-link[data-index="' + currentIndex + '"]').addClass('active');
                }

                // 👉 Sidebar question click
                $('.question-link').on('click', function() {
                    let index = $(this).data('index');
                    showQuestion(index);
                });

                // 👉 Next / Previous
                $('#next-button').on('click', function() {
                    showQuestion(currentIndex + 1);
                });

                $('#previous-button').on('click', function() {
                    showQuestion(currentIndex - 1);
                });

                // 👉 Keyboard support
                $(document).on('keydown', function(e) {
                    if (e.key === 'ArrowRight') showQuestion(currentIndex + 1);
                    if (e.key === 'ArrowLeft') showQuestion(currentIndex - 1);
                });

                // 🔥 Initial load
                $('.mcq-question').hide();
                showQuestion(0);
            });
        </script>
    @endpush

@endsection
