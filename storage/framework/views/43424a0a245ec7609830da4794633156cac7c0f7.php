<?php $__env->startSection('title', 'Edit Secure PDF'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Secure PDF</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-warning text-white"
                       href="<?php echo e(route('admin.secure-pdfs.index')); ?>">Back</a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="alert alert-info">
                <i class="fa fa-file-pdf-o"></i>
                <strong>Current File:</strong> <?php echo e($securePdf->original_name); ?>

                &bull; <?php echo e($securePdf->file_size_formatted); ?>

                &bull; <?php echo e($securePdf->total_pages); ?> pages
            </div>

            <form method="POST"
                  action="<?php echo e(route('admin.secure-pdfs.update', $securePdf->id)); ?>"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="row">
                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">
                            Courses <span class="text-danger">*</span>
                        </label>
                        <select name="course_ids[]" id="courseSelect"
                                class="form-control select2" multiple required>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>"
                                    <?php echo e(collect(old('course_ids', $securePdf->courses->pluck('id')))->contains($course->id) ? 'selected' : ''); ?>>
                                    <?php echo e($course->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">
                            Chapter <span class="text-danger">*</span>
                        </label>
                        <select name="chapter_id" id="chapterSelect"
                                class="form-control select2" required>
                            <option value="" disabled selected>Select Chapter</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Lesson</label>
                        <select name="lesson_id" id="lessonSelect"
                                class="form-control select2">
                            <option value="" disabled selected>Select Lesson</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">IsPaid</label><br>
                        <input type="checkbox" name="isPaid" id="isPaid"
                               class="mt-2" value="1"
                               <?php echo e(old('isPaid', $securePdf->isPaid) == 1 ? 'checked' : ''); ?>>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" class="form-control"
                               value="<?php echo e(old('title', $securePdf->title)); ?>"
                               required maxlength="500">
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Total Pages</label>
                        <input type="number" name="total_pages" class="form-control"
                               value="<?php echo e(old('total_pages', $securePdf->total_pages)); ?>"
                               min="0">
                    </div>

                    <div class="col-sm-12 mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"
                                  rows="3"><?php echo e(old('description', $securePdf->description)); ?></textarea>
                    </div>

                    <div class="col-sm-12 mb-2">
                        <label class="form-label">
                            Replace PDF File
                            <small class="text-muted">
                                (optional — leave blank to keep current)
                            </small>
                        </label>
                        <input type="file" name="pdf_file" id="rep-input"
                               class="form-control-file" accept=".pdf">
                        <small class="text-muted">PDF only &bull; Max 50MB</small>
                        <div id="rep-preview" class="alert alert-warning mt-2"
                             style="display:none">
                            <i class="fa fa-exclamation-triangle"></i>
                            Will replace with: <strong id="rep-name"></strong>
                        </div>
                    </div>

                    <div class="col-12 mb-2">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-control" style="width:200px">
                            <option value="1" <?php echo e($securePdf->is_active ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e(!$securePdf->is_active ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-6 mb-2">
                        <label class="form-label">Allow Print</label><br>
                        <input type="checkbox" name="allow_print" value="1"
                               <?php echo e(old('allow_print', $securePdf->allow_print) == 1 ? 'checked' : ''); ?>>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <button type="reset" class="btn btn-success">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>
<script>
$(document).ready(function () {
    const formHandler = new FormHandler({
        courseSelect: '#courseSelect',
        chapterSelect: '#chapterSelect',
        lessonSelect: '#lessonSelect',
        chaptersUrl: '/admin/chapters/get',
        lessonsUrl: '/admin/lessons/get',
        moduleType: 'secure_pdf',
        allowMultipleCourses: true,
    });

    const courseIds = <?php echo json_encode(old('course_ids', $securePdf->courses->pluck('id')), 512) ?>;
    const chapterId = '<?php echo e(old("chapter_id", $securePdf->chapter_id)); ?>';
    const lessonId  = '<?php echo e(old("lesson_id",  $securePdf->lesson_id)); ?>';
    formHandler.initializeWithData(courseIds, chapterId, lessonId);
});

document.getElementById('rep-input').addEventListener('change', function () {
    var f = this.files[0];
    if (f) {
        document.getElementById('rep-name').textContent = f.name;
        document.getElementById('rep-preview').style.display = 'block';
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/secure-pdfs/edit.blade.php ENDPATH**/ ?>