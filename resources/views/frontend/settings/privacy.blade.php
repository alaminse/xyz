@extends('layouts.frontend')
@section('title', 'Privacy Policy')
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
