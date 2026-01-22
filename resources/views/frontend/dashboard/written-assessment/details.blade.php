@extends('frontend.dashboard.app')
@section('title', 'Written Assessment Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')

    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Written Assessment Topic</h5>
                        <a class="btn btn-sm btn-warning" href="{{ route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug]) }}">Back</a>
                    </div>

                    <section id="info-utile" class="">
                        @isset($assessment)
                            @include('frontend.dashboard.written-assessment.partial')
                        @endisset
                    </section>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const query = @json($query ?? ''); // Pass the query from the server to JavaScript
                if (query) {
                    // Target the element that contains the note's description
                    const noteDescription = document.querySelector('.note-description'); // Adjust if necessary
                    const noteTitle = document.querySelector('.note-title'); // Adjust if necessary

                    if (noteDescription || noteTitle) {
                        const regex = new RegExp(query, 'gi'); // Create a regex for case-insensitive matching
                        noteDescription.innerHTML = noteDescription.innerHTML.replace(regex, match =>
                            `<span style="background: #F1A909; color: white">${match}</span>`);
                        noteTitle.innerHTML = noteTitle.innerHTML.replace(regex, match =>
                            `<span style="background: #F1A909; color: white">${match}</span>`);
                    }
                }
            });
        </script>
    @endpush
@endsection
