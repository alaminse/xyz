<?php $__env->startSection('title', 'Edit Lecture Video'); ?>

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
        .video-preview {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }
        .current-video-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin-bottom: 15px;
        }
        .upload-section {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin-top: 15px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edit Lecture Video</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="btn btn-warning text-white" href="<?php echo e(route('admin.lecturevideos.index')); ?>">
                        <i class="fa fa-arrow-left"></i> Back
                    </a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form action="<?php echo e(route('admin.lecturevideos.update', $video->id)); ?>" method="POST" id="videoForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">
                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">Courses <span class="text-danger">*</span></label>
                            <select name="course_ids[]" id="courseSelect" class="form-control select2" multiple required>
                                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e(collect(old('course_ids', $video->courses->pluck('id')))->contains($course->id) ? 'selected' : ''); ?>>
                                        <?php echo e($course->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">Chapter <span class="text-danger">*</span></label>
                            <select name="chapter_id" id="chapterSelect" class="form-control select2" required>
                                <option value="">Select Chapter</option>
                            </select>
                        </div>

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">Lesson <span class="text-danger">*</span></label>
                            <select name="lesson_id" id="lessonSelect" class="form-control select2" required>
                                <option value="">Select Lesson</option>
                            </select>
                        </div>

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label d-block">Content Type</label>
                            <div class="mt-2">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="isPaid" value="1"
                                        <?php echo e(old('isPaid', $video->isPaid) ? 'checked' : ''); ?>>
                                    <span class="ml-2">Paid Content</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Video Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="<?php echo e(old('title', $video->title)); ?>" required>
                        </div>

                        
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option value="1" <?php echo e(old('status', $video->status) == 1 ? 'selected' : ''); ?>>Active</option>
                                <option value="2" <?php echo e(old('status', $video->status) == 2 ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>

                        
                        <div class="col-sm-12 mb-3">
                            <div class="current-video-info">
                                <h6 class="mb-2"><i class="fa fa-video-camera"></i> Current Video</h6>
                                <?php if($video->youtube_link): ?>
                                    <span class="badge badge-danger">
                                        <i class="fa fa-youtube-play"></i> YouTube Video
                                    </span>
                                    <p class="mb-0 mt-2"><small>URL: <?php echo e($video->youtube_link); ?></small></p>
                                <?php elseif($video->uploaded_link): ?>
                                    <span class="badge badge-primary">
                                        <i class="fa fa-cloud"></i> Bunny.net Video
                                    </span>
                                    <p class="mb-0 mt-2"><small>Video ID: <code><?php echo e($video->uploaded_link); ?></code></small></p>
                                <?php else: ?>
                                    <span class="badge badge-secondary">No video uploaded</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="col-sm-12 mb-3">
                            <label class="form-label">Change Video Source</label>
                            <select id="uploadMethod" class="form-control">
                                <option value="">-- Keep Current Video --</option>
                                <option value="youtube">📺 Update with YouTube Link</option>
                                <option value="bunny">🐰 Upload New Video to Bunny.net</option>
                            </select>
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> Leave as "Keep Current Video" to not change the video source
                            </small>
                        </div>

                        
                        <div id="youtubeSection" class="col-sm-12 mb-3" style="display:none;">
                            <div class="upload-section">
                                <label class="form-label">New YouTube URL</label>
                                <input type="url" name="youtube_link" class="form-control"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       value="<?php echo e(old('youtube_link')); ?>">
                                <small class="text-muted">
                                    <i class="fa fa-warning"></i> This will replace the current video
                                </small>
                            </div>
                        </div>

                        
                        <div id="bunnyUploadSection" class="col-sm-12 mb-3" style="display:none;">
                            <div class="upload-section">
                                <label class="form-label">Upload New Video File</label>
                                <input type="file" id="bunnyVideoFile" class="form-control" accept="video/*">
                                <input type="hidden" name="uploaded_link" id="bunnyVideoId" value="">
                                <small class="text-muted d-block mt-2">
                                    <i class="fa fa-warning"></i> This will replace the current video<br>
                                    <i class="fa fa-info-circle"></i> Max: 5GB | Formats: MP4, MOV, AVI, MKV
                                </small>

                                
                                <div id="bunnyProgress" class="mt-3"></div>
                            </div>
                        </div>

                        
                        <div class="col-sm-12 mb-3">
                            <label class="form-label d-block">Current Video Preview</label>
                            <div class="video-preview">
                                <?php if($video->youtube_link): ?>
                                    
                                    <iframe width="100%" height="450"
                                            src="<?php echo e($video->youtube_link); ?>"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>

                                <?php elseif($video->uploaded_link): ?>
                                    
                                    <?php
                                        $libraryId = env('BUNNY_VIDEO_LIBRARY_ID');
                                        $videoId = $video->uploaded_link;
                                        $bunnyUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}";
                                    ?>

                                    <iframe width="100%" height="450"
                                            src="<?php echo e($bunnyUrl); ?>"
                                            frameborder="0"
                                            loading="lazy"
                                            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>

                                <?php else: ?>
                                    <div class="alert alert-secondary m-3">
                                        <i class="fa fa-film"></i> No video available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="fa fa-save"></i> Update Video
                            </button>
                            <a href="<?php echo e(route('admin.lecturevideos.show', $video->id)); ?>" class="btn btn-info">
                                <i class="fa fa-eye"></i> View Details
                            </a>
                            <a href="<?php echo e(route('admin.lecturevideos.index')); ?>" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('backend/vendors/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?php echo e(asset('backend/js/dependent-dropdown-handler.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            const formHandler = new FormHandler({
                courseSelect: '#courseSelect',
                chapterSelect: '#chapterSelect',
                lessonSelect: '#lessonSelect',
                chaptersUrl: '/admin/chapters/get',
                lessonsUrl: '/admin/lessons/get',
                moduleType: 'videos',
                allowMultipleCourses: true,
                csrfToken: '<?php echo e(csrf_token()); ?>'
            });

            const courseIds = <?php echo json_encode(old('course_ids', $video->courses->pluck('id')), 512) ?>;
            const chapterId = '<?php echo e(old('chapter_id', $video->chapter_id)); ?>';
            const lessonId = '<?php echo e(old('lesson_id', $video->lesson_id)); ?>';

            formHandler.initializeWithData(courseIds, chapterId, lessonId);

            // Upload method toggle
            $('#uploadMethod').on('change', function() {
                const method = $(this).val();

                if (method === 'youtube') {
                    $('#youtubeSection').slideDown();
                    $('#bunnyUploadSection').slideUp();
                    $('#bunnyVideoId').val('');
                } else if (method === 'bunny') {
                    $('#youtubeSection').slideUp();
                    $('#bunnyUploadSection').slideDown();
                    $('input[name="youtube_link"]').val('');
                } else {
                    $('#youtubeSection').slideUp();
                    $('#bunnyUploadSection').slideUp();
                }
            });

            // Bunny.net Chunk Upload (same as create page)
            $('#bunnyVideoFile').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const maxSize = 5 * 1024 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('❌ File too large! Maximum size: 5GB');
                    $(this).val('');
                    return;
                }

                const title = $('#title').val() || file.name;
                const chunkSize = 5 * 1024 * 1024;
                const totalChunks = Math.ceil(file.size / chunkSize);
                let currentChunk = 0;
                let uploadedBytes = 0;
                const startTime = Date.now();

                $('#bunnyProgress').html(`
                    <div class="alert alert-info">
                        <strong><i class="fa fa-upload"></i> Uploading:</strong> ${file.name}
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
                        success: function(data) {
                            if (!data.success) {
                                throw new Error(data.error || 'Upload failed');
                            }

                            currentChunk++;
                            uploadedBytes += chunkBlob.size;

                            const progress = Math.round((currentChunk / totalChunks) * 100);
                            const uploadedMB = (uploadedBytes / (1024 * 1024)).toFixed(2);
                            const totalMB = (file.size / (1024 * 1024)).toFixed(2);
                            const elapsedTime = (Date.now() - startTime) / 1000;
                            const speedMBps = (uploadedBytes / (1024 * 1024)) / elapsedTime;

                            $('.progress-bar').css('width', progress + '%').text(progress + '%');
                            $('#uploadStatus').text(`${uploadedMB} MB / ${totalMB} MB uploaded`);
                            $('#uploadSpeed').text(`Speed: ${speedMBps.toFixed(2)} MB/s`);

                            if (currentChunk < totalChunks) {
                                uploadChunk();
                            } else {
                                if (data.uploaded_link) {
                                    $('#bunnyVideoId').val(data.uploaded_link);
                                    $('#bunnyProgress').html(`
                                        <div class="alert alert-success">
                                            <i class="fa fa-check-circle"></i>
                                            <strong>✅ Upload Complete!</strong>
                                            <br>Video ID: <code>${data.uploaded_link}</code>
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
                                </div>
                            `);
                            $('#submitBtn').prop('disabled', false);
                            $('#bunnyVideoFile').val('');
                        }
                    });
                }

                uploadChunk();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lecture-video/edit.blade.php ENDPATH**/ ?>