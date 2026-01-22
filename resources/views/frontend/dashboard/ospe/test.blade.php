@extends('frontend.dashboard.app')
@section('title', 'OSPE Station Test')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/ospe.css') }}">
    <style>
        .ospe-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .ospe-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ospe-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .question-navigation {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .question-counter {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            color: #495057;
        }

        .nav-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-button:hover:not(:disabled) {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .nav-button:disabled {
            background: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
        }

        .question-content-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .ospe-image-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .ospe-image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .qa-item {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .qa-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .question-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 1rem 1.25rem;
            cursor: pointer;
            font-weight: 600;
            color: #1565c0;
            position: relative;
            transition: background 0.3s ease;
            user-select: none;
        }

        .question-box:hover {
            background: linear-gradient(135deg, #bbdefb 0%, #90caf9 100%);
        }

        .question-box::after {
            content: '\f078';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 1.25rem;
            transition: transform 0.3s ease;
        }

        .question-box[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .answer-box {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 1rem 1.25rem;
            color: #2e7d32;
            border-top: 2px solid #a5d6a7;
        }

        .finish-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .finish-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 3rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .finish-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .badge-question {
            background: #667eea;
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="ospe-container">
        <!-- Header -->
        <div class="ospe-header">
            <h5><i class="bi bi-clipboard-check"></i> OSPE Station Test</h5>
        </div>

        <!-- Navigation -->
        <div class="question-navigation">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="nav-button" disabled id="prevBtn">
                    <i class="bi bi-arrow-left"></i> Previous
                </button>

                <div class="question-counter">
                    Question <span id="current-question-number">1</span> of
                    <span id="total-question-number">{{ count($questions) }}</span>
                </div>

                <button type="button" class="nav-button" id="nextBtn">
                    Next <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question Cards -->
        <div id="question-container">
            @foreach ($questions as $in => $item)
                @php $questionList = json_decode($item->questions); @endphp

                <div class="question-card" id="question-{{ $in }}" style="display: none;">
                    <div class="question-content-card">
                        <!-- Image -->
                        @if($item->image)
                            <div class="ospe-image-container">
                                <img src="{{ asset('uploads/'.$item->image) }}" alt="OSPE Image">
                            </div>
                        @endif

                        <!-- Questions & Answers -->
                        @foreach ($questionList as $index => $q)
                            <div class="qa-item">
                                <div class="question-box"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#answer-{{ $in }}-{{ $index }}"
                                     aria-expanded="false"
                                     aria-controls="answer-{{ $in }}-{{ $index }}">
                                    <span class="badge-question">Q{{ $index + 1 }}</span>
                                    {{ $q->question }}
                                </div>
                                <div class="answer-box collapse" id="answer-{{ $in }}-{{ $index }}">
                                    <strong><i class="bi bi-check-circle"></i> Answer:</strong>
                                    <div class="mt-2">{!! $q->answer !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Finish Section -->
            <div class="finish-section" id="finishSection" style="display: none;">
                <h5 class="mb-3">🎉 Test Completed!</h5>
                <p class="text-muted mb-4">You have reviewed all questions.</p>
                <a href="{{ route('ospes.index', ['course' => $course_slug]) }}" class="finish-button">
                    <i class="bi bi-house-door"></i> Return to OSPE List
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const questions = document.querySelectorAll('.question-card');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const currentQuestionNumber = document.getElementById('current-question-number');
                const finishSection = document.getElementById('finishSection');
                let currentIndex = 0;

                function showQuestion(index) {
                    // Hide all questions and finish section
                    questions.forEach(q => q.style.display = 'none');
                    finishSection.style.display = 'none';

                    // Show current question with fade effect
                    const currentQuestion = questions[index];
                    currentQuestion.style.display = 'block';
                    currentQuestion.style.opacity = '0';
                    setTimeout(() => {
                        currentQuestion.style.transition = 'opacity 0.3s ease';
                        currentQuestion.style.opacity = '1';
                    }, 10);

                    // Update question counter
                    currentQuestionNumber.textContent = index + 1;

                    // Update button states
                    prevBtn.disabled = (index === 0);
                    nextBtn.disabled = (index === questions.length - 1);

                    // Show finish section on last question
                    if (index === questions.length - 1) {
                        finishSection.style.display = 'block';
                        finishSection.style.opacity = '0';
                        setTimeout(() => {
                            finishSection.style.transition = 'opacity 0.3s ease';
                            finishSection.style.opacity = '1';
                        }, 10);
                    }

                    // Scroll to top smoothly
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                // Next button handler
                nextBtn.addEventListener('click', function () {
                    if (currentIndex < questions.length - 1) {
                        currentIndex++;
                        showQuestion(currentIndex);
                    }
                });

                // Previous button handler
                prevBtn.addEventListener('click', function () {
                    if (currentIndex > 0) {
                        currentIndex--;
                        showQuestion(currentIndex);
                    }
                });

                // Initialize first question
                showQuestion(currentIndex);

                // Optional: Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowRight' && !nextBtn.disabled) {
                        nextBtn.click();
                    } else if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
                        prevBtn.click();
                    }
                });
            });
        </script>
    @endpush
@endsection
