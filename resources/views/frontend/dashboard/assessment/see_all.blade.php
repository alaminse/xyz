@extends('frontend.dashboard.app')
@section('title', 'Assessments')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <div
                            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2">Assessments</h5>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">{{ $cour->name }}</li>
                                        @isset($chap)
                                            <li class="breadcrumb-item">{{ $chap->name }}</li>
                                        @endisset
                                        @isset($less)
                                            <li class="breadcrumb-item active text-light" aria-current="page">{{ $less->name }}</li>
                                        @endisset
                                    </ol>
                                </nav>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('assessments.index', $cour->slug) }}" class="btn button-yellow btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Course
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (isset($assessments) && $assessments->isNotEmpty())
                        @foreach ($assessments as $key => $assessment)
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>{{ $assessment->name }}</h6>
                                        </div>
                                        <a href="{{ route('assessments.exam', ['assessment' => $assessment->slug, 'course' => $cour->slug]) }}"
                                            class="btn button-yellow btn-sm py-2 px-4 align-self-start">
                                            Continue
                                        </a>
                                    </div>

                                    <ul class="list-group mt-2">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Start Date
                                            <span class="badge text-bg-primary rounded-pill">
                                                {{ $assessment->start_date_time ? \Carbon\Carbon::parse($assessment->start_date_time)->format('F j, Y, g:i a') : 'Not Set' }}
                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            End Date
                                            <span class="badge text-bg-warning rounded-pill">
                                                {{ $assessment->end_date_time ? \Carbon\Carbon::parse($assessment->end_date_time)->format('F j, Y, g:i a') : 'Not Set' }}
                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Total Marks
                                            <span
                                                class="badge text-bg-primary rounded-pill">{{ $assessment->total_marks }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Time Duration
                                            <span class="badge text-bg-info rounded-pill">{{ $assessment->time }}
                                                min</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Type
                                            <span
                                                class="badge text-bg-{{ $assessment->isPaid ? 'warning' : 'success' }} rounded-pill">
                                                {{ $assessment->isPaid ? 'Paid' : 'Free' }}
                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Status
                                            <span
                                                class="badge text-bg-{{ \App\Enums\Status::from($assessment->status)->message() }} rounded-pill text-light">
                                                {{ \App\Enums\Status::from($assessment->status)->title() }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if (isset($progress) && $progress->isNotEmpty())
                        @foreach ($progress as $item)
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4>{{ $item->assessment->name }}</h4>
                                        <a href="{{ route('assessments.show', $item->slug) }}" target="__blank"
                                            class="btn button-yellow btn-sm">Show</a>
                                    </div>

                                    <p>
                                        <span>Total: {{ (int) $item->total_marks }} Marks</span>
                                        <span style="color: #114bfa; margin-left: 30px">Achieved: {{ $item->achive_marks }}
                                            Marks</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
