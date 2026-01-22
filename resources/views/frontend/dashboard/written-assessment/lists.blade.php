@extends('frontend.dashboard.app')
@section('title', 'Written Assessment Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Written Assessment Topic</h5>
                    <a class="btn btn-sm btn-warning" href="{{ route('writtens.index', ['course' => $course_slug]) }}">Back</a>
                </div>
                <section id="info-utile" class="">
                    <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>
                    @isset($written)
                    <div id="notes-container">
                        @foreach ($written as $key => $assessment)
                        <div class="card mb-2">
                            <div class="card-body mb-1 p-2 d-flex note-item">
                                <h5 class="flex-grow-1 note-title">{{ $assessment->question }}</h5>
                                <a href="{{ route('writtens.single.details', ['slug' => $assessment->slug, 'query' => $query ?? null]) }}" class="btn btn-sm button-yellow">Details</a>
                            </div>
                            </div>
                        @endforeach
                    </div>
                    @endisset
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const query = @json($query ?? '');
        if (query) {
            const notes = document.querySelectorAll('.note-item .note-title');
            notes.forEach(note => {
                const regex = new RegExp(query, 'gi');
                note.innerHTML = note.textContent.replace(regex, match => `<span style="color: #F1A909;">${match}</span>`);
            });
        }
    });
</script>

@endsection
