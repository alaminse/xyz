@extends('layouts.backend')

@section('title', 'Edit Chapter')

@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12">
    <div class="x_panel">

        {{-- Header --}}
        <div class="x_title">
            <h2>Edit Chapter</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="{{ route('admin.chapters.index') }}">
                        Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        {{-- Body --}}
        <div class="x_content">
            <form action="{{ route('admin.chapters.update', $chapter->id) }}" method="POST">
                @csrf

                @include('backend.includes.message')

                {{-- Chapter Name --}}
                <div class="form-group">
                    <label class="font-weight-semibold">
                        Chapter Name <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ $chapter->name }}"
                        required
                    >
                </div>

                <div class="row">
                    {{-- Lessons --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-semibold">Lessons</label>
                            <select name="lesson_ids[]" class="form-control js-example-basic-multiple" multiple>
                                @foreach ($lessons as $lesson)
                                    <option
                                        value="{{ $lesson->id }}"
                                        {{ in_array($lesson->id, $chapter->lessons->pluck('id')->toArray()) ? 'selected' : '' }}
                                    >
                                        {{ $lesson->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Courses --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-semibold">Courses</label>
                            <select name="course_ids[]" class="form-control js-example-basic-multiple" multiple>
                                @foreach ($courses as $course)
                                    <option
                                        value="{{ $course->id }}"
                                        {{ in_array($course->id, $chapter->courses->pluck('id')->toArray()) ? 'selected' : '' }}
                                    >
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="font-weight-semibold">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $chapter->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ $chapter->status == 2 ? 'selected' : '' }}>Inactive</option>
                        <option value="3" {{ $chapter->status == 3 ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>

                <hr class="my-4">

                {{-- Features --}}
                <div class="form-group">
                    <label class="font-weight-bold mb-3 d-block">
                        Available Features
                    </label>

                    <div class="card bg-light border-0">
                        <div class="card-body py-3">
                            <div class="row">

                                @php
                                    $features = [
                                        'sba'             => 'SBA',
                                        'note'            => 'Note',
                                        'mcq'             => 'MCQ',
                                        'flush'           => 'Flush',
                                        'videos'          => 'Videos',
                                        'ospe'            => 'OSPE',
                                        'written'         => 'Written',
                                        'mock_viva'       => 'Mock Viva',
                                        'self_assessment' => 'Self Assessment',
                                    ];
                                @endphp

                                @foreach($features as $key => $label)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="{{ $key }}"
                                                name="{{ $key }}"
                                                value="1"
                                                {{ $chapter->$key ? 'checked' : '' }}
                                            >
                                            <label class="custom-control-label" for="{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4">
                        Update Chapter
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });
</script>
@endpush
@endsection
