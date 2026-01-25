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
                        $correctAnswers = 0;
                        $totalAnsweredAnswers = 0;
                    @endphp

                    @foreach ($questions as $index => $question)
                        <div class="mcq-question" data-index="{{ $index }}">
                            <div class="question-card">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">Question {{ $index + 1 }}</span>
                                </div>

                                <h5 class="mb-4">{{ $question->question }}</h5>

                                @foreach ($question->answers as $i => $answer)
                                    @php
                                        // Count each MCQ answer individually
                                        if ($answer->selected !== null) {
                                            $totalAnsweredAnswers++;

                                            if ((int) $answer->selected === (int) $answer->correct) {
                                                $correctAnswers++;
                                            }
                                        }
                                    @endphp

                                    <div
                                        class="option-item
                        {{ $answer->selected === null ? '' : ($answer->selected == $answer->correct ? 'correct' : 'wrong') }}
                        option-container mb-2">

                                        <div class="row align-items-center">
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <strong>{{ $answer->option_text }}</strong>
                                            </div>

                                            <div class="col-12 col-md-6 text-md-end">
                                                @if ($answer->selected !== null && $answer->selected == $answer->correct)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Correct
                                                    </span>
                                                @elseif ($answer->selected !== null)
                                                    <span class="badge bg-danger">
                                                        You chose:
                                                        <strong>{{ $answer->selected ? 'True' : 'False' }}</strong><br>
                                                        Correct: <strong>{{ $answer->correct ? 'True' : 'False' }}</strong>
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Not answered</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Explanation --}}
                                @if (!empty($question->explain))
                                    <div class="explanation-card mt-3">
                                        <h6><i class="bi bi-lightbulb text-warning"></i> Explanation</h6>
                                        <div>{!! $question->explain !!}</div>
                                    </div>
                                @endif

                                {{-- Note --}}
                                @if (!empty($question->note_description))
                                    <div class="note-preview mt-3">
                                        <h6><i class="bi bi-journal-text text-info"></i> Related Note</h6>
                                        @if (!empty($question->note_title))
                                            <strong>{{ $question->note_title }}</strong>
                                        @endif
                                        <div class="mt-2">{!! $question->note_description !!}</div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>


            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="status-card">
                <div class="score-display">
                    @php
                        $percentage = $totalAnsweredAnswers > 0
                            ? ($correctAnswers / $totalAnsweredAnswers) * 100
                            : 0;

                        $progressClass =
                            $percentage >= 75 ? 'bg-success'
                            : ($percentage >= 50 ? 'bg-warning'
                            : ($percentage >= 25 ? 'bg-info'
                            : 'bg-danger'));
                    @endphp

                    <h3>{{ number_format($percentage, 2) }}%</h3>
                    <small>{{ $correctAnswers }} out of {{ $totalAnsweredAnswers }} answered correct</small>


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

                    @foreach ($questions as $index => $question)
                        <div class="question-link" data-index="{{ $index }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <small>
                                        <span class="text-warning fw-bold">Q {{ $index + 1 }}.</span>
                                        {{ Str::limit($question->question, 50) }}
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
