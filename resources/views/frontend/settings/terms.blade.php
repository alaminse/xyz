@extends('layouts.frontend')
@section('title', 'Terms And Conditions')
@section('content')
<section class="about-us">
    <div class="container" style="overflow: hidden">
        @if (isset($value) && !empty($value))
            {!! $value !!}
        @endif
    </div>
</section>

@endsection
