@extends('frontend.dashboard.app')

@section('title', 'Flash Card Test')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/flash.css') }}">
@endsection

@section('content')
    <div class="flashcard-wrapper">
        {{-- Header Section --}}
        <div class="card mb-3">
            <div class="card-body p-1 p-sm-1">
                <div class="d-flex flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                    <div class="flex-grow-1">
                        <div class="nav-card">
                            <div class="nav-buttons">
                                <button class="btn" id="prevBtn" disabled>
                                    <i class="bi bi-arrow-left"></i>
                                </button>

                                <h6 class="mb-0">
                                    Q <span id="currentQ">1</span> of <span id="totalQ">
                                        {{ count($remainingQuestionIds) > 0 ? count($remainingQuestionIds) : $totalOriginalQuestions }}
                                    </span>
                                    <span id="queueInfo" class="text-muted small"></span>
                                </h6>

                                <button class="btn" id="nextBtn">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('flashs.index', ['course' => $courseSlug]) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- LEFT SIDE: FLASHCARDS --}}
            <div class="col-lg-8 col-md-7">

                @foreach ($questions as $index => $question)
                    <div class="flashcard-form" data-index="{{ $index }}" data-id="{{ $question->id }}"
                        style="{{ $index != 0 ? 'display:none' : '' }}">

                        <div class="flashcard-container">
                            {{-- Question Section --}}
                            <div class="question-section">
                                <h5 class="mb-4">{{ $question->question }}</h5>

                                <div class="text-center mt-4">
                                    <button type="button" class="revealBtn">
                                        <i class="bi bi-eye"></i> Reveal Answer
                                    </button>
                                </div>
                            </div>

                            {{-- Answer Section --}}
                            <div class="answer-section" style="display:none">

                                @if($isPaid && $isLocked)
                                    {{-- 🔒 Free trial or unenrolled on a paid flashcard --}}
                                    <div class="text-center py-4 px-3"
                                        style="background:#fff8e1; border:2px dashed #ffc107; border-radius:12px;">
                                        <i class="bi bi-lock-fill text-warning" style="font-size:3rem;"></i>
                                        <h5 class="mt-3 mb-2">Answer is locked</h5>
                                        <p class="text-muted mb-3">
                                            This is a Premium content. Please upgrade your plan to see the answer.
                                        </p>
                                        <a href="{{ route('courses.checkout', ['course' => $courseSlug]) }}"
                                            class="btn btn-warning fw-bold">
                                            <i class="bi bi-unlock-fill"></i> Upgrade to Premium
                                        </a>
                                    </div>
                                @else
                                    {{-- ✅ Free flashcard OR fully enrolled --}}
                                    <h6>Answer:</h6>
                                    <div class="answer-content">{!! $question->answer !!}</div>

                                    <h6 class="mt-4">How well did you know this?</h6>
                                    <div class="rating-buttons">
                                        <button type="button" class="btn btn-success rateBtn" data-rating="verywell">
                                            <i class="bi bi-hand-thumbs-up"></i> Very Well
                                        </button>
                                        <button type="button" class="btn btn-warning rateBtn" data-rating="confused">
                                            <i class="bi bi-question-circle"></i> Confused
                                        </button>
                                        <button type="button" class="btn btn-danger rateBtn" data-rating="notatall">
                                            <i class="bi bi-hand-thumbs-down"></i> Not At All
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- RIGHT SIDE: PROGRESS --}}
            <div class="col-lg-4 col-md-5">
                <div class="progress-card">

                    <h5 class="mb-4">
                        Score: <span id="scorePercent">0</span>%
                    </h5>

                    <div class="mb-3">
                        <p>
                            <i class="bi bi-check-circle"></i>
                            Correct: <strong id="correctCount">{{ $quiz->correct ?? 0 }} /
                                {{ $totalOriginalQuestions }}</strong>
                        </p>
                        <p>
                            <i class="bi bi-layers"></i>
                            Remaining: <strong id="remainingCount">{{ count($remainingQuestionIds) }}</strong>
                        </p>
                        <p>
                            <i class="bi bi-arrow-repeat"></i>
                            To Review: <strong id="repeatCount">0</strong>
                        </p>
                    </div>

                    <hr>

                    <ul class="list-group status-list" id="questionStatusList"></ul>

                    <a href="{{ route('flashs.index', ['course' => $courseSlug]) }}"
                        class="btn end-session-btn w-100 mt-3">
                        <i class="bi bi-x-circle"></i> End Session
                    </a>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                let isLocked = {{ ($isPaid && $isLocked) ? 'true' : 'false' }};
                let quizId = {{ $quiz->id }};
                let totalOriginal = {{ $totalOriginalQuestions }};
                let current = 0;
                let correct = {{ $quiz->correct ?? 0 }};
                let wrong = {{ $quiz->wrong ?? 0 }};
                let maxVisited = 0;
                let questionQueue = [];

                let answersData = {!! json_encode(json_decode($quiz->answers ?? '[]', true) ?? []) !!};

                let existingAnswers;
                if (Array.isArray(answersData)) {
                    existingAnswers = answersData;
                } else if (answersData.answers) {
                    existingAnswers = answersData.answers;
                } else {
                    existingAnswers = [];
                }

                let answeredKeys = new Set(existingAnswers.map(a => a.answer_key || ''));

                let savedQueue = {!! json_encode(json_decode($quiz->question_queue ?? '[]', true) ?? []) !!};
                let remainingIds = {!! json_encode($remainingQuestionIds ?? []) !!};
                let repeatQueueIds = {!! json_encode($repeatQueueIds ?? []) !!};

                let initialQuestions = [];
                @foreach ($questions as $index => $question)
                    initialQuestions.push({
                        index: {{ $index }},
                        id: {{ $question->id }},
                        isRepeat: false
                    });
                @endforeach

                questionQueue = [];

                let remainingQuestions = [];
                let repeatQuestions = [];

                remainingIds.forEach(id => {
                    let question = initialQuestions.find(q => q.id === id);
                    if (question) {
                        remainingQuestions.push({ index: question.index, id: id, isRepeat: false });
                    }
                });

                repeatQueueIds.forEach(id => {
                    let question = initialQuestions.find(q => q.id === id);
                    if (question) {
                        repeatQuestions.push({ index: question.index, id: id, isRepeat: true });
                    }
                });

                if (repeatQuestions.length > 0 && remainingQuestions.length > 0) {
                    let interval = Math.ceil(remainingQuestions.length / repeatQuestions.length);
                    let repeatIndex = 0;

                    for (let i = 0; i < remainingQuestions.length; i++) {
                        questionQueue.push(remainingQuestions[i]);
                        if (repeatIndex < repeatQuestions.length && (i + 1) % interval === 0) {
                            questionQueue.push(repeatQuestions[repeatIndex]);
                            repeatIndex++;
                        }
                    }

                    while (repeatIndex < repeatQuestions.length) {
                        questionQueue.push(repeatQuestions[repeatIndex]);
                        repeatIndex++;
                    }
                } else {
                    questionQueue = [...remainingQuestions, ...repeatQuestions];
                }

                function getAnswerKey(queuePos, questionId) {
                    return 'pos_' + queuePos + '_q_' + questionId;
                }

                function isAnswered(queuePos, questionId) {
                    return answeredKeys.has(getAnswerKey(queuePos, questionId));
                }

                function updateCounts() {
                    let answeredInCurrentQueue = 0;
                    for (let i = 0; i <= maxVisited && i < questionQueue.length; i++) {
                        if (isAnswered(i, questionQueue[i].id)) {
                            answeredInCurrentQueue++;
                        }
                    }

                    let remaining = questionQueue.length - answeredInCurrentQueue;
                    let repeatCount = questionQueue.slice(maxVisited + 1).filter(q => q.isRepeat).length;

                    $('#remainingCount').text(remaining);
                    $('#repeatCount').text(repeatCount);

                    let percent = totalOriginal > 0 ? (correct / totalOriginal) * 100 : 0;
                    $('#scorePercent').text(percent.toFixed(0));
                    $('#correctCount').text(correct + ' / ' + totalOriginal);
                }

                function updateStatusList() {
                    let html = '';
                    for (let i = 0; i <= maxVisited && i < questionQueue.length; i++) {
                        let statusClass = i === current ? 'active-question' : '';
                        let badge = 'bg-secondary';
                        let text = 'Pending';

                        let queueItem = questionQueue[i];
                        if (!queueItem) continue;

                        if (isAnswered(i, queueItem.id)) {
                            let answer = existingAnswers.find(a => a.answer_key === getAnswerKey(i, queueItem.id));
                            if (answer) {
                                if (answer.rating === 'verywell') { badge = 'bg-success'; text = 'Correct'; }
                                else if (answer.rating === 'notatall') { badge = 'bg-danger'; text = 'Wrong'; }
                                else if (answer.rating === 'confused') { badge = 'bg-warning'; text = 'Confused'; }
                            }
                        }

                        let repeatLabel = queueItem.isRepeat ? ' <span class="badge bg-info">Repeat</span>' : '';

                        html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center ${statusClass}"
                                id="status-${i}" data-index="${i}">
                                <span>Question ${i+1} ${repeatLabel}</span>
                                <span class="badge ${badge}">${text}</span>
                            </li>
                        `;
                    }
                    $('#questionStatusList').html(html);
                }

                function showQuestion(index) {
                    current = index;

                    if (index > maxVisited) maxVisited = index;
                    if (index >= questionQueue.length) return;

                    let queueItem = questionQueue[index];
                    let originalIndex = queueItem.index;

                    $('.flashcard-form').hide();
                    let currentForm = $('.flashcard-form[data-index="' + originalIndex + '"]');
                    currentForm.show();

                    currentForm.find('.answer-section').hide();
                    currentForm.find('.revealBtn').prop('disabled', false);

                    // ✅ Only enable rate buttons if not locked
                    if (!isLocked) {
                        currentForm.find('.rateBtn').prop('disabled', false);
                    }

                    $('#currentQ').text(index + 1);
                    $('#queueInfo').text(queueItem.isRepeat ? '(Reviewing)' : '');
                    $('#prevBtn').prop('disabled', index === 0);
                    $('#nextBtn').prop('disabled', index >= questionQueue.length - 1);

                    updateStatusList();
                }

                $(document).on('click', '.status-list .list-group-item', function() {
                    let index = $(this).data('index');
                    if (index < questionQueue.length) showQuestion(index);
                });

                $('#nextBtn').click(() => {
                    if (current < questionQueue.length - 1) showQuestion(++current);
                });

                $('#prevBtn').click(() => {
                    if (current > 0) showQuestion(--current);
                });

                $('.revealBtn').click(function() {
                    $(this).closest('.flashcard-form').find('.answer-section').slideDown();
                    $(this).prop('disabled', true);
                });

                $('.rateBtn').click(function() {
                    if (isLocked) return; // 🔒 locked হলে rating কাজ করবে না

                    let form = $(this).closest('.flashcard-form');
                    let queueItem = questionQueue[current];
                    let questionId = queueItem.id;
                    let rating = $(this).data('rating');
                    let isRepeat = queueItem.isRepeat;
                    let answerKey = getAnswerKey(current, questionId);

                    $.ajax({
                        url: "{{ route('flashs.updateProgress') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            quiz_id: quizId,
                            question_id: questionId,
                            rating: rating,
                            queue_position: current,
                            is_repeat: isRepeat ? 1 : 0
                        },
                        success: function(res) {
                            correct = res.correct;
                            wrong = res.wrong;

                            answeredKeys.add(answerKey);
                            existingAnswers.push({
                                answer_key: answerKey,
                                question_id: questionId,
                                queue_position: current,
                                rating: rating,
                                is_repeat: isRepeat
                            });

                            if (rating === 'verywell') {
                                $('#status-' + current + ' .badge')
                                    .removeClass('bg-secondary bg-warning bg-danger')
                                    .addClass('bg-success').text('Correct');
                                questionQueue = questionQueue.filter((q, idx) =>
                                    !(idx > current && q.id === questionId));

                            } else if (rating === 'notatall') {
                                $('#status-' + current + ' .badge')
                                    .removeClass('bg-secondary bg-success bg-warning')
                                    .addClass('bg-danger').text('Wrong');
                                questionQueue = questionQueue.filter((q, idx) =>
                                    !(idx > current && q.id === questionId));
                                questionQueue.push({ index: queueItem.index, id: questionId, isRepeat: true });

                            } else if (rating === 'confused') {
                                $('#status-' + current + ' .badge')
                                    .removeClass('bg-secondary bg-success bg-danger')
                                    .addClass('bg-warning').text('Confused');
                                questionQueue = questionQueue.filter((q, idx) =>
                                    !(idx > current && q.id === questionId));
                                questionQueue.push({ index: queueItem.index, id: questionId, isRepeat: true });
                            }

                            $('#correctCount').text(correct);
                            updateCounts();
                            updateStatusList();

                            form.find('.rateBtn').prop('disabled', true);

                            setTimeout(() => {
                                if (res.is_complete) {
                                    showCompletionModal(totalOriginal > 0 ? (correct / totalOriginal) * 100 : 0);
                                } else if (current < questionQueue.length - 1) {
                                    showQuestion(++current);
                                } else {
                                    showCompletionModal(totalOriginal > 0 ? (correct / totalOriginal) * 100 : 0);
                                }
                            }, 1000);
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                        }
                    });
                });

                function showCompletionModal(percent) {
                    let modalHtml = `
                        <div class="modal fade" id="completionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-check-circle"></i> Session Completed!
                                        </h5>
                                    </div>
                                    <div class="modal-body text-center py-4">
                                        <div class="mb-4">
                                            <h1 class="display-1 fw-bold text-success">${percent.toFixed(0)}%</h1>
                                            <p class="text-muted">Your Final Score</p>
                                        </div>
                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <div class="p-3 bg-light rounded">
                                                    <h4 class="text-success mb-0">${correct}</h4>
                                                    <small class="text-muted">Correct</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 bg-light rounded">
                                                    <h4 class="text-danger mb-0">${wrong}</h4>
                                                    <small class="text-muted">Wrong</small>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-0">All questions answered! Great job! 🎉</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('flashs.index', ['course' => $courseSlug]) }}"
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-arrow-left"></i> Back to Flash Cards
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#completionModal').remove();
                    $('body').append(modalHtml);
                    let modal = new bootstrap.Modal(document.getElementById('completionModal'));
                    modal.show();
                }

                if (questionQueue.length > 0) {
                    showQuestion(0);
                    updateCounts();
                    updateStatusList();
                } else {
                    alert('No questions available!');
                    window.location.href = "{{ route('flashs.index', ['course' => $courseSlug]) }}";
                }
            });
        </script>
    @endpush
@endsection
