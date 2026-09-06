@extends('frontend.dashboard.app')
@section('title', 'Review Questions')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/summernote_show.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mcq.css') }}">
    <style>
        .option-correct { background: #d4edda !important; border-color: #28a745 !important; font-weight: 600; }
        .option-wrong   { background: #f8d7da !important; border-color: #dc3545 !important; }
        .option-item {
            border: 2px solid #e0e0e0;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 12px;
        }
        .explanation-box {
            background: #e7f3ff; border-left: 4px solid #2196f3;
            padding: 15px; border-radius: 8px; margin-top: 15px;
        }
        .note-box {
            background: #fff3cd; border-left: 4px solid #ffc107;
            padding: 15px; border-radius: 8px; margin-top: 15px;
        }
        .sidebar-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px; padding: 15px; position: sticky; top: 20px;
        }
        .question-link {
            background: rgba(255,255,255,0.1);
            padding: 10px; border-radius: 8px; margin-bottom: 8px;
            cursor: pointer; border: 2px solid transparent; transition: all .3s ease;
        }
        .question-link:hover { background: rgba(255,255,255,0.2); }
        .question-link.active { background: rgba(255,255,255,0.3); border-color: #fff; }
        .score-badge {
            background: rgba(255,255,255,0.2); padding: 15px;
            border-radius: 10px; text-align: center; margin-bottom: 20px;
        }
        .score-badge h4 { color: #fff; font-weight: bold; font-size: 2rem; margin: 0; }
        #typeTabs .nav-link { cursor: pointer; }
    </style>
@endsection
@section('content')

    {{-- Header --}}
    <div class="card mb-3">
        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
            <div class="flex-grow-1">
                <h5 class="card-title mb-2">Review Questions</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">{{ $course->name }}</li>
                        <li class="breadcrumb-item">{{ $chapter->name }}</li>
                        @if ($lesson)
                            <li class="breadcrumb-item active" aria-current="page">{{ $lesson->name }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <a href="{{ route('review_questions.index', $course->slug) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Main panel --}}
        <div class="col-sm-12 col-md-8">

            {{-- Type filter tabs --}}
            <ul class="nav nav-pills mb-3" id="typeTabs">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-filter="all">
                        All ({{ $totalCount }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-filter="sba">
                        SBA ({{ $sbaItems->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-filter="mcq">
                        MCQ ({{ $mcqItems->count() }})
                    </button>
                </li>
            </ul>

            @if ($allItems->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    You haven't solved any SBA or MCQ questions in this chapter yet.
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <h6 class="mb-0">
                        Question <span id="current-question-number">1</span> of
                        <span id="total-question-number">{{ $allItems->count() }}</span>
                    </h6>
                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>
                </div>

                <div id="review-container">
                    @foreach ($allItems as $index => $item)
                        @php
                            // Locked only when this specific module is paid AND the user is on FREETRIAL/not enrolled
                            $itemLocked = ($item['isPaid'] ?? false) && $isLocked;
                        @endphp
                        <div class="review-question" data-type="{{ $item['type'] }}" data-index="{{ $index }}"
                             style="{{ $index == 0 ? '' : 'display:none;' }}">
                            <div class="question-card">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary text-uppercase">{{ $item['type'] }}</span>
                                    @if (! $itemLocked)
                                        <span class="badge {{ ($item['is_correct'] ?? false) ? 'bg-success' : 'bg-danger' }}">
                                            @if ($item['is_correct'] ?? false)
                                                <i class="bi bi-check-circle-fill me-1"></i> Correct
                                            @else
                                                <i class="bi bi-x-circle-fill me-1"></i> Wrong
                                            @endif
                                        </span>
                                    @endif
                                </div>

                                <h5 class="mb-4">{!! $item['question'] ?? 'Question not available' !!}</h5>

                                @if ($item['type'] === 'sba')
                                    {{-- ===== SBA: single correct_option / selected_option ===== --}}
                                    <ul class="list-unstyled">
                                        @foreach (['option1', 'option2', 'option3', 'option4', 'option5'] as $opt)
                                            @if (!empty($item[$opt]))
                                                @php
                                                    $isCorrect  = ($item['correct_option'] ?? null) === $opt;
                                                    $isSelected = ($item['selected_option'] ?? null) === $opt;
                                                @endphp
                                                <li class="option-item {{ (!$itemLocked && $isCorrect) ? 'option-correct' : ((!$itemLocked && $isSelected) ? 'option-wrong' : '') }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            @if ($isSelected)
                                                                <i class="bi bi-hand-index me-2 {{ (!$itemLocked && $isCorrect) ? 'text-success' : 'text-danger' }}"></i>
                                                            @endif
                                                            {{ $item[$opt] }}
                                                        </span>
                                                        @if (! $itemLocked)
                                                            @if ($isCorrect)
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            @elseif ($isSelected)
                                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    {{-- ===== MCQ: multiple true/false sub-options ===== --}}
                                    @foreach (($item['answers'] ?? []) as $ans)
                                        @php
                                            $selected = $ans['selected'] ?? null;
                                            $correct  = $ans['correct'] ?? null;
                                            $subCorrect = $selected !== null && (int) $selected === (int) $correct;
                                        @endphp
                                        <div class="option-item {{ $itemLocked ? '' : ($selected === null ? '' : ($subCorrect ? 'option-correct' : 'option-wrong')) }}">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                    <strong>{{ $ans['option_text'] ?? '' }}</strong>
                                                </div>
                                                <div class="col-12 col-md-6 text-md-end">
                                                    @if (! $itemLocked)
                                                        @if ($subCorrect)
                                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Correct</span>
                                                        @elseif ($selected !== null)
                                                            <span class="badge bg-danger">
                                                                You chose: <strong>{{ $selected ? 'True' : 'False' }}</strong> —
                                                                Correct: <strong>{{ $correct ? 'True' : 'False' }}</strong>
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Not answered</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($itemLocked)
                                    <div class="text-center py-4 px-3 mt-3"
                                         style="background:#fff8e1; border:2px dashed #ffc107; border-radius:12px;">
                                        <i class="bi bi-lock-fill text-warning" style="font-size:2.5rem;"></i>
                                        <h5 class="mt-3 mb-2">Answer is locked</h5>
                                        <p class="text-muted mb-3">
                                            This is Premium content. Please upgrade your plan to see the answer.
                                        </p>
                                        <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                           class="btn btn-warning fw-bold">
                                            <i class="bi bi-unlock-fill"></i> Upgrade to Premium
                                        </a>
                                    </div>
                                @else
                                    @if (!empty($item['explain']))
                                        <div class="explanation-box">
                                            <h6><i class="bi bi-lightbulb text-warning"></i> Explanation</h6>
                                            <div>{!! $item['explain'] !!}</div>
                                        </div>
                                    @endif

                                    @if (!empty($item['note_description']))
                                        <div class="note-box">
                                            <h6><i class="bi bi-journal-text text-info"></i>
                                                {{ $item['note_title'] ?? 'Related Note' }}
                                            </h6>
                                            <div>{!! $item['note_description'] !!}</div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-sm-12 col-md-4">
            <div class="sidebar-card">
                <div class="score-badge">
                    <h6 class="text-white mb-2">Overall Score</h6>
                    <h4>{{ $totalCount > 0 ? number_format(($correctCount / $totalCount) * 100, 2) : '0.00' }}%</h4>
                    <small class="text-white">{{ $correctCount }} Correct | {{ $totalCount }} Total</small>
                </div>

                @if ($allItems->isNotEmpty())
                    <h6 class="text-white mb-3"><i class="bi bi-list-check me-2"></i>All Questions</h6>
                    <div style="max-height: 500px; overflow-y: auto;">
                        @foreach ($allItems as $index => $item)
                            <div class="question-link" data-index="{{ $index }}" data-type="{{ $item['type'] }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">
                                        <span class="badge bg-dark text-uppercase me-1">{{ $item['type'] }}</span>
                                        <span class="d-inline-block text-truncate" style="max-width: 120px;">
                                            Q{{ $index + 1 }}. {{ strip_tags($item['question'] ?? '') }}
                                        </span>
                                    </span>
                                    @if (($item['isPaid'] ?? false) && $isLocked)
                                        <i class="bi bi-lock-fill text-warning"></i>
                                    @elseif ($item['is_correct'] ?? false)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                const totalQuestions = $('.review-question').length;
                let currentIndex = 0;
                let activeFilter = 'all';

                function visibleQuestions() {
                    return $('.review-question').filter(function () {
                        return activeFilter === 'all' || $(this).data('type') === activeFilter;
                    });
                }

                function showQuestion(index) {
                    const items = visibleQuestions();
                    if (index < 0 || index >= items.length) return;

                    $('.review-question').hide();
                    items.eq(index).show();

                    currentIndex = index;
                    $('#current-question-number').text(currentIndex + 1);
                    $('#total-question-number').text(items.length);
                    $('#previous-button').prop('disabled', currentIndex === 0);
                    $('#next-button').prop('disabled', currentIndex === items.length - 1);

                    $('.question-link').removeClass('active');
                    const globalIdx = items.eq(index).data('index');
                    $(`.question-link[data-index="${globalIdx}"]`).addClass('active');
                }

                $('#next-button').on('click', () => showQuestion(currentIndex + 1));
                $('#previous-button').on('click', () => showQuestion(currentIndex - 1));

                $('.question-link').on('click', function () {
                    const globalIndex = $(this).data('index');
                    const items = visibleQuestions();

                    let idx = -1;
                    items.each(function (i) {
                        if ($(this).data('index') === globalIndex) idx = i;
                    });
                    if (idx !== -1) showQuestion(idx);
                });

                $('#typeTabs button').on('click', function () {
                    $('#typeTabs button').removeClass('active');
                    $(this).addClass('active');
                    activeFilter = $(this).data('filter');

                    $('.question-link').each(function () {
                        const show = activeFilter === 'all' || $(this).data('type') === activeFilter;
                        $(this).toggle(show);
                    });

                    showQuestion(0);
                });

                $(document).on('keydown', function (e) {
                    if (e.key === 'ArrowRight') showQuestion(currentIndex + 1);
                    if (e.key === 'ArrowLeft') showQuestion(currentIndex - 1);
                });

                if (totalQuestions > 0) showQuestion(0);
            });
        </script>
    @endpush
@endsection
