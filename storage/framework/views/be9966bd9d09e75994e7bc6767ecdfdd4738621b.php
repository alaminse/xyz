<?php $__env->startSection('title', 'Written Assessment Details'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-12">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fa fa-file-text-o text-primary"></i>
                        Written Assessment Details
                    </h5>
                    <a href="<?php echo e(route('admin.writtenassessments.index')); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-md-3 text-muted font-weight-bold">Chapter</div>
                        <div class="col-md-9"><?php echo e($written->chapter->name ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted font-weight-bold">Lesson</div>
                        <div class="col-md-9"><?php echo e($written->lesson->name ?? '-'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 text-muted font-weight-bold">Paid</div>
                        <div class="col-md-9">
                            <span class="badge badge-<?php echo e($written->isPaid ? 'warning' : 'success'); ?>">
                                <?php echo e($written->isPaid ? 'Paid' : 'Free'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted font-weight-bold">Status</div>
                        <div class="col-md-9">
                            <span class="badge badge-<?php echo e($written->status ? 'success' : 'secondary'); ?>">
                                <?php echo e($written->status ? 'Active' : 'Inactive'); ?>

                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold mb-0">Question Groups</h6>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addGroupModal">
                            <i class="fa fa-plus"></i> Add Question Group
                        </button>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $written->questionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $questions = is_array($group->questions)
                            ? $group->questions
                            : json_decode($group->questions, true) ?? [];
                    ?>
                    <div class="card mb-3 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                            <span class="font-weight-bold text-muted" style="font-size:13px;">
                                Group <?php echo e($index + 1); ?>

                                <span class="text-muted font-weight-normal">· <?php echo e(count($questions)); ?> question(s)</span>
                            </span>
                            <div>
                                <button class="btn btn-sm btn-outline-primary mr-1 edit-group-btn"
                                    data-id="<?php echo e($group->id); ?>"
                                    data-questions='<?php echo e(json_encode($questions)); ?>'>
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a href="<?php echo e(route('admin.writtenassessments.question.group.destroy', $group->id)); ?>"
                                   onclick="showDeleteConfirmation(event)"
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qi => $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded p-3 mb-2 bg-white d-flex justify-content-between align-items-start">
                                <div style="flex:1;">
                                    <div class="text-uppercase text-muted mb-1" style="font-size:10px; font-weight:600; letter-spacing:.05em;">Question <?php echo e($qi + 1); ?></div>
                                    <div class="mb-2" style="font-size:14px;"><?php echo e($qa['question']); ?></div>
                                    <div class="text-uppercase text-muted mb-1" style="font-size:10px; font-weight:600; letter-spacing:.05em;">Answer</div>
                                    <div class="text-muted" style="font-size:13px;"><?php echo $qa['answer']; ?></div>
                                </div>
                                <form action="<?php echo e(route('admin.writtenassessments.question.group.update', $group->id)); ?>" method="POST" class="ml-2">
                                    <?php echo csrf_field(); ?>
                                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($k !== $qi): ?>
                                            <input type="hidden" name="questions[<?php echo e($k); ?>][question]" value="<?php echo e($q['question']); ?>">
                                            <input type="hidden" name="questions[<?php echo e($k); ?>][answer]" value="<?php echo e($q['answer']); ?>">
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this question?')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-muted py-4 border rounded">
                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                        <p class="mb-0">No question groups yet. Click "Add Question Group" to get started.</p>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <div class="text-right">
                        <a href="<?php echo e(route('admin.writtenassessments.edit', $written->id)); ?>"
                           class="btn btn-sm btn-primary mr-1">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo e(route('admin.writtenassessments.destroy', $written->id)); ?>"
                           onclick="showDeleteConfirmation(event)"
                           class="btn btn-sm btn-danger <?php echo e($written->status == 3 ? 'disabled' : ''); ?>">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="addGroupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.writtenassessments.question.group.store', $written->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Question Group</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="addQList">
                    <div class="qa-item border rounded p-3 mb-3 bg-light position-relative" id="add-qa-0">
                        <div class="form-group mb-2">
                            <label style="font-size:12px;" class="text-muted font-weight-bold">Question</label>
                            <input type="text" name="questions[0][question]" class="form-control form-control-sm" placeholder="Enter question" required>
                        </div>
                        <div class="form-group mb-0">
                            <label style="font-size:12px;" class="text-muted font-weight-bold">Answer</label>
                            <textarea name="questions[0][answer]" class="form-control form-control-sm summernote-add" rows="3" placeholder="Enter answer" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addAnotherQBtn">
                        <i class="fa fa-plus"></i> Add Another Question
                    </button>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary mr-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Group</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editGroupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editGroupForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Edit Question Group</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="editQList"></div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="editAnotherQBtn">
                        <i class="fa fa-plus"></i> Add Another Question
                    </button>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary mr-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Update Group</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    let addQACount = 1;
    let editQACount = 0;

    function initSummernote(el) {
        $(el).summernote({
            height: 200,
            placeholder: 'Enter answer',
            callbacks: {
                onImageUpload: function(files) {
                    var editor = $(this);
                    var data = new FormData();
                    data.append('image', files[0]);
                    $.ajax({
                        url: '<?php echo e(route('admin.summernote.upload')); ?>',
                        method: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            $(editor).summernote('insertImage', response.url);
                        }
                    });
                }
            }
        });
    }

    function removeQA(id) {
        const el = document.getElementById(id);
        if (el) {
            $(el).find('.summernote-add, .summernote-edit').each(function() {
                if ($(this).data('summernote')) $(this).summernote('destroy');
            });
            el.remove();
        }
    }

    // Add modal — init summernote when shown
    $('#addGroupModal').on('shown.bs.modal', function() {
        $('.summernote-add').each(function() {
            if (!$(this).data('summernote')) initSummernote(this);
        });
    });

    // Add modal — destroy and reset when hidden
    $('#addGroupModal').on('hidden.bs.modal', function() {
        $('.summernote-add').each(function() {
            if ($(this).data('summernote')) $(this).summernote('destroy');
        });
        $('#addQList').html(`
            <div class="qa-item border rounded p-3 mb-3 bg-light position-relative" id="add-qa-0">
                <div class="form-group mb-2">
                    <label style="font-size:12px;" class="text-muted font-weight-bold">Question</label>
                    <input type="text" name="questions[0][question]" class="form-control form-control-sm" placeholder="Enter question" required>
                </div>
                <div class="form-group mb-0">
                    <label style="font-size:12px;" class="text-muted font-weight-bold">Answer</label>
                    <textarea name="questions[0][answer]" class="form-control form-control-sm summernote-add" rows="3" placeholder="Enter answer" required></textarea>
                </div>
            </div>`);
        addQACount = 1;
    });

    // Edit modal — destroy summernote when hidden
    $('#editGroupModal').on('hidden.bs.modal', function() {
        $('.summernote-edit').each(function() {
            if ($(this).data('summernote')) $(this).summernote('destroy');
        });
        $('#editQList').html('');
        editQACount = 0;
    });

    // Add another question in ADD modal
    $('#addAnotherQBtn').on('click', function() {
        const count = addQACount;
        const id = 'add-qa-' + count;
        const div = document.createElement('div');
        div.className = 'qa-item border rounded p-3 mb-3 bg-light position-relative';
        div.id = id;
        div.innerHTML = `
            <button type="button" class="btn btn-sm btn-link text-danger position-absolute" style="top:8px;right:8px;" onclick="removeQA('${id}')">
                <i class="fa fa-times"></i>
            </button>
            <div class="form-group mb-2">
                <label style="font-size:12px;" class="text-muted font-weight-bold">Question</label>
                <input type="text" name="questions[${count}][question]" class="form-control form-control-sm" placeholder="Enter question" required>
            </div>
            <div class="form-group mb-0">
                <label style="font-size:12px;" class="text-muted font-weight-bold">Answer</label>
                <textarea name="questions[${count}][answer]" class="form-control form-control-sm summernote-add" rows="3" placeholder="Enter answer" required></textarea>
            </div>`;
        document.getElementById('addQList').appendChild(div);
        initSummernote(div.querySelector('.summernote-add'));
        addQACount++;
    });

    // Add another question in EDIT modal
    $('#editAnotherQBtn').on('click', function() {
        const count = editQACount;
        const id = 'edit-qa-' + count;
        const div = document.createElement('div');
        div.className = 'qa-item border rounded p-3 mb-3 bg-light position-relative';
        div.id = id;
        div.innerHTML = `
            <button type="button" class="btn btn-sm btn-link text-danger position-absolute" style="top:8px;right:8px;" onclick="removeQA('${id}')">
                <i class="fa fa-times"></i>
            </button>
            <div class="form-group mb-2">
                <label style="font-size:12px;" class="text-muted font-weight-bold">Question</label>
                <input type="text" name="questions[${count}][question]" class="form-control form-control-sm" placeholder="Enter question" required>
            </div>
            <div class="form-group mb-0">
                <label style="font-size:12px;" class="text-muted font-weight-bold">Answer</label>
                <textarea name="questions[${count}][answer]" class="form-control form-control-sm summernote-edit" rows="3" placeholder="Enter answer" required></textarea>
            </div>`;
        document.getElementById('editQList').appendChild(div);
        initSummernote(div.querySelector('.summernote-edit'));
        editQACount++;
    });

    // Edit group button click
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-group-btn');
        if (!btn) return;

        const groupId = btn.getAttribute('data-id');
        let questions;

        try {
            questions = JSON.parse(btn.getAttribute('data-questions'));
        } catch(err) {
            console.error('Failed to parse questions:', err);
            return;
        }

        const form = document.getElementById('editGroupForm');
        form.action = `/admin/writtenassessments/question-group/update/${groupId}`;

        const list = document.getElementById('editQList');
        list.innerHTML = '';
        editQACount = 0;

        questions.forEach((qa, i) => {
            const id = 'edit-qa-' + i;
            const div = document.createElement('div');
            div.className = 'qa-item border rounded p-3 mb-3 bg-light position-relative';
            div.id = id;
            div.innerHTML = `
                <button type="button" class="btn btn-sm btn-link text-danger position-absolute" style="top:8px;right:8px;" onclick="removeQA('${id}')">
                    <i class="fa fa-times"></i>
                </button>
                <div class="form-group mb-2">
                    <label style="font-size:12px;" class="text-muted font-weight-bold">Question</label>
                    <input type="text" name="questions[${i}][question]" class="form-control form-control-sm" required>
                </div>
                <div class="form-group mb-0">
                    <label style="font-size:12px;" class="text-muted font-weight-bold">Answer</label>
                    <textarea name="questions[${i}][answer]" class="form-control form-control-sm summernote-edit" rows="3" required></textarea>
                </div>`;

            div.querySelector('input').value = qa.question;
            list.appendChild(div);

            initSummernote(div.querySelector('.summernote-edit'));
            $(div.querySelector('.summernote-edit')).summernote('code', qa.answer);

            editQACount = i + 1;
        });

        $('#editGroupModal').modal('show');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/written-assessment/show.blade.php ENDPATH**/ ?>