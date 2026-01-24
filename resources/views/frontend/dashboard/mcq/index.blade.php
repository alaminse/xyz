@extends('frontend.dashboard.app')
@section('title', 'Mcq')
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
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-center">
                            <i class="bi bi-bookmark-star"></i> Mcq Topics
                        </h4>
                        <h6 class="text-center mb-4">{{ $course->name }}</h6>
                    </div>
                    <hr>
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
                                                    <a href="{{ route('mcqs.test', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}">
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
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-clock-history"></i> Recent Progress
                        </h4>
                    </div>
                    @forelse ($progress as $item)

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 bg-transparent">
                                            <li class="breadcrumb-item">
                                                <small class="">
                                                    <i class="bi bi-book me-1"></i>{{ $item->getCourseName() }}
                                                </small>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <small class="">
                                                    <i class="bi bi-folder me-1"></i>{{ $item->getChapterName() }}
                                                </small>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <small class="text-mute">
                                                    <i class="bi bi-file-text me-1"></i>{{ $item->getLessonName() }}
                                                </small>
                                            </li>
                                        </ol>
                                    </nav>
                                    <a href="{{ route('mcqs.review', $item->slug) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </a>
                                </div>

                                @php
                                    // $progressItem = $item->total > 0 ? ($item->correct / $item->total) * 100 : 0;

                                    // ✅ CORRECT
                                    $progressItem = $item->progress_cut > 0 ? ($item->correct / ($item->total * 5)) * 100 : 0;
                                    // এটা total answered options দিয়ে ভাগ করছে
                                    $progressItemClass = $progressItem >= 75 ? 'bg-success' :
                                                        ($progressItem >= 50 ? 'bg-warning' :
                                                        ($progressItem >= 25 ? 'bg-info' : 'bg-danger'));
                                @endphp

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $progressItemClass }}"
                                         role="progressbar"
                                         style="width: {{ $progressItem }}%;"
                                         aria-valuenow="{{ $progressItem }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ number_format($progressItem, 0) }}% ({{ $item->correct }}/{{ $item->total * 5 }})
                                    </div>
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
@endsection
