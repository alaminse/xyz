<?php $__env->startSection('title', 'Lecture Video Details'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    <style>
        #video-container {
            position: relative;
            width: 100%;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-watermark {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.75);
            color: rgba(255, 255, 255, 0.7);
            padding: 6px 12px;
            font-size: 11px;
            border-radius: 4px;
            pointer-events: none;
            z-index: 9999;
            font-family: 'Courier New', monospace;
            backdrop-filter: blur(5px);
        }

        .bunny-video-iframe {
            width: 100%;
            height: 500px;
            border: none;
            border-radius: 8px;
        }

        /* Prevent text selection */
        #video-container * {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        /* Loading animation */
        .video-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 14px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card">
                <div class="card-body bg-white text-dark">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><?php echo e($video->title); ?></h5>
                        <a class="btn btn-sm btn-warning"
                            href="<?php echo e(route('videos.details', ['course' => $course_slug, 'chapter' => $video->chapter?->slug, 'lesson' => $video->lesson?->slug])); ?>">
                            ← Back to Course
                        </a>
                    </div>

                    <section id="info-utile">
                        <?php if(isset($video)): ?>
                                <div class="row">
                                    
                                    <div class="col-12 mb-4">
                                        <div id="video-container">

                                            <?php if($video->uploaded_link && $signedUrl): ?>
                                                
                                                <div style="position: relative;">
                                                    <div class="video-loading">Loading video...</div>

                                                    <iframe
                                                        src="<?php echo e($signedUrl); ?>"
                                                        class="bunny-video-iframe"
                                                        loading="lazy"
                                                        allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                                        allowfullscreen
                                                        oncontextmenu="return false;"
                                                        onload="document.querySelector('.video-loading').style.display='none'">
                                                    </iframe>

                                                    
                                                    <div class="video-watermark">
                                                        🔒 <?php echo e(auth()->user()->email); ?>

                                                    </div>
                                                </div>

                                            <?php elseif($video->youtube_link): ?>
                                                
                                                <div style="position: relative;">
                                                    <iframe
                                                        width="100%"
                                                        height="500px"
                                                        src="<?php echo e($video->youtube_link); ?>"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen>
                                                    </iframe>

                                                    <div class="alert alert-warning mt-2" style="font-size: 12px;">
                                                        ⚠️ YouTube videos cannot be fully secured
                                                    </div>
                                                </div>

                                            <?php else: ?>
                                                <div class="alert alert-info text-center p-5">
                                                    <i class="fas fa-video fa-3x mb-3"></i>
                                                    <p class="mb-0">No video available for this lecture</p>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>

                                    
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="mb-3">📚 Video Information</h5>

                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Course:</strong>
                                                        <?php echo e($video->courses->pluck('name')->join(', ')); ?>

                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <strong>Chapter:</strong>
                                                        <?php echo e($video->getChapterName()); ?>

                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <strong>Lesson:</strong>
                                                        <?php echo e($video->getLessonName()); ?>

                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <strong>Access:</strong>
                                                        <?php if($video->isPaid): ?>
                                                            <span class="badge badge-warning">Paid Content</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-success">Free Content</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function() {

            // 🔒 Global Security Measures

            // Disable right-click
            document.addEventListener('contextmenu', e => {
                e.preventDefault();
                return false;
            });

            // Disable developer tools shortcuts
            document.addEventListener('keydown', function(e) {
                // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S
                if (e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
                    (e.ctrlKey && (e.key === 'u' || e.key === 's'))) {
                    e.preventDefault();
                    return false;
                }

                // PrintScreen
                if (e.key === 'PrintScreen') {
                    navigator.clipboard.writeText('');
                    alert('⚠️ Screenshots are not allowed');
                }
            });

            // Disable text selection
            document.onselectstart = () => false;
            document.ondragstart = () => false;

            // Blur video on tab change (optional)
            document.addEventListener('visibilitychange', function() {
                const iframe = document.querySelector('.bunny-video-iframe');
                if (iframe) {
                    if (document.hidden) {
                        iframe.style.filter = 'blur(10px)';
                    } else {
                        iframe.style.filter = 'none';
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/lecture-video/details.blade.php ENDPATH**/ ?>