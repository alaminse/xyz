@extends('layouts.backend')
@section('title', 'OSPE Lists')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>OSPE Lists <small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="btn btn-sm btn-success text-white" href="{{ route('admin.ospestations.create') }}"><i class="fa fa-plus"></i> Add New</a>
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
                <div class="col-12">
                        <div class="d-flex flex-wrap">
                            @foreach ($courses as $key => $course)
                                <button class="btn btn-outline-primary mr-2 mb-2 nav-link {{ $key == 0 ? 'active' : '' }}"
                                    id="{{ $course->slug }}-tab" data-toggle="tab" data-target="#{{ $course->slug }}"
                                    type="button" role="tab" aria-controls="{{ $course->slug }}"
                                    aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                    {{ $course->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Chapter</th>
                                <th>Lesson</th>
                                <th>Question Image</th>
                                <th>IsPaid</th>
                                <th>Status</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
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

        <script>
            $(document).ready(function () {
                // Load the first course's data by default
                let activeCourseSlug = $('.nav-link.active').data('target').replace('#', '');
                getWrittenAssessments(activeCourseSlug);

                // Handle course tab clicks
                $('.nav-link').on('click', function () {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');

                    let courseSlug = $(this).data('target').replace('#', '');
                    getWrittenAssessments(courseSlug);
                });
            });

            function getWrittenAssessments(slug) {
                $.ajax({
                    url: '{{ route("admin.ospestations.getData") }}',
                    data: { slug: slug },
                    method: 'GET',
                    success: function (response) {
                        // Destroy DataTable if already initialized
                        if ($.fn.DataTable.isDataTable('#datatable-responsive')) {
                            $('#datatable-responsive').DataTable().destroy();
                        }

                        // Clear the tbody completely
                        $('#datatable-responsive tbody').empty();

                        // Replace tbody with new HTML
                        $('#datatable-responsive tbody').html(response.html);

                        // Re-initialize DataTable (simplified - no column config needed for HTML)
                        $('#datatable-responsive').DataTable({
                            responsive: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            autoWidth: false,
                            // Only use columnDefs to disable ordering/searching on specific columns
                            columnDefs: [
                                {
                                    targets: [0, 3, 6], // No, Question Image, and Action columns
                                    orderable: false
                                },
                                {
                                    targets: [3, 6], // Question Image and Action columns
                                    searchable: false
                                }
                            ]
                        });
                    },
                    error: function (xhr) {
                        console.error('Error fetching data:', xhr);
                        alert('Error loading data. Please try again.');
                    }
                });
            }

            function showDeleteConfirmation(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this OSPE station?')) {
                    window.location.href = event.target.closest('a').href;
                }
            }
        </script>
@endpush
@endsection
