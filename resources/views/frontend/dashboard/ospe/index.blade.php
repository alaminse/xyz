@extends('frontend.dashboard.app')
@section('title', 'OSPE Station')
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
                    <div class="card-header">
                        <h5 class="card-title">OSPE Station</h5>
                    </div>
                    <section id="info-utile" class="">
                        <div class="container">
                            <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>

                            <div class="accordion" id="accordionExample">
                                @foreach ($chapters as $key => $topic)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $key }}">
                                            <a class="accordion-button collapsed" href="#" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-{{ $key }}" aria-expanded="false"
                                                aria-controls="collapse-{{ $key }}">
                                                {{ $topic->name }}
                                            </a>
                                        </h2>
                                        <div id="collapse-{{ $key }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading-{{ $key }}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    @foreach ($topic->lessons as $key => $lesson)
                                                        <li class="list-group-item">
                                                            <a href="{{ route('ospes.test', ['course' => $course->slug, 'chapter' => $topic->slug, 'lesson' => $lesson->slug]) }}">{{ $lesson->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Recently Added OSPE</h5>
                    </div>
                    <ul class="list-group">
                        @foreach ($recentOspeStations as $station)
                            <li class="list-group-item">
                                <a href="{{ route('ospes.test', ['course' => $course->slug, 'chapter' => $station->chapter->slug, 'lesson' => $station->lesson->slug]) }}">
                                    {{ $station->chapter->name }} - {{ $station->lesson->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
