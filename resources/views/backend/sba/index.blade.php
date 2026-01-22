@extends('layouts.backend')
@section('title', 'Sbas')

@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Sbas</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-light" href="{{ route('admin.sbas.create') }}">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap">
                        @foreach ($courses as $key => $course)
                            <button class="btn btn-outline-primary mr-2 mb-2 nav-link {{ $key == 0 ? 'active' : '' }}"
                                    id="{{ $course->slug }}-tab"
                                    data-toggle="tab"
                                    data-target="#{{ $course->slug }}"
                                    type="button"
                                    aria-controls="{{ $course->slug }}"
                                    aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                {{ $course->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable-responsive" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Questions Count</th>
                                    <th>Courses</th>
                                    <th>Chapter</th>
                                    <th>Lesson</th>
                                    <th>IsPaid</th>
                                    <th>Status</th>
                                    <th width="25%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
<script>
$(document).ready(function() {
    let activeCourseSlug = $('.nav-link.active').data('target').replace('#', '');
    getSba(activeCourseSlug);

    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        let courseSlug = $(this).data('target').replace('#', '');
        getSba(courseSlug);
    });
});

function getSba(slug) {
    $.ajax({
        url: '/admin/sbas/get/data',
        data: { slug: slug },
        method: 'GET',
        success: function (response) {
            if ($.fn.DataTable.isDataTable('#datatable-responsive')) {
                $('#datatable-responsive').DataTable().destroy();
            }
            $('#datatable-responsive tbody').html(response.html);
            $('#datatable-responsive').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true
            });
        },
        error: function (xhr) {
            console.error('Error fetching data:', xhr);
        }
    });
}
</script>
@endpush
@endsection
