<?php $__env->startSection('title', 'Manage Note Sections'); ?>
<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .btn-link:hover { text-decoration: none; }
        .fa-chevron-down { transition: transform 0.2s ease; }
        .btn-link[aria-expanded="true"] .fa-chevron-down { transform: rotate(180deg); }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12">
    <div class="x_panel">

        <div class="x_title">
            <h2 class="pull-left">Manage Sections</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <button type="button" class="btn btn-success btn-sm text-white" data-toggle="modal" data-target="#sectionModal" onclick="openCreateModal()">
                        <i class="fa fa-plus"></i> Add Section
                    </button>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.notes.index')); ?>" class="btn btn-warning btn-sm text-white">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">

                
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Information</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <small class="text-muted d-block">Courses</small>
                                <?php $__empty_1 = true; $__currentLoopData = $note->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <span class="badge badge-primary mr-1 mb-1"><?php echo e($course->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">Chapter</small>
                                <strong><?php echo e($note->chapter->name ?? '-'); ?></strong>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">Lesson</small>
                                <strong><?php echo e($note->lesson->name ?? '-'); ?></strong>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">IsPaid</small>
                                <strong><?php echo e($note->isPaid ? 'Yes' : 'No'); ?></strong>
                            </div>

                            <a href="<?php echo e(route('admin.notes.edit', $note->id)); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit Note Info
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-8">
                    <div class="accordion" id="sectionsAccordion">
                        <div id="sections-wrapper">
                            <?php $__empty_1 = true; $__currentLoopData = $note->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="card mb-2" id="section-card-<?php echo e($detail->id); ?>">
                                    <div class="card-header p-0" id="heading-<?php echo e($detail->id); ?>">
                                        <div class="d-flex justify-content-between align-items-center px-3 py-2">
                                            <button type="button"
                                                    class="btn btn-link text-left flex-grow-1 font-weight-bold text-dark p-0"
                                                    style="text-decoration:none;"
                                                    data-toggle="collapse"
                                                    data-target="#collapse-<?php echo e($detail->id); ?>"
                                                    aria-expanded="<?php echo e($index == 0 ? 'true' : 'false'); ?>"
                                                    aria-controls="collapse-<?php echo e($detail->id); ?>">
                                                <i class="fa fa-chevron-down mr-2"></i><?php echo e($detail->title); ?>

                                            </button>
                                            <div class="flex-shrink-0">
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="event.stopPropagation(); openEditModal(<?php echo e($detail->id); ?>)">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="event.stopPropagation(); deleteDetail(<?php echo e($detail->id); ?>)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapse-<?php echo e($detail->id); ?>"
                                         class="collapse <?php echo e($index == 0 ? 'show' : ''); ?>"
                                         aria-labelledby="heading-<?php echo e($detail->id); ?>"
                                         data-parent="#sectionsAccordion">
                                        <div class="card-body bg-light">
                                            <?php echo $detail->description; ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="alert alert-info" id="no-sections-alert">
                                    <i class="fa fa-info-circle"></i>
                                    No sections added yet. Click "Add Section" to create the first one.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionModalTitle">Add Section</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="sectionForm">
                <div class="modal-body">
                    <input type="hidden" id="detail_id" value="">

                    <div class="mb-3">
                        <label for="section_title" class="form-label">Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="section_title" required>
                        <small class="text-danger d-none" id="title-error"></small>
                    </div>

                    <div class="mb-3">
                        <label for="section_description" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="section_description" rows="5"></textarea>
                        <small class="text-danger d-none" id="description-error"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="sectionSubmitBtn">Save Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    const noteId = <?php echo e($note->id); ?>;
    const csrfToken = '<?php echo e(csrf_token()); ?>';

    const storeUrl = "<?php echo e(route('admin.notes.details.store', $note->id)); ?>";
    const getUrlBase = "<?php echo e(url('admin/notes/details')); ?>"; // + /{id}
    const updateUrlBase = "<?php echo e(url('admin/notes/details')); ?>"; // + /{id}
    const destroyUrlBase = "<?php echo e(url('admin/notes/details')); ?>"; // + /{id}

    $(document).ready(function () {
        $('.summernote').summernote({ height: 250 });

        // Keep chevron rotation in sync when collapse opens/closes
        $('#sectionsAccordion').on('show.bs.collapse', '.collapse', function () {
            $(`[data-target="#${this.id}"]`).attr('aria-expanded', 'true');
        });
        $('#sectionsAccordion').on('hide.bs.collapse', '.collapse', function () {
            $(`[data-target="#${this.id}"]`).attr('aria-expanded', 'false');
        });
    });

    function resetModal() {
        $('#detail_id').val('');
        $('#section_title').val('');
        $('.summernote').summernote('code', '');
        $('#title-error, #description-error').addClass('d-none').text('');
    }

    function openCreateModal() {
        resetModal();
        $('#sectionModalTitle').text('Add Section');
        $('#sectionModal').modal('show');
    }

    function openEditModal(detailId) {
        resetModal();
        $('#sectionModalTitle').text('Edit Section');

        $.ajax({
            url: `${getUrlBase}/${detailId}`,
            method: 'GET',
            success: function (response) {
                if (response.success) {
                    $('#detail_id').val(response.detail.id);
                    $('#section_title').val(response.detail.title);
                    $('.summernote').summernote('code', response.detail.description);
                    $('#sectionModal').modal('show');
                }
            },
            error: function () {
                alert('Failed to load section details.');
            }
        });
    }

    $('#sectionForm').on('submit', function (e) {
        e.preventDefault();

        const detailId = $('#detail_id').val();
        const isEdit = !!detailId;

        const payload = {
            _token: csrfToken,
            title: $('#section_title').val(),
            description: $('.summernote').summernote('code'),
        };

        let url = isEdit ? `${updateUrlBase}/${detailId}` : storeUrl;
        if (isEdit) {
            payload._method = 'PUT';
        }

        $('#sectionSubmitBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            method: 'POST', // Laravel method-spoofing via _method for PUT
            data: payload,
            success: function (response) {
                if (response.success) {
                    $('#sectionModal').modal('hide');
                    location.reload(); // simplest way to refresh the sections list
                } else {
                    if (response.message) alert(response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const res = xhr.responseJSON;
                    if (res.message) {
                        alert(res.message);
                    } else if (res.errors) {
                        if (res.errors.title) {
                            $('#title-error').removeClass('d-none').text(res.errors.title[0]);
                        }
                        if (res.errors.description) {
                            $('#description-error').removeClass('d-none').text(res.errors.description[0]);
                        }
                    }
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $('#sectionSubmitBtn').prop('disabled', false).text('Save Section');
            }
        });
    });

    function deleteDetail(detailId) {
        if (!confirm('Delete this section? This cannot be undone.')) return;

        $.ajax({
            url: `${destroyUrlBase}/${detailId}`,
            method: 'POST',
            data: {
                _token: csrfToken,
                _method: 'DELETE'
            },
            success: function (response) {
                if (response.success) {
                    $(`#section-card-${detailId}`).remove();
                } else {
                    alert(response.message || 'Delete failed.');
                }
            },
            error: function () {
                alert('Delete failed. Please try again.');
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/note/show.blade.php ENDPATH**/ ?>