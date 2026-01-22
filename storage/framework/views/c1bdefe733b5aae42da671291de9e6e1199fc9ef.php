<?php $__env->startSection('title', 'Video Details'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .detail-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .detail-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }
        .detail-card-body {
            padding: 20px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            width: 180px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .video-container {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            min-height: 400px;
        }
        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 500px;
            border: none;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active {
            background: #28a745;
            color: #fff;
        }
        .status-inactive {
            background: #6c757d;
            color: #fff;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .course-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 4px;
            margin: 4px;
            font-size: 13px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-video-camera"></i> <?php echo e($video->title); ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-warning text-white" href="<?php echo e(route('admin.lecturevideos.index')); ?>">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row">
                    
                    <div class="col-md-8 mb-4">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-play-circle"></i> Video Player
                            </div>
                            <div class="detail-card-body p-0">
                                <div class="video-container">
                                    <?php if($video->youtube_link): ?>
                                        
                                        <iframe
                                            src="<?php echo e($video->youtube_link); ?>"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>

                                    <?php elseif($video->uploaded_link): ?>
                                        
                                       <div class="video-container">
                                            <?php if($video->youtube_link): ?>
                                                
                                                <iframe
                                                    src="<?php echo e($video->youtube_link); ?>"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>

                                            <?php elseif($video->uploaded_link): ?>
                                                
                                                <?php
                                                    $libraryId = env('BUNNY_VIDEO_LIBRARY_ID');
                                                    $videoId = $video->uploaded_link;
                                                    $bunnyUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}";
                                                ?>

                                                <iframe
                                                    src="<?php echo e($bunnyUrl); ?>"
                                                    loading="lazy"
                                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>

                                            <?php else: ?>
                                                
                                                <div style="padding: 80px 20px; text-align: center; background: #f8f9fa;">
                                                    <i class="fa fa-film" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                                                    <h4 class="text-muted">No Video Available</h4>
                                                    <p class="text-muted">Upload a video to see it here</p>
                                                    <a href="<?php echo e(route('admin.lecturevideos.edit', $video->id)); ?>" class="btn btn-primary mt-3">
                                                        <i class="fa fa-upload"></i> Upload Video
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    <?php else: ?>
                                        
                                        <div style="padding: 80px 20px; text-align: center; background: #f8f9fa;">
                                            <i class="fa fa-film" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                                            <h4 class="text-muted">No Video Available</h4>
                                            <p class="text-muted">Upload a video to see it here</p>
                                            <a href="<?php echo e(route('admin.lecturevideos.edit', $video->id)); ?>" class="btn btn-primary mt-3">
                                                <i class="fa fa-upload"></i> Upload Video
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-4 mb-4">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-info-circle"></i> Video Information
                            </div>
                            <div class="detail-card-body">
                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-book"></i> Courses</div>
                                    <div class="info-value">
                                        <?php $__currentLoopData = $video->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="course-badge"><?php echo e($course->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-list"></i> Chapter</div>
                                    <div class="info-value"><?php echo e($video->getChapterName()); ?></div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-file-text"></i> Lesson</div>
                                    <div class="info-value"><?php echo e($video->getLessonName()); ?></div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-money"></i> Type</div>
                                    <div class="info-value">
                                        <?php if($video->isPaid): ?>
                                            <span class="badge badge-warning">
                                                <i class="fa fa-lock"></i> Paid
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success">
                                                <i class="fa fa-unlock"></i> Free
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-toggle-on"></i> Status</div>
                                    <div class="info-value">
                                        <span class="status-badge <?php echo e($video->status == 1 ? 'status-active' : 'status-inactive'); ?>">
                                            <?php echo e($video->status == 1 ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-video-camera"></i> Source</div>
                                    <div class="info-value">
                                        <?php if($video->youtube_link): ?>
                                            <span class="badge badge-danger">
                                                <i class="fa fa-youtube-play"></i> YouTube
                                            </span>
                                        <?php elseif($video->uploaded_link): ?>
                                            <span class="badge badge-primary">
                                                <i class="fa fa-cloud"></i> Bunny.net
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">No video</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if($video->uploaded_link): ?>
                                    <div class="info-row">
                                        <div class="info-label"><i class="fa fa-code"></i> Video ID</div>
                                        <div class="info-value">
                                            <code style="font-size: 11px;"><?php echo e($video->uploaded_link); ?></code>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-calendar"></i> Created</div>
                                    <div class="info-value">
                                        <small><?php echo e($video->created_at->format('d M Y, h:i A')); ?></small>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-clock-o"></i> Updated</div>
                                    <div class="info-value">
                                        <small><?php echo e($video->updated_at->diffForHumans()); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-cog"></i> Actions
                            </div>
                            <div class="detail-card-body">
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('admin.lecturevideos.edit', $video->id)); ?>" class="btn btn-primary btn-block">
                                        <i class="fa fa-edit"></i> Edit Video
                                    </a>

                                    <form action="<?php echo e(route('admin.lecturevideos.status', $video->id)); ?>"
                                          method="POST" class="w-100">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-secondary btn-block">
                                            <i class="fa fa-toggle-on"></i>
                                            <?php echo e($video->status == 1 ? 'Deactivate' : 'Activate'); ?>

                                        </button>
                                    </form>

                                    <form action="<?php echo e(route('admin.lecturevideos.destroy', $video->id)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure? This action cannot be undone!');"
                                          class="w-100">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fa fa-trash"></i> Delete Video
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/lecture-video/show.blade.php ENDPATH**/ ?>