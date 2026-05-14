<?php $__env->startSection('title', 'Secure PDFs'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Secure PDFs</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-light"
                       href="<?php echo e(route('admin.secure-pdfs.create')); ?>">
                        <i class="fa fa-upload"></i> Upload New PDF
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap">
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                class="btn btn-outline-primary mr-2 mb-2 nav-link <?php echo e($key == 0 ? 'active' : ''); ?>"
                                id="<?php echo e($course->slug); ?>-tab"
                                data-toggle="tab"
                                data-target="#<?php echo e($course->slug); ?>"
                                type="button"
                                aria-controls="<?php echo e($course->slug); ?>"
                                aria-selected="<?php echo e($key == 0 ? 'true' : 'false'); ?>">
                                <?php echo e($course->name); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box table-responsive">
                        <table id="pdf-table"
                               class="table table-striped table-bordered dt-responsive nowrap"
                               cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title / File</th>
                                    <th>Chapter</th>
                                    <th>Lesson</th>
                                    <th>Pages</th>
                                    <th>Size</th>
                                    <th>IsPaid</th>
                                    <th>Status</th>
                                    <th width="22%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pdf-tbody">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:#d9534f; color:white;">
                <button type="button" class="close"
                        data-dismiss="modal" style="color:white;">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-warning"></i> Confirm Delete
                </h4>
            </div>
            <div class="modal-body">
                <p>Delete: <strong id="del-title" class="text-danger"></strong></p>
                <small class="text-muted">File will be permanently removed.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">Cancel</button>
                <form id="del-form" method="POST" style="display:inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')); ?>"></script>

<script>
$(document).ready(function () {
    let activeCourseSlug = $('.nav-link.active').data('target').replace('#', '');
    getPdfs(activeCourseSlug);

    $('.nav-link').on('click', function () {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        let slug = $(this).data('target').replace('#', '');
        getPdfs(slug);
    });
});

function getPdfs(slug) {
    $.ajax({
        url: '<?php echo e(route("admin.secure-pdfs.data")); ?>',
        data: { slug: slug },
        method: 'GET',
        success: function (res) {
            if ($.fn.DataTable.isDataTable('#pdf-table')) {
                $('#pdf-table').DataTable().destroy();
            }
            $('#pdf-tbody').html(res.html);
            $('#pdf-table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
            });
        },
        error: function () {
            $('#pdf-tbody').html(
                '<tr><td colspan="9" class="text-center text-danger">Failed to load.</td></tr>'
            );
        }
    });
}

$(document).on('click', '.btn-toggle', function () {
    var btn = $(this), id = btn.data('id');
    $.post('/admin/secure-pdfs/' + id + '/toggle',
        { _token: '<?php echo e(csrf_token()); ?>' },
        function (res) {
            btn.text(res.label)
               .removeClass('btn-success btn-danger')
               .addClass(res.is_active ? 'btn-success' : 'btn-danger');
        });
});

$(document).on('click', '.btn-delete', function () {
    $('#del-title').text($(this).data('title'));
    $('#del-form').attr('action', $(this).data('url'));
    $('#deleteModal').modal('show');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/secure-pdfs/index.blade.php ENDPATH**/ ?>