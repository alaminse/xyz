<?php $__env->startSection('title', 'Notes'); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')); ?>"
        rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Notes</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-sm btn-success text-light" href="<?php echo e(route('admin.notes.create')); ?>">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </li>

                    <li>
                        <a class="btn btn-sm btn-info text-light" data-toggle="modal" data-target="#importModal">
                            <i class="fa fa-upload"></i> Import
                        </a>
                    </li>
                    <li>
                        <a class="btn btn-sm btn-warning text-light" data-toggle="modal" data-target="#exportModal">
                            <i class="fa fa-download"></i> Export
                        </a>
                    </li>
                    <li>
                        <a class="btn btn-sm btn-secondary text-light" href="<?php echo e(route('admin.notes.sample-download')); ?>">
                            <i class="fa fa-file-excel-o"></i> Sample
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
                                <button class="btn btn-outline-primary mr-2 mb-2 nav-link <?php echo e($key == 0 ? 'active' : ''); ?>"
                                    id="<?php echo e($course->slug); ?>-tab" data-toggle="tab" data-target="#<?php echo e($course->slug); ?>"
                                    type="button" aria-controls="<?php echo e($course->slug); ?>"
                                    aria-selected="<?php echo e($key == 0 ? 'true' : 'false'); ?>">
                                    <?php echo e($course->name); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Sections Count</th>
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





    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?php echo e(route('admin.notes.bulk-upload.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Import Questions</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Excel / CSV File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
                            <small class="text-muted">
                                File must have <b>chapter_name</b>, <b>lesson_name</b> columns.
                                If the MCQ set doesn't exist yet, also fill <b>course_name</b> — it will be created
                                automatically.
                                <a href="<?php echo e(route('admin.notes.sample-download')); ?>">Download sample</a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Questions</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Leave everything empty to export all questions, or narrow it down.</p>

                    <div class="form-group">
                        <label>Course <span class="text-muted">(optional)</span></label>
                        <select id="exportCourse" class="form-control">
                            <option value="">-- All Courses --</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Chapter <span class="text-muted">(optional)</span></label>
                        <select id="exportChapter" class="form-control" disabled>
                            <option value="">-- All Chapters --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lesson <span class="text-muted">(optional)</span></label>
                        <select id="exportLesson" class="form-control" disabled>
                            <option value="">-- All Lessons --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="exportSubmitBtn">
                        <i class="fa fa-download"></i> Download
                    </button>
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
            // ================================================================
            // ================================================================
            // Course change -> load chapters
            $('#exportCourse').on('change', function() {
                let courseId = $(this).val();
                $('#exportChapter').html('<option value="">-- All Chapters --</option>').prop('disabled', true);
                $('#exportLesson').html('<option value="">-- All Lessons --</option>').prop('disabled', true);

                if (!courseId) return;

                $.ajax({
                    url: "<?php echo e(route('admin.notes.export.chapters')); ?>",
                    method: 'GET',
                    data: {
                        course_id: courseId
                    },
                    success: function(res) {
                        let options = '<option value="">-- All Chapters --</option>';
                        res.chapters.forEach(function(chapter) {
                            options += `<option value="${chapter.id}">${chapter.name}</option>`;
                        });
                        $('#exportChapter').html(options).prop('disabled', false);
                    }
                });
            });

            $('#exportChapter').on('change', function() {
                let chapterId = $(this).val();
                $('#exportLesson').html('<option value="">-- All Lessons --</option>').prop('disabled', true);

                if (!chapterId) return;

                $.ajax({
                    url: "<?php echo e(route('admin.notes.export.lessons')); ?>",
                    method: 'GET',
                    data: {
                        chapter_id: chapterId
                    },
                    success: function(res) {
                        let options = '<option value="">-- All Lessons --</option>';
                        res.lessons.forEach(function(lesson) {
                            options += `<option value="${lesson.id}">${lesson.name}</option>`;
                        });
                        $('#exportLesson').html(options).prop('disabled', false);
                    }
                });
            });

            $('#exportSubmitBtn').on('click', function() {
                let courseId = $('#exportCourse').val();
                let chapterId = $('#exportChapter').val();
                let lessonId = $('#exportLesson').val();

                let url = "<?php echo e(route('admin.notes.export')); ?>" + "?";
                if (courseId) url += "course_id=" + courseId + "&";
                if (chapterId) url += "chapter_id=" + chapterId + "&";
                if (lessonId) url += "lesson_id=" + lessonId + "&";

                window.location.href = url;
            });
            // ================================================================
            // ================================================================
            $(document).ready(function() {
                let activeCourseSlug = $('.nav-link.active').data('target').replace('#', '');
                getNote(activeCourseSlug);

                $('.nav-link').on('click', function() {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                    let courseSlug = $(this).data('target').replace('#', '');
                    getNote(courseSlug);
                });
            });

            function getNote(slug) {
                $.ajax({
                    url: '<?php echo e(route('admin.notes.getData')); ?>',
                    data: {
                        slug: slug
                    },
                    method: 'GET',
                    success: function(response) {
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
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr);
                    }
                });
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/note/index.blade.php ENDPATH**/ ?>