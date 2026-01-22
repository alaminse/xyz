@extends('layouts.master')
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('title')
    @lang('translation.details')
@endsection
@section('content')
    @component('layouts.breadcrumb')
        @slot('pagetitle')
            @lang('translation.subscriptions')
        @endslot
        @slot('title')
            @lang('translation.details')
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-md-4">
            <div class="card p-0 shadow-lg mb-5 card-hover">
                <div class="card-body">
                    <h2 class="text-center">Total Subscribers</h2>
                    <h1 class="fs-1 text-center"><strong>100</strong></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-0 shadow-lg mb-5 card-hover">
                <div class="card-body">
                    <h2 class="text-center">Total Amount</h2>
                    <h1 class="fs-1 text-center"><strong>10000</strong></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-0 shadow-lg mb-5 card-hover">
                <div class="card-body">
                    <h2 class="text-center">Today</h2>
                    <h1 class="fs-1 text-center"><strong>1000</strong></h1>
                </div>
            </div>
        </div>
    </div>
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
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <ul class="list-group list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Title</div>
                            </div>
                            <span class="rounded-pill">{{$subscription->title}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Duration</div>
                            </div>
                            <span class="rounded-pill">{{$subscription->duration}} {{$subscription->type}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Price</div>
                            </div>
                            <span class="rounded-pill">{{$subscription->price}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Sell Price</div>
                            </div>
                            <span class="rounded-pill">{{$subscription->sell_price}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Status</div>
                            </div>
                            <span class="rounded-pill">{{$subscription->status}}</span>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-6">
                    <label for="banner" class="form-label">Banner</label>
                    <div class="row">
                        <div class="col-12 mt-1">
                            <img class="image-preview img-fluid" id="banner_image_preview"
                                src="{{ getImageUrl($subscription->banner) }}"
                                alt="Banner">
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="description" class="form-label">Description</label>
                    <br>
                    {{$subscription->description}}
                </div>
            </div>
        </div>
    </div>

    <div class="card p-2">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12">
                    <h6 class="bg-head-test" style="border-radius: 16px 16PX 0 0; margin-bottom: 0">User Lists</h6>
                    <div style="overflow-x:auto;">
                        <table class="table table-striped-columns">
                            <thead>
                                <tr style="background: #EFF7FD">
                                    <th scope="col">Date</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Results</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">00:32 - 05/25/18</th>
                                    <td>Lorem ipsum</td>
                                    <td>example@gmail.com</td>
                                    <td>23</td>
                                    <td><i class="uil-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <th scope="row">00:32 - 05/25/18</th>
                                    <td>Lorem ipsum</td>
                                    <td>example@gmail.com</td>
                                    <td>23</td>
                                    <td><i class="uil-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <th scope="row">00:32 - 05/25/18</th>
                                    <td>Lorem ipsum</td>
                                    <td>example@gmail.com</td>
                                    <td>23</td>
                                    <td><i class="uil-times text-danger"></i></td>
                                </tr>
                                <tr>
                                    <th scope="row">00:32 - 05/25/18</th>
                                    <td>Lorem ipsum</td>
                                    <td>example@gmail.com</td>
                                    <td>23</td>
                                    <td><i class="uil-times text-danger"></i></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection
