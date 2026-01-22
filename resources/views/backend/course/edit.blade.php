@extends('layouts.backend')

@section('title', 'Edit Course')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Course</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white" href="{{ route('admin.courses.index') }}">Back</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('backend.includes.message')

                <!-- BASIC INFO -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-3">
                                        <label class="form-label">Parent Course</label>
                                        <select name="parent_id" class="form-control select2">
                                            <option value="">Choose Parent Course</option>
                                            @foreach ($courses as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('parent_id', $course->parent_id) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12 mb-3">
                                        <label class="form-label">Course Name</label>
                                        <input class="form-control" name="name"
                                            value="{{ old('name', $course->name) }}" required>
                                    </div>

                                    <div class="col-md-4 col-12 mt-4">
                                        <div class="card mt-1">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="status" value="1"
                                                        {{ old('status', $course->status) ? 'checked' : '' }}>
                                                    <label class="form-check-label">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LEFT + RIGHT -->
                <div class="row">
                    <!-- LEFT -->
                    <div class="col-lg-4 col-md-5 col-12">
                        <div class="card mb-3">
                            <div class="card-body p-3">
                                <h6 class="font-weight-bold mb-2">Course Has</h6>

                                @foreach ([
                                    'sba' 			=> 'SBA',
                                    'note' 			=> 'Note',
                                    'mcq' 			=> 'MCQ',
                                    'flush' 		=> 'Flush',
                                    'written' 		=> 'Written Assessment',
                                    'videos' 		=> 'Lecture Video',
                                    'mock_viva' 	=> 'Mock Viva',
                                    'ospe' 			=> 'OSPE Station',
                                    'self_assessment' => 'Self Assessment'
                                ] as $key => $label)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="{{ $key }}" value="1"
                                            {{ old($key, $course->detail?->$key) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-8 col-md-7 col-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <label class="font-weight-bold">Banner</label>
                                <input type="file" class="form-control mb-2" name="banner"
                                    onchange="previewImage(event,'banner_preview')">

                                <img id="banner_preview"
                                    src="{{ getImageUrl($course->banner) }}"
                                    class="img-fluid rounded"
                                    style="max-height:100px;">
                            </div>
                        </div>

                        <div class="card mt-2">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_pricing" name="is_pricing" value="1"
                                        {{ old('is_pricing', $course->is_pricing) ? 'checked' : '' }}>
                                    <label class="form-check-label">Has Pricing</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRICING -->
                <div class="card pricing_div d-none mt-3">
                    <div class="card-body p-3">
                        <h6 class="font-weight-bold mb-3">Pricing</h6>
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <label>Duration</label>
                                <input type="number" class="form-control" name="duration"
                                    value="{{ old('duration', $course->detail?->duration) }}">
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <label>Billing</label>
                                <select class="form-control" name="type">
                                    @foreach(['day','week','month','year'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $course->detail?->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <label>Price</label>
                                <input type="number" step="0.01" class="form-control" name="price"
                                    value="{{ old('price', $course->detail?->price) }}">
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <label>Sell Price</label>
                                <input type="number" step="0.01" class="form-control" name="sell_price"
                                    value="{{ old('sell_price', $course->detail?->sell_price) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ASSIGN + DETAILS -->
                <div class="row mt-3">
                    <div class="col-12 mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assign_to[]" class="form-control select2" multiple>
                            @foreach ($instructors as $instructor)
                                <option value="{{ $instructor->id }}"
                                    {{ $course->assignedUsers->contains('id', $instructor->id) ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Details</label>
                        <textarea class="form-control summernote" name="details">{!! old('details', $course->details) !!}</textarea>
                    </div>
                </div>

                <button class="btn btn-warning mt-2">Update</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function () {
    $('.select2').select2();

    $('#is_pricing').on('change', function () {
        $('.pricing_div').toggleClass('d-none', !this.checked);
    }).trigger('change');
});

function previewImage(event, id) {
    const reader = new FileReader();
    reader.onload = () => document.getElementById(id).src = reader.result;
    reader.readAsDataURL(event.target.files[0]);
}

$('.summernote').summernote();
</script>
@endpush
@endsection
