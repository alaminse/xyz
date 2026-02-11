@extends('layouts.frontend')
@section('title', 'Terms And Conditions')
@section('content')
    <section class="about-us">
        <div class="container" style="overflow: hidden">
        @php
            $data = json_decode($value, true);
        @endphp

        @if (!empty($data['description']))
            {!! $data['description'] !!}
        @endif
        </div>
    </section>
@endsection
