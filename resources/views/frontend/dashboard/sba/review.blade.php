@extends('frontend.dashboard.app')
@section('title', 'SBA Review')
@section('css')

<link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
<style>
    .review-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
    }
    .review-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .option-correct {
        background: #d4edda !important;
        border-color: #28a745 !important;
        font-weight: 600;
    }
    .option-wrong {
        background: #f8d7da !important;
        border-color: #dc3545 !important;
    }
    .option-item {
        border: 2px solid #e0e0e0;
        margin-bottom: 10px;
        border-radius: 8px;
        padding: 12px;
    }
    .explanation-box {
        background: #e7f3ff;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .note-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .sidebar-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 12px;
        padding: 20px;
        position: sticky;
        top: 20px;
    }
    .question-link {
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .question-link:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateX(5px);
    }
    .question-link.active {
        background: rgba(255,255,255,0.3);
        border-color: white;
    }
    .score-badge {
        background: rgba(255,255,255,0.2);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 20px;
    }
    .score-badge h4 {
        color: white;
        font-weight: bold;
        font-size: 2rem;
        margin: 0;
    }
</style>
@endsection
@section('content')
    {{-- Header Section --}}
    <div class="card mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">Sba Test Review</h5>
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
                    <a href="{{ route('sbas.index', ['course' => $quiz->course?->slug]) }}"
                        class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="review-container">
                @if(empty($answers))
                    <div class="review-card text-center py-5">
                        <i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No Answers Found</h4>
                        <p class="text-muted">You haven't answered any questions yet.</p>
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                            <i class="bi bi-arrow-left-circle"></i>
                        </button>
                        <h6 class="text-white mb-0">
                            Question <span id="current-question-number">1</span> of
                            <span id="total-question-number">{{ count($answers) }}</span>
                        </h6>
                        <button type="button" class="btn btn-light btn-sm" id="next-button">
                            <i class="bi bi-arrow-right-circle"></i>
                        </button>
                    </div>

                    <div id="sba-container">
                        @foreach ($answers as $index => $answer)
                            <div class="sba-question" data-index="{{ $index }}" style="{{ $index == 0 ? '' : 'display:none;' }}">
                                <div class="review-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="mb-0">
                                            @if(isset($answer['question']))
                                                {!! $answer['question'] !!}
                                            @else
                                                Question not available
                                            @endif
                                        </h5>
                                        <span class="badge {{ isset($answer['is_correct']) && $answer['is_correct'] ? 'bg-success' : 'bg-danger' }}">
                                            @if(isset($answer['is_correct']) && $answer['is_correct'])
                                                <i class="bi bi-check-circle me-1"></i>Correct
                                            @else
                                                <i class="bi bi-x-circle me-1"></i>Wrong
                                            @endif
                                        </span>
                                    </div>

                                    <ul class="list-unstyled">
                                        @php
                                            $options = ['option1', 'option2', 'option3', 'option4', 'option5'];
                                            $hasOptions = false;
                                        @endphp

                                        @foreach ($options as $option)
                                            @if(isset($answer[$option]) && !empty($answer[$option]))
                                                @php
                                                    $hasOptions = true;
                                                    $isCorrect = isset($answer['correct_option']) && $answer['correct_option'] == $option;
                                                    $isSelected = isset($answer['selected_option']) && $answer['selected_option'] == $option;
                                                    $class = $isCorrect ? 'option-correct' : ($isSelected ? 'option-wrong' : '');
                                                @endphp
                                                <li class="option-item {{ $class }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            @if($isSelected)
                                                                <i class="bi bi-hand-index me-2 {{ $isCorrect ? 'text-success' : 'text-danger' }}"></i>
                                                            @endif
                                                            {{ $answer[$option] }}
                                                        </span>
                                                        @if($isCorrect)
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        @elseif($isSelected && !$isCorrect)
                                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach

                                        @if(!$hasOptions)
                                            <li class="option-item">
                                                <span class="text-muted">Options not available</span>
                                            </li>
                                        @endif
                                    </ul>

                                    @if(isset($answer['explain']) && !empty($answer['explain']))
                                        <div class="explanation-box">
                                            <h6><i class="bi bi-lightbulb text-primary me-2"></i>Explanation</h6>
                                            <p class="mb-0">{!! $answer['explain'] !!}</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                                <i class="bi bi-arrow-left-circle"></i>
                                            </button>
                                            <h6 class="text-white mb-0">
                                                Question <span id="current-question-number">1</span> of
                                                <span id="total-question-number">{{ count($answers) }}</span>
                                            </h6>
                                            <button type="button" class="btn btn-light btn-sm" id="next-button">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </div>
                                    @endif

                                    @if(isset($answer['note_description']) && !empty($answer['note_description']))
                                        <div class="note-box">
                                            <h6>
                                                <i class="bi bi-journal-text text-warning me-2"></i>
                                                Related Note
                                                @if(isset($answer['note_title']) && !empty($answer['note_title']))
                                                    : {{ $answer['note_title'] }}
                                                @endif
                                            </h6>
                                            <div>{!! $answer['note_description'] !!}</div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                                <i class="bi bi-arrow-left-circle"></i>
                                            </button>
                                            <h6 class="text-white mb-0">
                                                Question <span id="current-question-number">1</span> of
                                                <span id="total-question-number">{{ count($answers) }}</span>
                                            </h6>
                                            <button type="button" class="btn btn-light btn-sm" id="next-button">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="sidebar-card">
                <div class="score-badge">
                    <h6 class="text-white mb-2">Final Score</h6>
                    <h4>
                        {{ count($answers) > 0
                            ? number_format(($quiz->correct / count($answers)) * 100, 2)
                            : '0.00'
                        }} %
                    </h4>
                    <small class="text-white">{{ $quiz->correct ?? 0 }} Correct | {{ count($answers) }} Total</small>
                </div>

                @if(!empty($answers))
                    <h6 class="text-white mb-3">
                        <i class="bi bi-list-check me-2"></i>All Questions
                    </h6>
                    <div style="max-height: 500px; overflow-y: auto;">
                        @foreach ($answers as $index => $answer)
                            <div class="question-link" data-index="{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            <strong>Q{{ $index + 1 }}.</strong>
                                            @if(isset($answer['question']))
                                                {!! strip_tags($answer['question']) !!}
                                            @else
                                                Question {{ $index + 1 }}
                                            @endif
                                        </span>
                                    </span>
                                    @if(isset($answer['is_correct']) && $answer['is_correct'])
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-light w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to Test
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                const totalQuestions = $('.sba-question').length;
                let currentQuestionIndex = 0;

                // Debug: Log answers data
                console.log('Total questions:', totalQuestions);
                console.log('Answers data:', @json($answers));

                if (totalQuestions === 0) {
                    console.warn('No questions found to display');
                    return;
                }

                function updateQuestionDisplay() {
                    $('#current-question-number').text(currentQuestionIndex + 1);
                    $('#next-button').prop('disabled', currentQuestionIndex === totalQuestions - 1);
                    $('#previous-button').prop('disabled', currentQuestionIndex === 0);

                    // Update active question in sidebar
                    $('.question-link').removeClass('active');
                    $(`.question-link[data-index="${currentQuestionIndex}"]`).addClass('active');
                }

                function showQuestion(index) {
                    if (index < 0 || index >= totalQuestions) return;

                    $('.sba-question').hide();
                    $(`.sba-question[data-index="${index}"]`).show();
                    currentQuestionIndex = index;
                    updateQuestionDisplay();
                }

                $('#next-button').click(() => {
                    if (currentQuestionIndex < totalQuestions - 1) {
                        showQuestion(currentQuestionIndex + 1);
                    }
                });

                $('#previous-button').click(() => {
                    if (currentQuestionIndex > 0) {
                        showQuestion(currentQuestionIndex - 1);
                    }
                });

                $('.question-link').click(function () {
                    const index = $(this).data('index');
                    showQuestion(index);
                });

                // Keyboard navigation
                $(document).keydown(function(e) {
                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (currentQuestionIndex < totalQuestions - 1) {
                            showQuestion(currentQuestionIndex + 1);
                        }
                    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (currentQuestionIndex > 0) {
                            showQuestion(currentQuestionIndex - 1);
                        }
                    }
                });

                updateQuestionDisplay();
            });
        </script>
    @endpush
@endsection
