@extends('layouts.backend')
@section('title', 'Assessment Details - ' . $progress->user->name)

@section('css')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .summary-card .label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .summary-card .value {
        font-size: 24px;
        font-weight: bold;
        color: #4A90E2;
    }

    .result-badge {
        display: inline-block;
        border-radius: 25px;
        font-weight: bold;
        font-size: 20px;
    }

    .result-badge.pass {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .result-badge.fail {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .question-block {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .question-header {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 4px solid #4A90E2;
    }

    .question-text {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        margin: 15px 0;
    }

    .option-box {
        padding: 12px 15px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        background: white;
        font-size: 14px;
        transition: all 0.2s;
    }

    .option-box.correct {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-color: #28a745;
        color: #155724;
        font-weight: 600;
    }

    .option-box.incorrect {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-color: #dc3545;
        color: #721c24;
        font-weight: 600;
    }

    .explanation-section {
        background: #f8f9fa;
        border-left: 4px solid #4A90E2;
        padding: 15px;
        margin-top: 15px;
        border-radius: 4px;
    }

    .explanation-section h6 {
        color: #4A90E2;
        font-weight: 600;
    }

    .user-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .avatar-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-weight: bold;
        border-radius: 50%;
    }

    .stat-box {
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .stat-box.border-primary { border: 2px solid #007bff; }
    .stat-box.border-success { border: 2px solid #28a745; }
    .stat-box.border-info { border: 2px solid #17a2b8; }
    .stat-box.border-warning { border: 2px solid #ffc107; }

    @media print {
        .no-print { display: none; }
        .question-block { page-break-inside: avoid; }
    }

    @media screen and (max-width: 768px) {
        .options-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="page-header no-print">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fa fa-file-text-o"></i> Assessment Details
                </h2>
                <p class="mb-0">
                    <i class="fa fa-user"></i> {{ $progress->user->name }} |
                    <i class="fa fa-book"></i> {{ $assessment->name }}
                </p>
            </div>
            <div class="col-md-4 text-right">
                <button onclick="window.print()" class="btn btn-success btn-sm mr-2">
                    <i class="fa fa-print"></i> Print
                </button>
                <a href="{{ route('admin.assessments.leaderboard', $assessment->id) }}" class="btn btn-light btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Leaderboard
                </a>
            </div>
        </div>
    </div>

    {{-- Student Information Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            @if(isset($progress->user->profile_photo_path) && $progress->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $progress->user->profile_photo_path) }}"
                                     alt="{{ $progress->user->name }}"
                                     class="user-avatar-large">
                            @else
                                <div class="avatar-placeholder user-avatar-large mx-auto">
                                    <span style="font-size: 40px;">{{ strtoupper(substr($progress->user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-3">{{ $progress->user->name }}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Email:</strong> {{ $progress->user->email }}</p>
                                    <p class="mb-2"><strong>Assessment:</strong> {{ $assessment->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Rank:</strong> <span class="badge badge-primary">#{{ $progress->rank }}</span></p>
                                    <p class="mb-2"><strong>Submitted:</strong> {{ $progress->created_at ? $progress->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-box border-primary shadow-sm">
                <div class="label text-muted">Total Marks</div>
                <div class="value text-primary">{{ $assessment->total_marks }}</div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-box border-success shadow-sm">
                <div class="label text-muted">Achieved Marks</div>
                <div class="value text-success">{{ $progress->achive_marks }}</div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-box border-info shadow-sm">
                <div class="label text-muted">Percentage</div>
                <div class="value text-info">{{ $progress->percentage }}%</div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-box border-warning shadow-sm">
                <div class="label text-muted">Result</div>
                <div class="value">
                    @if($progress->achive_marks >= $assessment->total_marks * 0.7)
                        <span class="result-badge pass px-4">PASS</span>
                    @else
                        <span class="result-badge fail px-4">FAIL</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="summary-card">
                <div class="label">Cut Mark (70%)</div>
                <div class="value text-danger">{{ $assessment->total_marks * 0.7 }}</div>
            </div>
        </div>
    </div>
    {{-- Questions Details --}}
    @if($details && is_array($details) && count($details) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-list"></i> Question-wise Details</h5>
                </div>
                <div class="card-body">
                    @foreach($details as $index => $question)
                    <div class="question-block">
                        <div class="question-header">
                            <p class="question-text">Q{{ $index + 1 }}. {{ $question['question'] ?? 'N/A' }}</p>
                        </div>

                        @if(isset($question['question_type']))
                            @if($question['question_type'] == 'sba')
                                @php
                                    $correctOption = $question['options']['correct_option'] ?? null;
                                    $userAnswer = $question['options']['user_option'] ?? null;
                                    $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                                @endphp

                                <div class="options-grid">
                                    @for($i = 1; $i <= 5; $i++)
                                        @php
                                            $optionKey = 'option' . $i;
                                            $optionValue = $question['options'][$optionKey] ?? null;

                                            $class = '';
                                            $indicator = '';
                                            if ($userAnswer === $optionKey && $userAnswer === $correctOption) {
                                                $class = 'correct';
                                                $indicator = ' ✓';
                                            } elseif ($userAnswer === $optionKey && $userAnswer != $correctOption) {
                                                $class = 'incorrect';
                                                $indicator = ' ✗';
                                            } elseif ($correctOption === $optionKey) {
                                                $class = 'correct';
                                                $indicator = ' ✓';
                                            }
                                        @endphp

                                        @if($optionValue)
                                            <div class="option-box {{ $class }}">
                                                <strong>{{ $optionLabels[$i-1] }}.</strong> {{ $optionValue }}{{ $indicator }}
                                            </div>
                                        @endif
                                    @endfor
                                </div>

                            @elseif($question['question_type'] == 'mcq')
                                @php
                                    $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                                @endphp

                                <div class="options-grid">
                                    @for($i = 1; $i <= 5; $i++)
                                        @php
                                            $optionKey = 'option' . $i;
                                            $userOptionKey = 'user_option' . $i;
                                            $answerOption = 'answers' . $i;
                                            $optionValue = $question['options'][$optionKey] ?? null;
                                            $userOptionValue = $question['options'][$userOptionKey] ?? null;
                                            $answerOptionValue = $question['options'][$answerOption] ?? null;

                                            $class = '';
                                            $indicator = '';
                                            $userText = '';
                                            $correctText = '';

                                            if ($answerOptionValue == 1) {
                                                $correctText = ' [Correct: True]';
                                            } elseif ($answerOptionValue == 0) {
                                                $correctText = ' [Correct: False]';
                                            }

                                            if($userOptionValue !== null && $answerOptionValue !== null){
                                                $userOptionValue = $userOptionValue == 'false' ? 0 : 1;
                                                $userText = $userOptionValue == 1 ? ' (Your: True)' : ' (Your: False)';

                                                if ($userOptionValue == $answerOptionValue) {
                                                    $class = 'correct';
                                                    $indicator = ' ✓';
                                                } else {
                                                    $class = 'incorrect';
                                                    $indicator = ' ✗';
                                                }
                                            } elseif ($answerOptionValue == 1) {
                                                $class = 'correct';
                                                $indicator = ' ✓';
                                            }
                                        @endphp

                                        @if($optionValue)
                                            <div class="option-box {{ $class }}">
                                                <div><strong>{{ $optionLabels[$i-1] }}.</strong> {{ $optionValue }}</div>
                                                @if($userText)
                                                    <div style="font-size: 12px; margin-top: 5px;">
                                                        <span style="font-weight: bold;">{{ $userText }}{{ $indicator }}</span>
                                                    </div>
                                                @endif
                                                @if($correctText && $class == 'incorrect')
                                                    <div style="font-size: 12px; color: #155724;">
                                                        <strong>{{ $correctText }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                        @endif

                        @if(!empty($question['explanation']))
                            <div class="explanation-section">
                                <h6><u>Explanation</u></h6>
                                {!! $question['explanation'] !!}
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> No question details available.
    </div>
    @endif

</div>
@endsection
