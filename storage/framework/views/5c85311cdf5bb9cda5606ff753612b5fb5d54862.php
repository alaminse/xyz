
<?php $__env->startSection('title', 'Lecture Video'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Lecture Video</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-sm btn-success text-light" href="<?php echo e(route('admin.lecturevideos.create')); ?>"><i class="fa fa-plus"></i>
                            Add New</a>
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
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button class="btn btn-outline-primary mr-2 mb-2 nav-link <?php echo e($key == 0 ? 'active' : ''); ?>"
                                    id="<?php echo e($course->slug); ?>-tab" data-toggle="tab" data-target="#<?php echo e($course->slug); ?>"
                                    type="button" role="tab" aria-controls="<?php echo e($course->slug); ?>"
                                    aria-selected="<?php echo e($key == 0 ? 'true' : 'false'); ?>">
                                    <?php echo e($course->name); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-box table-responsive">
                            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>Course</th>
                                        <th>Chapter</th>
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


    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
        <script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>

        <script>
            $(document).ready(function () {
                // Load the first course's data by default
                let activeCourseSlug = $('.nav-link.active').data('target').replace('#', '');
                getLectureVideo(activeCourseSlug);

                // Handle course tab clicks
                $('.nav-link').on('click', function () {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');

                    let courseSlug = $(this).data('target').replace('#', '');
                    getLectureVideo(courseSlug);
                });
            });

            function getLectureVideo(slug) {
                $.ajax({
                    url: '/admin/lecturevideos/get/data',
                    data: { slug: slug },
                    method: 'GET',
                    success: function (response) {
                        // Destroy if already initialized
                        if ($.fn.DataTable.isDataTable('#datatable-responsive')) {
                            $('#datatable-responsive').DataTable().destroy();
                        }

                        // Replace tbody with new HTML
                        $('#datatable-responsive tbody').html(response.html);

                        // Re-initialize DataTable
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
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lecture-video/index.blade.php ENDPATH**/ ?>