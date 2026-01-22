<?php $__env->startSection('title', 'Lecture Video'); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('backend/vendors/select2/dist/css/select2.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .progress {
            height: 25px;
            margin-top: 10px;
        }

        .progress-bar {
            line-height: 25px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Lecture Video <small></small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-warning text-white" href="<?php echo e(route('admin.lecturevideos.index')); ?>"> Back</a></li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?php echo e(route('admin.lecturevideos.store')); ?>" method="POST" id="videoForm">
                    <?php echo csrf_field(); ?>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label class="form-label">Courses <span class="required text-danger">*</span></label>
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
                            <label for="chapterSelect" class="form-label">Chapter <span
                                    class="required text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Chapter</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="lessonSelect" class="form-label">Lesson <span
                                    class="required text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="" selected disabled>Select Lesson</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label for="isPaid" class="form-label">IsPaid <span
                                    class="required text-danger">*</span></label>
                            <br>
                            <input type="checkbox" name="isPaid" id="isPaid" class="mt-2" value="1"
                                <?php echo e(old('isPaid') == 1 ? 'checked' : ''); ?>>
                        </div>

                        <div class="col-sm-12 mb-2">
                            <label for="title" class="form-label">Title <span
                                    class="required text-danger">*</span></label>
                            <input class="form-control" id="title" name="title" placeholder="Enter title"
                                value="<?php echo e(old('title')); ?>" required />
                        </div>

                        <div class="col-sm-12 mb-2">
                            <label class="form-label">Upload Method <span class="required text-danger">*</span></label>
                            <select name="upload_method" id="uploadMethod" class="form-control" required>
                                <option value="youtube" <?php echo e(old('upload_method') == 'youtube' ? 'selected' : ''); ?>>YouTube
                                    Link</option>
                                <option value="bunny" <?php echo e(old('upload_method') == 'bunny' ? 'selected' : ''); ?>>Upload to
                                    Bunny.net</option>
                            </select>
                        </div>

                        <!-- YouTube Section -->
                        <div id="youtubeSection" class="col-sm-12 mb-2">
                            <label class="form-label">YouTube Link</label>
                            <input type="text" name="youtube_link" class="form-control" placeholder="Enter YouTube URL"
                                value="<?php echo e(old('youtube_link')); ?>">
                        </div>

                        <!-- Bunny.net Section -->
                        <div id="bunnyUploadSection" class="col-sm-12 mb-2" style="display:none;">
                            <label class="form-label">Upload Video (Bunny CDN)</label>
                            <input type="file" id="bunnyVideoFile" class="form-control" accept="video/*">
                            <input class="mt-2 form-control" type="input" name="uploaded_link" id="bunnyVideoId"
                                value="<?php echo e(old('uploaded_link')); ?>" placeholder="Bunny video ID">

                            <!-- Progress Bar -->
                            <div id="bunnyProgress" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 mt-3">
                            <button type='submit' class="btn btn-primary" id="submitBtn">Create</button>
                            <button type='reset' class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>

        <script>
            $(document).ready(function() {
                // Dependent dropdown handler
                const formHandler = new FormHandler({
                    courseSelect: '#courseSelect',
                    chapterSelect: '#chapterSelect',
                    lessonSelect: '#lessonSelect',
                    chaptersUrl: '/admin/chapters/get',
                    lessonsUrl: '/admin/lessons/get',
                    moduleType: 'videos',
                    allowMultipleCourses: true,
                    summernoteSelector: '.summernote',
                    summernoteHeight: 300,
                    csrfToken: '<?php echo e(csrf_token()); ?>',
                    summernoteUploadUrl: "<?php echo e(route('admin.summernote.upload')); ?>"
                });

                // Restore old values
                const oldCourseIds = <?php echo json_encode(old('course_ids', []), 512) ?>;
                const oldChapterId = '<?php echo e(old('chapter_id')); ?>';
                const oldLessonId = '<?php echo e(old('lesson_id')); ?>';

                if (oldCourseIds && oldCourseIds.length > 0) {
                    formHandler.initializeWithData(oldCourseIds, oldChapterId, oldLessonId);
                }

                // Upload method toggle
                $('#uploadMethod').on('change', function() {
                    const method = $(this).val();

                    if (method === 'youtube') {
                        $('#youtubeSection').show();
                        $('#bunnyUploadSection').hide();
                        $('#bunnyVideoId').val(''); // Clear bunny ID
                    } else if (method === 'bunny') {
                        $('#youtubeSection').hide();
                        $('#bunnyUploadSection').show();
                        $('input[name="youtube_link"]').val(''); // Clear youtube link
                    }
                });

                // Initialize on page load
                $('#uploadMethod').trigger('change');

                // Bunny.net Chunk Upload
                let uploadedVideoId = null;

                // Bunny.net Optimized Chunk Upload
                $('#bunnyVideoFile').on('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    // Validate file size (5GB max)
                    const maxSize = 5 * 1024 * 1024 * 1024; // 5GB
                    if (file.size > maxSize) {
                        alert('❌ File too large! Maximum size: 5GB');
                        $(this).val('');
                        return;
                    }

                    const title = $('#title').val() || file.name;
                    const chunkSize = 5 * 1024 * 1024; // 5MB chunks
                    const totalChunks = Math.ceil(file.size / chunkSize);
                    let currentChunk = 0;
                    let uploadedBytes = 0;
                    const startTime = Date.now();

                    // Initialize progress UI
                    $('#bunnyProgress').html(`
                        <div class="alert alert-info">
                            <strong>📤 Uploading:</strong> ${file.name}
                            <br><small>Size: ${(file.size / (1024 * 1024)).toFixed(2)} MB</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                role="progressbar" style="width: 0%">0%</div>
                        </div>
                        <div class="mt-2 d-flex justify-content-between">
                            <small id="uploadStatus">Preparing...</small>
                            <small id="uploadSpeed">Speed: --</small>
                        </div>
                    `);

                    $('#submitBtn').prop('disabled', true);

                    function uploadChunk() {
                        const start = currentChunk * chunkSize;
                        const end = Math.min(start + chunkSize, file.size);
                        const chunkBlob = file.slice(start, end);
                        const chunkStartTime = Date.now();

                        $.ajax({
                            url: '<?php echo e(route('admin.lecturevideos.upload.bunny')); ?>?' + $.param({
                                title: title,
                                chunk: currentChunk,
                                totalChunks: totalChunks,
                                fileName: file.name,
                                _token: '<?php echo e(csrf_token()); ?>'
                            }),
                            method: 'POST',
                            data: chunkBlob,
                            processData: false,
                            contentType: 'application/octet-stream',
                            cache: false,
                            xhr: function() {
                                const xhr = new window.XMLHttpRequest();
                                return xhr;
                            },
                            success: function(data) {
                                console.log(data);

                                if (!data.success) {
                                    throw new Error(data.error || 'Upload failed');
                                }

                                currentChunk++;
                                uploadedBytes += chunkBlob.size;

                                // Calculate progress
                                const progress = Math.round((currentChunk / totalChunks) * 100);
                                const uploadedMB = (uploadedBytes / (1024 * 1024)).toFixed(2);
                                const totalMB = (file.size / (1024 * 1024)).toFixed(2);

                                // Calculate speed
                                const elapsedTime = (Date.now() - startTime) / 1000;
                                const speedMBps = (uploadedBytes / (1024 * 1024)) / elapsedTime;

                                // Update UI
                                $('.progress-bar').css('width', progress + '%').text(progress + '%');
                                $('#uploadStatus').text(`${uploadedMB} MB / ${totalMB} MB uploaded`);
                                $('#uploadSpeed').text(`Speed: ${speedMBps.toFixed(2)} MB/s`);

                                if (currentChunk < totalChunks) {
                                    uploadChunk(); // Next chunk
                                } else {
                                    // Upload complete
                                    if (data.uploaded_link) {
                                        $('#bunnyVideoId').val(data.uploaded_link);
                                        $('#bunnyProgress').html(`
                                            <div class="alert alert-success">
                                                <i class="fa fa-check-circle"></i>
                                                <strong>✅ Upload Complete!</strong>
                                                <br>Video is being processed on Bunny.net
                                            </div>
                                        `);
                                        $('#submitBtn').prop('disabled', false);
                                    }
                                }
                            },
                            error: function(xhr) {
                                console.error('Upload error:', xhr);
                                const errorMsg = xhr.responseJSON?.error || xhr.responseText || 'Network error';
                                $('#bunnyProgress').html(`
                                    <div class="alert alert-danger">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <strong>❌ Upload Failed</strong>
                                        <br>${errorMsg}
                                        <br><small>Please check your internet and try again</small>
                                    </div>
                                `);
                                $('#submitBtn').prop('disabled', false);
                                $('#bunnyVideoFile').val('');
                            }
                        });
                    }

                    uploadChunk(); // Start upload
                });

                // Form submission validation
                $('#videoForm').on('submit', function(e) {
                    const uploadMethod = $('#uploadMethod').val();

                    if (uploadMethod === 'bunny' && !$('#bunnyVideoId').val()) {
                        e.preventDefault();
                        alert('Please wait for video upload to complete!');
                        return false;
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lecture-video/create.blade.php ENDPATH**/ ?>