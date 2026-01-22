@extends('frontend.dashboard.app')
@section('title', 'Assessments')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Assessment Topic</h5>
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
                                                    <a href="{{ route('assessments.by.chapter', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}">
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
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title text-light">Recent</h5>
                    </div>
                    @foreach ($progress as $item)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                      <li class="breadcrumb-item">{{$item->assessment->name}}</li>
                                    </ol>
                                </nav>
                                <div style="min-width: 150px">
                                    <a href="{{ route('assessments.show', $item->slug )}}" target="__blank" class="btn button-yellow btn-sm">Show</a>
                                    <a href="{{ route('assessments.rank', $item->assessment->slug )}}" class="btn button-yellow btn-sm">Rank</a>
                                </div>
                            </div>
                            <p>
                                <span>Total: {{(int)$item->total_marks }} Marks</span>
                                <span style="color: #114bfa; margin-left: 30px">Achive: {{$item->achive_marks }} Marks</span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                    @if(count($progress) > 0)
                        <a href="{{ route('assessments.see.all', $course->slug) }}" class="btn button-yellow btn-sm mt-3">See All</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush
@endsection
