<?php $__env->startSection('title', 'Lecture Video Details'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        /* Main Card */
        .lecture-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .lecture-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 20px;
            border: none;
            color: white;
        }

        .lecture-header h5 {
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
        }

        .back-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        /* Topic Section */
        .topic-section {
            background: #f8f9fa;
            padding: 12px;
            text-align: center;
            margin: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .topic-section h6 {
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0;
            text-transform: uppercase;
        }

        /* Videos Container */
        .videos-container {
            padding: 0 20px 20px;
        }

        /* Video Item */
        .video-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .video-item:hover {
            background: white;
            border-color: #667eea;
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.15);
        }

        /* Video Number */
        .video-number {
            background: #667eea;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        /* Video Icon */
        .video-icon {
            color: #667eea;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* Video Title */
        .video-title {
            color: #212529;
            font-weight: 500;
            font-size: 0.9rem;
            margin: 0;
            flex-grow: 1;
        }

        /* Details Button */
        .details-btn {
            background: #F1A909;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .details-btn:hover {
            background: #d99608;
            color: white;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 2.5rem;
            color: #dee2e6;
            margin-bottom: 15px;
        }

        .empty-state h6 {
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .empty-state p {
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .video-item {
                flex-wrap: wrap;
            }

            .video-title {
                font-size: 0.85rem;
                width: 100%;
            }

            .details-btn {
                width: 100%;
                margin-top: 8px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card lecture-card">
                <div class="card-body p-0">

                    <!-- Header -->
                    <div class="card-header lecture-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-camera-video me-2"></i>Lecture Videos
                        </h5>
                        <a class="btn back-btn" href="<?php echo e(route('videos.index', ['course' => $course->slug])); ?>">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                    </div>

                    <!-- Topic Title -->
                    <div class="topic-section">
                        <h6>
                            <i class="bi bi-folder-open me-2"></i>Video Topics
                        </h6>
                    </div>

                    <!-- Videos List -->
                    <?php if(isset($videos)): ?>
                        <div class="videos-container">
                            <?php $__empty_1 = true; $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="video-item">
                                    <div class="video-number"><?php echo e($key + 1); ?></div>
                                    <i class="bi bi-play-circle-fill video-icon"></i>
                                    <h6 class="video-title"><?php echo e($video->title); ?></h6>
                                    <a href="<?php echo e(route('videos.single.details', ['slug' => $video->slug, 'course' => $course->slug])); ?>"
                                        class="btn details-btn">
                                        <i class="bi bi-eye me-1"></i>Details
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="empty-state">
                                    <i class="bi bi-camera-video-off"></i>
                                    <h6>No Videos Available</h6>
                                    <p class="text-muted mb-0">No lecture videos found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/lecture-video/lists.blade.php ENDPATH**/ ?>