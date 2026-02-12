<?php $__env->startSection('title', 'Enrollments'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Enrollments</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="p-2 flex-grow-1">
                        <h3>Report</h3>
                    </div>
                    
                    <div class="p-2">
                        <select class="form-control" id="selectCourse" style="width: 13rem !important">
                            <option value="">All Courses</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="p-2">
                        <input type="text" name="dates" class="form-control" id="dateRange"
                            style="width: 13rem !important" placeholder="Select date range" />
                    </div>
                    <div class="p-2">
                        <select class="form-control" id="selectDateRange">
                            <option value="all_time" selected>All Time</option>
                            <option value="today">Today</option>
                            <option value="week">Last Week</option>
                            <option value="month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                            <option value="ytd">2024 to Today</option>
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
                                <h2 class="text-center">Earning</h2>
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
                        <div class="pagination-wrapper text-center mt-3">
                           <!-- Pagination will be loaded here -->
                        </div>
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

                    <form action="<?php echo e(route('admin.enrolles.status')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="enrolle_id" id="enrollee-id-placeholder">
                        <select class="custom-select" id="status-select" name="status">
                            <option value="<?php echo e(App\Enums\Status::PENDING()); ?>"><?php echo e(App\Enums\Status::PENDING()->title()); ?>

                            </option>
                            <option value="<?php echo e(App\Enums\Status::ACTIVE()); ?>"><?php echo e(App\Enums\Status::ACTIVE()->title()); ?>

                            </option>
                            <option value="<?php echo e(App\Enums\Status::INACTIVE()); ?>"><?php echo e(App\Enums\Status::INACTIVE()->title()); ?>

                            </option>
                            <option value="<?php echo e(App\Enums\Status::DELETED()); ?>"><?php echo e(App\Enums\Status::DELETED()->title()); ?>

                            </option>
                            <option value="<?php echo e(App\Enums\Status::BANNED()); ?>"><?php echo e(App\Enums\Status::BANNED()->title()); ?>

                            </option>
                            <option value="<?php echo e(App\Enums\Status::SEEN()); ?>"><?php echo e(App\Enums\Status::SEEN()->title()); ?>

                            </option>
                        </select>

                        <button type="submit" class="btn btn-warning mt-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <!-- Datatables -->
        <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(document).ready(function() {
                // Modal setup
                $('#statusUpdate').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var enrolleeId = button.data('id');
                    var enrolleeStatus = button.data('status');

                    var modal = $(this);
                    modal.find('#enrollee-id-placeholder').val(enrolleeId);
                    modal.find('#status-select').val(enrolleeStatus);
                });


                // Initialize date range picker
                $('#dateRange').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'MM/DD/YYYY'
                    }
                });

                // MODIFIED: Handle date range picker apply
                $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    let startDate = picker.startDate.format('YYYY-MM-DD');
                    let endDate = picker.endDate.format('YYYY-MM-DD');
                    updateData(startDate, endDate);
                    $('#selectDateRange').val(''); // Clear the dropdown when custom range is selected
                });

                // MODIFIED: Handle date range picker cancel
                $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#selectDateRange').val('all_time');
                    updateData(null, null);
                });

                // NEW: Event handler for course selection change
                $('#selectCourse').on('change', function() {
                    // When course changes, refresh data with current date range
                    let dateRange = $('#dateRange').val();
                    if (dateRange) {
                        // If a custom date range is set, use it
                        $('#dateRange').trigger('apply.daterangepicker');
                    } else {
                        // Otherwise, use the dropdown selection
                        $('#selectDateRange').trigger('change');
                    }
                });

                // Load initial data
                $('#selectDateRange').trigger('change');
            });

            // MODIFIED: Function to update report data
            function updateData(startDate, endDate) {
                // Show loading state
                $('#tablebody').html('<tr><td align="center" colspan="10">Loading...</td></tr>');
                $('.pagination-wrapper').html(''); // Clear pagination

                // Get selected course ID
                let courseId = $('#selectCourse').val();

                // Prepare data for AJAX
                let ajaxData = { course_id: courseId }; // IMPORTANT: Backend must accept 'course_id'
                if (startDate && endDate) {
                    ajaxData.startDate = startDate;
                    ajaxData.endDate = endDate;
                }

                $.ajax({
                    url: '/admin/enrolles/report',
                    type: 'GET',
                    data: ajaxData,
                    success: function(response) {
                        $('#totalUsers').text(response.total || 0);
                        $('#activeUsers').text(response.active || 0);
                        $('#earning').text('৳' + (response.earning || 0).toFixed(2));

                        let subscriptionTable = $('#datatable').DataTable();
                        if (subscriptionTable) {
                            subscriptionTable.destroy();
                        }

                        if (response.html && response.total > 0) {
                            $('#tablebody').html(response.html);
                            $('.pagination-wrapper').html(response.pagination);
                        } else {
                            $('#tablebody').html('<tr><td align="center" colspan="10">No Data Found</td></tr>');
                        }

                        // Re-initialize DataTable
                        $('#datatable').DataTable({
                            "paging": true, // We use server-side pagination
                            "lengthChange": false,
                            "searching": true,
                            "ordering": true,
                            "info": false,
                            "autoWidth": false,
                            "drawCallback": function(settings) {
                                var api = this.api();
                                var rows = api.rows({page: 'current'}).nodes();
                                var startIndex = api.page() * api.page.len();
                                var i = startIndex + 1;
                                $(rows).each(function() {
                                    $(this).find('td:first').html(i++);
                                });
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching report data:", error);
                        $('#tablebody').html('<tr><td align="center" colspan="10">Error loading data. Please try again.</td></tr>');
                    }
                });
            }

            $('#selectDateRange').change(function() {
                let selectedOption = $(this).val();
                let startDate, endDate;

                switch (selectedOption) {
                    case "all_time":
                        $('#dateRange').val('');
                        updateData(null, null);
                        return;
                    case "today":
                        let today = new Date();
                        startDate = endDate = today.toISOString().split('T')[0];
                        break;
                    case "week":
                        endDate = new Date().toISOString().split('T')[0];
                        startDate = new Date(new Date(endDate).getTime() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                        break;
                    case "month":
                        startDate = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
                        endDate = new Date().toISOString().split('T')[0];
                        break;
                    case "last_month":
                        var lastMonthEnd = new Date(new Date().getFullYear(), new Date().getMonth(), 0);
                        var lastMonthStart = new Date(lastMonthEnd.getFullYear(), lastMonthEnd.getMonth(), 1);
                        startDate = lastMonthStart.toISOString().split('T')[0];
                        endDate = lastMonthEnd.toISOString().split('T')[0];
                        break;
                    case "this_year":
                        startDate = new Date(new Date().getFullYear(), 0, 1).toISOString().split('T')[0];
                        endDate = new Date().toISOString().split('T')[0];
                        break;
                    case "ytd": // Year to Date (2024 to Today)
                        startDate = new Date(2024, 0, 1).toISOString().split('T')[0];
                        endDate = new Date().toISOString().split('T')[0];
                        break;
                    default:
                        startDate = endDate = new Date().toISOString().split('T')[0];
                }

                $('#dateRange').val(startDate + " - " + endDate);
                updateData(startDate, endDate);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/enrolle/index.blade.php ENDPATH**/ ?>