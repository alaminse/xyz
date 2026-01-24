@extends('frontend.dashboard.app')
@section('title', 'Mock Viva')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <style>
        .button-yellow:hover,
        .button-white:hover {
            color: white;
     
        .list-group {
            background-color: transparent !important;
        }

        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
</style>
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Mock Viva</h5>
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
                                                    {{-- <li class="list-group-item">A fourth item</li> --}}
                                                    @foreach ($topic->lessons as $key => $lesson)
                                                        <li class="list-group-item">
                                                            <a href="{{ route('mockvivas.test', ['course' => $course->slug, 'chapter' => $topic->slug, 'lesson' => $lesson->slug]) }}">{{ $lesson->name }}</a>
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
        <div class="col-sm-12 col-md-6 col-lg-7">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Recent</h5>
                    </div>
                    @forelse ($progress as $item)
                        <div class="sba-item">
                            <div class="sba-item-header">
                                <h5>{{ $item->chapter->name }} / {{ $item->lesson->name }}</h5>
                                <a href="{{ route('mockvivas.test', ['course' => $course->slug, 'chapter' => $item->chapter->slug, 'lesson' => $item->lesson->slug]) }}"
                                    class="btn btn-primary">Re-Test</a>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $item->status ? 100 : 0 }}%;"
                                    aria-valuenow="{{ $item->status ? 100 : 0 }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $item->status ? 100 : 0 }}%</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">No recent activity</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
