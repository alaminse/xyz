@extends('frontend.dashboard.app')
@section('title', 'Flash Card')

@section('css')
<link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
<style>
        .list-group {
            background-color: transparent !important;
        }

        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
</style>
@endsection

@section('content')
<div class="row">

    {{-- LEFT : Flash Card Topics --}}
    <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title text-white text-center mb-4">
                        <i class="bi bi-layers"></i> Flash Card Topics
                    </h4>
                </div>

                <div class="accordion" id="flashAccordion">
                    @forelse ($chapters as $key => $chapter)
                        <div class="accordion-item bg-transparent border-0 mb-2">
                            <h2 class="accordion-header" id="heading-{{ $key }}">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $key }}">
                                    <i class="bi bi-folder2-open me-2"></i>
                                    {{ $chapter->name }}
                                </button>
                            </h2>

                            <div id="collapse-{{ $key }}"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#flashAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        @forelse ($chapter->lessons as $lesson)
                                            <li class="list-group-item">
                                                <a href="{{ route('flashs.test', [
                                                    'course' => $course->slug,
                                                    'chapter' => $chapter->slug,
                                                    'lesson' => $lesson->slug
                                                ]) }}">
                                                    <i class="bi bi-play-circle me-2"></i>
                                                    {{ $lesson->name }}
                                                </a>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-white-50">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No lessons available
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No flash card topics available.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT : Recent Progress --}}
    <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
        <div class="card"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 15px;">
            <div class="card-body">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title text-white text-center mb-4">
                        <i class="bi bi-clock-history"></i> Recent Flash Progress
                    </h4>
                </div>

                @forelse ($progress as $item)

                    <div class="card mt-3">
                        <div class="card-body">

                            {{-- Breadcrumb --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 bg-transparent">
                                        <li class="breadcrumb-item">
                                            <small>
                                                <i class="bi bi-book me-1"></i>
                                                {{ $item->getCourseName() }}
                                            </small>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <small>
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $item->getChapterName() }}
                                            </small>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <small">
                                                <i class="bi bi-file-text me-1"></i>
                                                {{ $item->getLessonName() }}
                                            </small>
                                        </li>
                                    </ol>
                                </nav>

                                <a href="{{ route('flashs.review', ['slug' => $item->slug]) }}"
                                class="btn btn-sm btn-light" style="width: 115px">
                                    <i class="bi bi-eye me-1"></i> Review
                                </a>

                            </div>

                            {{-- Progress calculation --}}
                            @php
                                $percent = $item->total > 0
                                    ? ($item->correct / $item->total) * 100
                                    : 0;

                                $barClass = $percent >= 75 ? 'bg-success' :
                                            ($percent >= 50 ? 'bg-warning' :
                                            ($percent >= 25 ? 'bg-info' : 'bg-danger'));
                            @endphp

                            {{-- Stats --}}
                            <div class="d-flex justify-content-between mb-2 text-white">
                                <small>
                                    <i class="bi bi-check-circle"></i>
                                    Correct: {{ $item->correct }}/{{ $item->total }}
                                </small>
                                <small>
                                    <i class="bi bi-x-circle"></i>
                                    Wrong: {{ $item->wrong }}
                                </small>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $barClass }}"
                                     style="width: {{ $percent }}%">
                                    {{ number_format($percent, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        No flash card progress yet. Start learning now!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
