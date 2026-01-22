@extends('layouts.master')
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    @lang('translation.edit')
@endsection
@section('content')
    @component('layouts.breadcrumb')
        @slot('pagetitle')
            @lang('translation.subscriptions')
        @endslot
        @slot('title')
            @lang('translation.edit')
        @endslot
    @endcomponent
    <div class="card p-2">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('admin.subscriptions.index') }}"
                        class="btn btn-success btn-sm waves-effect waves-light mb-3">
                        @lang('translation.back')
                    </a>
                </div>
                <div class="col-lg-12">
                    @include('layouts.partial.error')
                </div>
            </div>
            <form action="{{route('admin.subscriptions.update', $subscription->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row border pt-3">
                    <div class="col-md-4 mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required value="{{$subscription->title}}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="number" class="form-control" id="duration" name="duration" value="{{ $subscription->duration }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Billing Frequency</label>
                        <select class="form-control" id="type" name="type">
                            <option>Choose One</option>
                            <option value="day" {{ $subscription->type == 'day' ? 'selected' : ''}}>Day</option>
                            <option value="week" {{ $subscription->type == 'week' ? 'selected' : ''}}>Week</option>
                            <option value="month" {{ $subscription->type == 'month' ? 'selected' : ''}} selected>Month</option>
                            <option value="year" {{ $subscription->type == 'year' ? 'selected' : ''}}>Year</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required value="{{$subscription->price}}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="sell_price" class="form-label">Sell Price</label>
                        <input type="number" step="0.01" class="form-control" id="sell_price" name="sell_price" required value="{{ $subscription->sell_price }}">
                    </div>

                    <div class="col-md-4 mb-3 form-check pt-sm-0 pt-md-4">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked value="5" {{ $subscription->is_active == 5 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="description" class="form-label">@lang('translation.description')</label>
                        <textarea class="form-control" id="description" name="description" rows="6">{{$subscription->description}}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="banner" class="form-label">Banner</label>
                        <div class="row">
                            <div class="col-12">
                                <input type="file" class="form-control" id="banner" name="banner" onchange="previewImage(event, 'banner_image_preview')">
                            </div>
                            <div class="col-12 mt-1">
                                <img class="image-preview" id="banner_image_preview"
                                    src="{{ getImageUrl($subscription->banner ?? null) }}"
                                    alt="Banner" style="height: 100px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>

    <script>
        function previewImage(event, imagePreviewId) {
            let reader = new FileReader();
            reader.onload = function() {
                let output = document.getElementById(imagePreviewId);
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
