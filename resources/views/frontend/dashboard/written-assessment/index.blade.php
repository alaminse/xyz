@extends('frontend.dashboard.app')
@section('title', 'Written Assessment')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <style>
        .button-yellow:hover,
        .button-white:hover {
            color: white;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-bookmark-star"></i> {{ $course->name }} Topics
                        </h4>
                    </div>
                    @include('backend.includes.message')
                    <div class="accordion" id="accordionExample">
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
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse ($chapter->lessons as $lesson)
                                                <li class="list-group-item">
                                                    <a href="{{ route('writtens.details', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}">
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
                                <i class="bi bi-info-circle me-2"></i>No topics available for this course.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-clock-history"></i> Recent Progress
                        </h4>
                    </div>
                    @forelse ($latest as $item)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex">
                                <h5 class="flex-grow-1">{{ $item->question }}</h5>
                                <a href="{{ route('writtens.single.details', $item->slug) }}"
                                    class="btn btn-sm button-yellow">Details</a>
                            </div>
                        </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>No progress yet. Start practicing to see your results here!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
