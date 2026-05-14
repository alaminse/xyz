<?php $__env->startSection('title', 'Upload Secure PDF'); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Upload Secure PDF</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-warning text-white" href="<?php echo e(route('admin.secure-pdfs.index')); ?>">Back</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
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

                <form method="POST" action="<?php echo e(route('admin.secure-pdfs.store')); ?>" enctype="multipart/form-data"
                    id="upload-form">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">
                                Courses <span class="text-danger">*</span>
                            </label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e(collect(old('course_ids'))->contains($course->id) ? 'selected' : ''); ?>>
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">
                                Chapter <span class="text-danger">*</span>
                            </label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="" disabled selected>Select Chapter</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">
                                Lesson <span class="text-danger">*</span>
                            </label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="" disabled selected>Select Lesson</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">IsPaid</label><br>
                            <input type="checkbox" name="isPaid" id="isPaid" class="mt-2" value="1"
                                <?php echo e(old('isPaid') == 1 ? 'checked' : ''); ?>>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" class="form-control" value="<?php echo e(old('title')); ?>"
                                placeholder="Enter PDF title" required maxlength="500">
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">
                                Total Pages <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="total_pages" class="form-control" value="<?php echo e(old('total_pages')); ?>"
                                placeholder="e.g. 45" min="1" required>
                            <small class="text-muted">Enter exact page count of the PDF.</small>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional"><?php echo e(old('description')); ?></textarea>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <label class="form-label">
                                PDF File <span class="text-danger">*</span>
                            </label>

                            <div id="drop-zone"
                                style="border:2px dashed #ccc; border-radius:8px;
                                    padding:30px; text-align:center;
                                    cursor:pointer; background:#fafafa;">
                                <i class="fa fa-file-pdf-o fa-3x" style="color:#d9534f;"></i>
                                <p style="margin:10px 0 4px;">
                                    Drag & drop PDF here or
                                    <strong style="color:#337ab7">click to browse</strong>
                                </p>
                                <small class="text-muted">PDF only &bull; Max 50MB</small>
                                <input type="file" name="pdf_file" id="pdf-input" accept=".pdf" required
                                    style="display:none">
                            </div>

                            <div id="file-preview" class="alert alert-info mt-2" style="display:none">
                                <i class="fa fa-file-pdf-o"></i>
                                <strong id="f-name"></strong> &mdash;
                                <span id="f-size"></span>
                                <button type="button" class="close" onclick="clearFile()">&times;</button>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">Allow Print</label><br>
                            <input type="checkbox" name="allow_print" value="1"
                                <?php echo e(old('allow_print') == 1 ? 'checked' : ''); ?>>
                            <small class="text-muted">
                                Check to allow users to print this PDF.
                            </small>
                        </div>
                    </div>

                    <div id="upload-progress" style="display:none" class="mb-3">
                        <label>
                            <i class="fa fa-spinner fa-spin"></i>
                            Uploading, please wait...
                        </label>
                        <div class="progress">
                            <div id="prog-bar" class="progress-bar progress-bar-striped active"
                                style="width:0%; background:#5cb85c;">0%</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fa fa-upload"></i> Upload Securely
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
        $(document).ready(function() {
            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'secure_pdf',
                allowMultipleCourses: true,
            });

            const oldCourseIds = <?php echo json_encode(old('course_ids', []), 512) ?>;
            const oldChapterId = '<?php echo e(old('chapter_id')); ?>';
            const oldLessonId = '<?php echo e(old('lesson_id')); ?>';
            if (oldCourseIds.length > 0) {
                formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
            }
        });

        // Drag & drop
        var dz = document.getElementById('drop-zone');
        var pi = document.getElementById('pdf-input');

        dz.addEventListener('click', function() {
            pi.click();
        });
        dz.addEventListener('dragover', function(e) {
            e.preventDefault();
            dz.style.borderColor = '#337ab7';
            dz.style.background = '#eaf4ff';
        });
        dz.addEventListener('dragleave', function() {
            dz.style.borderColor = '#ccc';
            dz.style.background = '#fafafa';
        });
        dz.addEventListener('drop', function(e) {
            e.preventDefault();
            dz.style.borderColor = '#ccc';
            dz.style.background = '#fafafa';
            var f = e.dataTransfer.files[0];
            if (f && f.type === 'application/pdf') {
                pi.files = e.dataTransfer.files;
                showPreview(f);
            } else {
                alert('PDF files only.');
            }
        });
        pi.addEventListener('change', function() {
            if (this.files[0]) showPreview(this.files[0]);
        });

        function showPreview(f) {
            document.getElementById('f-name').textContent = f.name;
            document.getElementById('f-size').textContent =
                f.size >= 1048576 ?
                (f.size / 1048576).toFixed(2) + ' MB' :
                (f.size / 1024).toFixed(1) + ' KB';
            document.getElementById('file-preview').style.display = 'block';
            dz.style.display = 'none';
        }

        function clearFile() {
            pi.value = '';
            document.getElementById('file-preview').style.display = 'none';
            dz.style.display = 'block';
        }

        document.getElementById('upload-form').addEventListener('submit', function() {
            document.getElementById('upload-progress').style.display = 'block';
            document.getElementById('submit-btn').disabled = true;
            var bar = document.getElementById('prog-bar'),
                pct = 0;
            var iv = setInterval(function() {
                pct = Math.min(pct + Math.random() * 8, 92);
                bar.style.width = Math.round(pct) + '%';
                bar.textContent = Math.round(pct) + '%';
                if (pct >= 92) clearInterval(iv);
            }, 300);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/secure-pdfs/create.blade.php ENDPATH**/ ?>