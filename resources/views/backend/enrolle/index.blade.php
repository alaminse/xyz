@extends('layouts.backend')
@section('title', 'Enrollments')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Enrollments</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        {{-- <a class="btn btn-sm btn-success text-white" href="{{ route('admin.enrolles.create') }}">
                            <i class="fa fa-plus"></i> Add New</a> --}}
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="d-flex">
                    <div class="p-2 flex-grow-1">
                        <h3>Report</h3>
                    </div>
                    <div class="p-2">
                        <input type="text" name="dates" class="form-control" id="dateRange"
                            style="width: 13rem !important" />
                    </div>
                    <div class="p-2">
                        <select class="form-control" id="selectDateRange">
                            <option value="today" selected>Today</option>
                            <option value="week">Last Week</option>
                            <option value="month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card p-0 shadow-lg mb-5 card-hover">
                            <div class="card-body">
                                <h2 class="text-center">Total Users</h2>
                                <h1 class="fs-1 text-center"><strong id="totalUsers"></strong></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-0 shadow-lg mb-5 card-hover">
                            <div class="card-body">
                                <h2 class="text-center">Active Users</h2>
                                <h1 class="fs-1 text-center"><strong id="activeUsers"></strong></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-0 shadow-lg mb-5 card-hover">
                            <div class="card-body">
                                <h2 class="text-center">Earnig</h2>
                                <h1 class="fs-1 text-center"><strong id="earning"></strong></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                            cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ITEM</th>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Joining Date</th>
                                    <th>Expiry Date</th>
                                    <th>Price</th>
                                    <th>Sell Price</th>
                                    <th>Status</th>
                                    <th width="25%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tablebody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="statusUpdateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusUpdateLabel">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('admin.enrolles.status') }}" method="post">
                        @csrf
                        <input type="hidden" name="enrolle_id" id="enrollee-id-placeholder">
                        <select class="custom-select" id="status-select" name="status">
                            <option value="{{ App\Enums\Status::PENDING() }}">{{ App\Enums\Status::PENDING()->title() }}
                            </option>
                            <option value="{{ App\Enums\Status::ACTIVE() }}">{{ App\Enums\Status::ACTIVE()->title() }}
                            </option>
                            <option value="{{ App\Enums\Status::INACTIVE() }}">{{ App\Enums\Status::INACTIVE()->title() }}
                            </option>
                            <option value="{{ App\Enums\Status::DELETED() }}">{{ App\Enums\Status::DELETED()->title() }}
                            </option>
                            <option value="{{ App\Enums\Status::BANNED() }}">{{ App\Enums\Status::BANNED()->title() }}
                            </option>
                            <option value="{{ App\Enums\Status::SEEN() }}">{{ App\Enums\Status::SEEN()->title() }}
                            </option>
                        </select>

                        <button type="submit" class="btn btn-warning mt-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Datatables -->
        <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#statusUpdate').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var enrolleeId = button.data('id'); // Get enrollee ID
                    var enrolleeStatus = button.data('status'); // Get enrollee status

                    var modal = $(this);
                    // Set the hidden input value
                    modal.find('#enrollee-id-placeholder').val(enrolleeId);
                    // Set the selected option in the dropdown
                    modal.find('#status-select').val(enrolleeStatus);
                });

                function updateData(startDate, endDate) {

                    $.ajax({
                        url: '/admin/enrolles/report',
                        type: 'GET',
                        data: {
                            startDate: startDate,
                            endDate: endDate
                        },
                        success: function(response) {
                            $('#totalUsers').text(response.total);
                            $('#activeUsers').text(response.active);
                            $('#earning').text(response.earning);

                            var subscriptionTable = $('#datatable').DataTable();
                            if (response.html && response.total > 0) {
                                if (subscriptionTable !== undefined && subscriptionTable !== null) {
                                    subscriptionTable.destroy();
                                }

                                $('#tablebody').html(response.html);
                                $('.pagination').html(response.pagination);

                                subscriptionTable = $('#datatable').DataTable({
                                    "paging": true,
                                    "lengthChange": false,
                                    "searching": false,
                                    "info": false,
                                    "autoWidth": true,
                                    "drawCallback": function(settings) {
                                        var api = this.api();
                                        var rows = api.rows({
                                            page: 'current'
                                        }).nodes();
                                        var startIndex = api.page() * api.page.len();
                                        var i = startIndex + 1;

                                        $(rows).each(function() {
                                            $(this).find('td:first').html(i++);
                                        });
                                    }
                                });
                            } else {
                                $('#tablebody').html('<tr>\
                                        <td align="center" colspan="10">No Data Found</td>\
                                    </tr>');
                                $('.pagination').html('');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }

                $('#dateRange').change(function() {
                    let selectedOption = $(this).val();
                    let dateParts = selectedOption.split(' - ');
                    let startDateString = dateParts[0];
                    let endDateString = dateParts[1];

                    let startDate = new Date(startDateString);
                    let endDate = new Date(endDateString);
                    startDate.setDate(startDate.getDate() + 1);
                    endDate.setDate(endDate.getDate() + 1);

                    let formattedStartDate = startDate.toISOString().split('T')[0];
                    let formattedEndDate = endDate.toISOString().split('T')[0];

                    updateData(formattedStartDate, formattedEndDate);
                });


                $('#selectDateRange').change(function() {
                    let selectedOption = $(this).val();
                    let startDate, endDate;

                    switch (selectedOption) {
                        case "today":
                            let today = new Date();
                            today.setDate(today.getDate() + 1); // Add one day
                            startDate = endDate = today.toISOString().split('T')[0];
                            break;
                        case "week":
                            endDate = new Date().toISOString().split('T')[0];
                            startDate = new Date(new Date(endDate).getTime() - 7 * 24 * 60 * 60 * 1000)
                                .toISOString().split('T')[0];
                            break;
                        case "month":
                            startDate = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                                .toISOString().split('T')[0];
                            endDate = new Date().toISOString().split('T')[0];
                            break;
                        case "last_month":
                            var lastMonthEnd = new Date(new Date().getFullYear(), new Date().getMonth(), 0);
                            var lastMonthStart = new Date(lastMonthEnd.getFullYear(), lastMonthEnd.getMonth(),
                                1);
                            startDate = lastMonthStart.toISOString().split('T')[0];
                            endDate = lastMonthEnd.toISOString().split('T')[0];
                            break;
                        case "this_year":
                            startDate = new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0];
                            endDate = new Date().toISOString().split('T')[0];
                            break;
                        default:
                            startDate = endDate = new Date().toISOString().split('T')[0];
                    }

                    $('#dateRange').val(startDate + " - " + endDate);
                    updateData(startDate, endDate);
                });

                $('#selectDateRange').trigger('change');
            });

            $('input[name="dates"]').daterangepicker();
        </script>
    @endpush
@endsection
