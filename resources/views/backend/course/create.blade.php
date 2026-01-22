@extends('layouts.backend')

@section('title', 'Create Course')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Create Course</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-warning text-white" href="{{ route('admin.courses.index') }}"> Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        {{ old('parent_id') == $course->id ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 col-12 mb-3">
                                            <label class="form-label">Course Name</label>
                                            <input class="form-control" name="name" value="{{ old('name') }}" required>
                                        </div>


                                        <div class="col-md-4 col-12 mt-4">
                                            <div class="card mt-1">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="status" value="1"
                                                            {{ old('status', 1) ? 'checked' : '' }}>
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

                    <div class="row">
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
                                                {{ old($key) ? 'checked' : '' }}>
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
                                        src="{{ getImageUrl(null) }}"
                                        class="img-fluid rounded"
                                        style="max-height:100px;">
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_pricing" name="is_pricing" value="1"
                                            {{ old('is_pricing') ? 'checked' : '' }}>
                                        <label class="form-check-label">Has Pricing</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="card pricing_div d-none mt-3 ">
                        <div class="card-body p-3">
                            <h6 class="font-weight-bold mb-3">Pricing</h6>

                            <div class="row">
                                <div class="col-md-3 col-6 mb-3">
                                    <label>Duration</label>
                                    <input type="number" class="form-control" name="duration"
                                        value="{{ old('duration') }}">
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <label>Billing</label>
                                    <select class="form-control" name="type">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month" selected>Month</option>
                                        <option value="year">Year</option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <label>Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price"
                                        value="{{ old('price',0) }}">
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <label>Sell Price</label>
                                    <input type="number" step="0.01" class="form-control" name="sell_price"
                                        value="{{ old('sell_price',0) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assign_to[]" class="form-control select2" multiple>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Details</label>
                            <textarea class="form-control summernote" name="details">{!! old('details') !!}</textarea>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-2">Create</button>

                </form>
            </div>

        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2(); // Initialize Select2 globally

                $('#is_pricing').change(function() {
                    var isChecked = $(this).is(':checked');
                    if (isChecked) {
                        $('.pricing_div').removeClass('d-none');
                        $('.select2').select2(); // Reinitialize Select2 after showing the element
                    } else {
                        $('.pricing_div').addClass('d-none');
                        $('.select2').select2(); // Ensure Select2 is active even if hidden
                    }
                });

                $('#is_pricing').trigger('change'); // Trigger initial state check
            });

            function uploadImage(file, summernoteInstance) {
                var formData = new FormData();
                formData.append('image', file);
                let csrfToken = '{{ csrf_token() }}';
                let url = '{{ route('admin.summernote.upload') }}?_token=' + csrfToken;
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        summernoteInstance.summernote('insertImage', response.url);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $(`.summernote`).summernote({
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0], $(this));
                    }
                }
            });

            function previewImage(event, previewId) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById(previewId);
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    @endpush
@endsection
