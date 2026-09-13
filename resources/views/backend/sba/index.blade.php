@extends('layouts.backend')
@section('title', 'Sbas')

@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
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
                        <a class="btn btn-sm btn-secondary text-light" href="{{ route('admin.sbas.sample-download') }}">
                            <i class="fa fa-file-excel-o"></i> Sample
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
                                    id="{{ $course->slug }}-tab" data-toggle="tab" data-target="#{{ $course->slug }}"
                                    type="button" aria-controls="{{ $course->slug }}"
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

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.sbas.bulk-upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                If the MCQ set doesn't exist yet, also fill <b>course_name</b> — it will be created automatically.
                                <a href="{{ route('admin.sbas.sample-download') }}">Download sample</a>
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
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
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

    @push('scripts')
        <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
        <script>

            // Course change -> load chapters
            $('#exportCourse').on('change', function() {
                let courseId = $(this).val();
                $('#exportChapter').html('<option value="">-- All Chapters --</option>').prop('disabled', true);
                $('#exportLesson').html('<option value="">-- All Lessons --</option>').prop('disabled', true);

                if (!courseId) return;

                $.ajax({
                    url: "{{ route('admin.sbas.export.chapters') }}",
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
                    url: "{{ route('admin.sbas.export.lessons') }}",
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

                let url = "{{ route('admin.sbas.export') }}" + "?";
                if (courseId) url += "course_id=" + courseId + "&";
                if (chapterId) url += "chapter_id=" + chapterId + "&";
                if (lessonId) url += "lesson_id=" + lessonId + "&";

                window.location.href = url;
            });
            // ================================================================
            // ================================================================
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
    @endpush
@endsection
