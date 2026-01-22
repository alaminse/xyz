@extends('frontend.dashboard.app')
@section('title', 'Flash Card Review')

@section('css')

    <style>
        .rating-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .rating-badge.verywell {
            background: #28a745;
            color: white;
        }

        .rating-badge.confused {
            background: #ffc107;
            color: #000;
        }

        .rating-badge.notatall {
            background: #dc3545;
            color: white;
        }

        .answer-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        {{-- Header Section --}}
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div
                        class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-2">Flash Card Test</h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">{{ $quiz->chapter?->name }}</li>
                                    <li class="breadcrumb-item">{{ $quiz->lesson?->name }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('flashs.index', ['course' => $quiz->course?->slug]) }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back to Flash Cards
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-8">
            <div class="card">
                <div class="card-body bg-light text-dark">
                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-sm btn-warning" id="previous-button" disabled>
                            <i class="bi bi-arrow-left-circle" style="font-size: 20px"></i>
                        </button>
                        <h6>Question <span id="current-question-number">1</span> of <span
                                id="total-question-number">{{ count($answers) }}</span></h6>
                        <button type="button" class="btn btn-sm btn-warning" id="next-button">
                            <i class="bi bi-arrow-right-circle" style="font-size: 20px"></i>
                        </button>
                    </div>

                    <div id="flashcard-container">
                        @foreach ($answers as $index => $answer)
                            <div class="flashcard-review" data-index="{{ $index }}"
                                style="{{ $index == 0 ? '' : 'display:none;' }}">

                                <h5 class="mb-4">{{ $answer['question'] }}</h5>

                                <div class="answer-box mb-3">
                                    <h6 class="text-muted mb-2">Answer:</h6>
                                    <div>{!! $answer['answer'] !!}</div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Your Rating:</h6>
                                    <span class="rating-badge {{ $answer['rating'] }}">
                                        @if ($answer['rating'] === 'verywell')
                                            <i class="bi bi-hand-thumbs-up"></i> Very Well
                                        @elseif($answer['rating'] === 'confused')
                                            <i class="bi bi-question-circle"></i> Confused
                                        @else
                                            <i class="bi bi-hand-thumbs-down"></i> Not At All
                                        @endif
                                    </span>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center mb-4">Session Summary</h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-layers"></i> Total Questions:</span>
                            <strong>{{ $data['total'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-check-circle text-success"></i> Correct:</span>
                            <strong class="text-success">{{ $data['correct'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-x-circle text-danger"></i> Wrong:</span>
                            <strong class="text-danger">{{ $data['wrong'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-percent"></i> Score:</span>
                            <strong>{{ $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100) : 0 }}%</strong>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Rating Breakdown:</h6>
                    @php
                        $verywellCount = 0;
                        $confusedCount = 0;
                        $notatallCount = 0;

                        foreach ($answers as $answer) {
                            if ($answer['rating'] === 'verywell') {
                                $verywellCount++;
                            } elseif ($answer['rating'] === 'confused') {
                                $confusedCount++;
                            } else {
                                $notatallCount++;
                            }
                        }
                    @endphp

                    <div class="mb-2">
                        <span class="rating-badge verywell">Very Well</span>
                        <strong class="ms-2">{{ $verywellCount }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="rating-badge confused">Confused</span>
                        <strong class="ms-2">{{ $confusedCount }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="rating-badge notatall">Not At All</span>
                        <strong class="ms-2">{{ $notatallCount }}</strong>
                    </div>

                    <hr>

                    <h6 class="mb-3">Questions:</h6>
                    <div class="questions" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($answers as $index => $answer)
                            <a href="javascript:void(0);" class="question-link d-block text-decoration-none mb-2"
                                data-index="{{ $index }}">
                                <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                <small>{{ \Illuminate\Support\Str::limit($answer['question'], 40) }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const totalQuestions = $('.flashcard-review').length;
                let currentQuestionIndex = 0;

                function updateQuestionDisplay() {
                    $('#current-question-number').text(currentQuestionIndex + 1);
                    $('#next-button').prop('disabled', currentQuestionIndex === totalQuestions - 1);
                    $('#previous-button').prop('disabled', currentQuestionIndex === 0);
                }

                function showQuestion(index) {
                    $('.flashcard-review').hide();
                    $(`.flashcard-review[data-index="${index}"]`).show();
                    currentQuestionIndex = index;
                    updateQuestionDisplay();
                }

                $('#next-button').click(() => showQuestion(currentQuestionIndex + 1));
                $('#previous-button').click(() => showQuestion(currentQuestionIndex - 1));

                // Handle question link clicks
                $('.question-link').click(function() {
                    const index = $(this).data('index');
                    showQuestion(index);
                });

                // Keyboard navigation
                $(document).on('keydown', function(e) {
                    if (e.key === 'ArrowRight' && currentQuestionIndex < totalQuestions - 1) {
                        showQuestion(currentQuestionIndex + 1);
                    } else if (e.key === 'ArrowLeft' && currentQuestionIndex > 0) {
                        showQuestion(currentQuestionIndex - 1);
                    }
                });

                // Initialize question display
                updateQuestionDisplay();
            });
        </script>
    @endpush
@endsection
