@extends('frontend.dashboard.app')
@section('title', 'Flashcard')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
@endsection
@section('content')

    @push('scripts')
        <script src="{{ asset('frontend/js/dashboard.js') }}"></script>
    @endpush
@endsection
