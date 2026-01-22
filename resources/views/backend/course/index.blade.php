@extends('layouts.backend')
@section('title', 'Courses')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Courses <small>
                    </small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-sm btn-success text-light" href="{{ route('admin.courses.create') }}"><i
                                class="fa fa-plus"></i> Add Course</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            @include('backend.includes.message')
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap"
                                cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Parent Name</th>
                                        <th>Active</th>
                                        <th>Status</th>
                                        <th width="25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $key => $course)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->getCourseName() }}</td>
                                            <td>
                                                @php
                                                    $detail = json_decode($course->detail);
                                                    $labels = [
                                                        'sba' => 'SBA',
                                                        'note' => 'Note',
                                                        'mcq' => 'MCQ',
                                                        'flush' => 'Flush',
                                                        'written' => 'Written',
                                                        'videos' => 'Video',
                                                        'verbal' => 'Verbal',
                                                        'ospe' => 'OSPE',
                                                        'self_assessment' => 'Self Assessment',
                                                    ];
                                                @endphp

                                                @if ($detail)
                                                    @foreach ($labels as $key => $label)
                                                        @if (data_get($detail, $key) == 1)
                                                            <span
                                                                class="badge bg-success me-1 text-white">{{ $label }}</span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.courses.status', $course->id) }}"
                                                    class="btn btn-{{ $course->status == 1 ? 'success' : ($course->status == 3 ? 'danger' : 'warning') }} btn-sm  {{ $course->status == 3 ? 'disabled' : '' }}">{{ \App\Enums\Status::from($course->status)->title() }}</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="{{ route('admin.courses.edit', $course->id) }}"> <i
                                                        class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger {{ $course->status == 3 ? 'disabled' : '' }}"
                                                    onclick="showDeleteConfirmation(event)"
                                                    href="{{ route('admin.courses.destroy', $course->id) }}"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @push('scripts')
        <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    @endpush
@endsection
