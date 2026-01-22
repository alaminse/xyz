@extends('frontend.dashboard.app')
@section('title', 'Notes')
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
        <div class="d-flex justify-content-center">
            <div class="col-sm-12 col-md-6">
                <form action="{{ route('notes.search') }}" method="POST">
                    @csrf

                    <input type="hidden" name="course_slug" value="{{ $course->slug }}">
                    <div class="input-group mb-3">
                        <input type="text" name="query" class="form-control" placeholder="Search notes..."
                            aria-label="Search notes" aria-describedby="button-addon2"
                            style="background: none; color: aliceblue; box-shadow: none !important; border-color: white"
                            required>
                        <button class="btn button-yellow" type="submit" id="button-addon2" style="height: 38px">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Notes Topic</h5>
                    </div>
                    <section id="info-utile" class="">
                        <div class="container">
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
                                                    {{-- <li class="list-group-item">A fourth item</li> --}}
                                                    @foreach ($topic->lessons as $key => $lesson)
                                                        <li class="list-group-item">
                                                            <a
                                                                href="{{ route('notes.details', ['course' => $course->slug, 'chapter' => $topic->slug, 'lesson' => $lesson->slug]) }}">{{ $lesson->name }}</a>
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
                        <h5 class="card-title">Recent</h5>
                    </div>
                    @foreach ($latest as $item)
                        <div class="mt-3 p-3 topic-card">
                            <div class="d-flex">
                                <h5 class="flex-grow-1">{{ $item->title }}</h5>
                                <a href="{{ route('notes.single.details', $item->slug) }}"
                                    class="btn btn-sm button-yellow">Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
