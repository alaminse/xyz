@extends('frontend.dashboard.app')
@section('title', 'Review Questions')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <style>
        .list-group { background-color: transparent !important; }
        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
@endsection
@section('content')
    <div class="card topic-card">
        <div class="card-body">
            <div class="card-header bg-transparent border-0">
                <h4 class="card-title text-center mb-4">
                    <i class="bi bi-journal-check"></i> Review Questions
                </h4>
                <h6 class="text-center mb-4">{{ $course->name }}</h6>
            </div>
            <hr>
            @include('backend.includes.message')

            <p class="text-center text-white-50 mb-4">Select a chapter or lesson — you will see the already solved SBA and MCQ questions together.
            </p>

            <div class="accordion" id="reviewAccordion">
                @forelse ($chapters as $key => $chapter)
                    <div class="accordion-item bg-transparent border-0 mb-2">
                        <h2 class="accordion-header" id="heading-{{ $key }}">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $key }}"
                                    aria-expanded="false"
                                    aria-controls="collapse-{{ $key }}">
                                <i class="bi bi-folder2-open me-2"></i> {{ $chapter->name }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $key }}" class="accordion-collapse collapse"
                             aria-labelledby="heading-{{ $key }}"
                             data-bs-parent="#reviewAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    @forelse ($chapter->lessons as $lesson)
                                        <li class="list-group-item">
                                            <a href="{{ route('review_questions.show', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}">
                                                <i class="bi bi-play-circle me-2"></i>{{ $lesson->name }}
                                            </a>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-white-50">
                                            <i class="bi bi-info-circle me-2"></i>No lessons available
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>No chapters available for this course.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
