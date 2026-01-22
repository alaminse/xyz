<?php $__env->startSection('title', 'Assessments'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/ranking.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="assessment-ranking">
            <h2><?php echo e($assessment->name); ?> - Ranking</h2>
            <p>Total Marks: <?php echo e($assessment->total_marks); ?> | Time: <?php echo e($assessment->time); ?> Minutes</p>

            <?php if($userRank): ?>
                <div class="your-rank-card">

                    
                    <div class="rank-card-header-main">
                        <div class="user-avatar">
                            <?php if(isset($userRank->user->profile_photo_path) && $userRank->user->profile_photo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $userRank->user->profile_photo_path)); ?>"
                                    alt="<?php echo e($userRank->user->name); ?>">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?php echo e(strtoupper(substr($userRank->user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-info">
                            <h3><?php echo e($userRank->user->name); ?></h3>
                            <p class="user-email"><?php echo e($userRank->user->email); ?></p>
                        </div>
                    </div>

                    
                    <div class="rank-stats-grid">
                        <div class="stat-box stat-total">
                            <div class="stat-icon">👥</div>
                            <div class="stat-content">
                                <div class="stat-label">Total Participants</div>
                                <div class="stat-value"><?php echo e($totalParticipants ?? $leaderboard->count()); ?></div>
                            </div>
                        </div>

                        <div class="stat-box stat-rank">
                            <div class="stat-icon">✅</div>
                            <div class="stat-content">
                                <div class="stat-label">Passed Participants</div>
                                <div class="stat-value">
                                    <?php echo e($leaderboard->where('achive_marks', '>=', $assessment->total_marks * 0.7)->count()); ?>

                                </div>
                            </div>
                        </div>

                        <div class="stat-box stat-marks">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-content">
                                <div class="stat-label">Total Marks</div>
                                <div class="stat-value"><?php echo e($assessment->total_marks); ?></div>
                            </div>
                        </div>

                        <div class="stat-box stat-marks">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-content">
                                <div class="stat-label">Cut Mark 70%</div>
                                <div class="stat-value"><?php echo e($assessment->total_marks * 0.7); ?></div>
                            </div>
                        </div>

                        <div class="stat-box stat-your-marks">
                            <div class="stat-icon">📊</div>
                            <div class="stat-content">
                                <div class="stat-label">Your Marks</div>
                                <div class="stat-value"><?php echo e($userRank->achive_marks); ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="your-performance">
                        <h4>🏆 Your Performance</h4>
                        <div class="performance-grid">

                            <div class="performance-item">
                                <div class="performance-item-label">Your Rank</div>
                                <div class="performance-item-value">#<?php echo e($userRank->rank ?? $loop->iteration); ?></div>
                                <div class="performance-item-sub">
                                    Out of <?php echo e($totalParticipants ?? $leaderboard->count()); ?> Participants
                                </div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Score</div>
                                <div class="performance-item-value">
                                    <?php echo e($userRank->achive_marks); ?>/<?php echo e($assessment->total_marks); ?>

                                </div>
                                <div class="performance-item-sub"><?php echo e($userRank->percentage); ?>%</div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Result</div>
                                <div class="performance-item-value">
                                    <?php if($userRank->achive_marks >= $assessment->total_marks * 0.7): ?>
                                        <span class="result-badge pass">✓ Passed</span>
                                    <?php else: ?>
                                        <span class="result-badge fail">✗ Failed</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Your Position</div>
                                <div class="performance-item-value">
                                    <?php
                                        $position = round(
                                            ($userRank->rank / ($totalParticipants ?? $leaderboard->count())) * 100,
                                        );
                                    ?>
                                    <?php if($position <= 10): ?>
                                        Top 10%
                                    <?php elseif($position <= 25): ?>
                                        Top 25%
                                    <?php elseif($position <= 50): ?>
                                        Top 50%
                                    <?php else: ?>
                                        Bottom 50%
                                    <?php endif; ?>
                                </div>
                                <div class="performance-item-sub">
                                    <?php echo e($userRank->created_at->format('d M Y, h:i A')); ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <h4>📝 Assessment Not Completed</h4>
                    <p>You have not completed this assessment yet. Click the button below to start.</p>
                    <a href="<?php echo e(route('assessment.start', $assessment->slug)); ?>" class="btn btn-primary">
                        Start Assessment
                    </a>
                </div>
            <?php endif; ?>

            
            <?php if(isset($topThree) && $topThree->count() > 0): ?>
                <div class="top-winners pt-3">
                    <h3>🥇 Top 3 Performers</h3>
                    <div class="winners-row pb-4">
                        <?php $__currentLoopData = $topThree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $winner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="winner-card">
                                <div class="rank-badge"><?php echo e($loop->iteration); ?></div>
                                <h4><?php echo e($winner->user->name); ?></h4>
                                <p><?php echo e($winner->achive_marks); ?> Marks</p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="leaderboard-table p-4">
                <h3>Complete Ranking List</h3>

                
                <table class="table table-desktop">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php echo e($progress->user_id == auth()->id() ? 'highlight-row' : ''); ?>">
                                <td><span class="rank-badge">#<?php echo e($loop->iteration); ?></span></td>
                                <td>
                                    <?php echo e($progress->user->name); ?>

                                    <?php if($progress->user_id == auth()->id()): ?>
                                        <span class="badge-primary">You</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($progress->achive_marks); ?> / <?php echo e($assessment->total_marks); ?></td>
                                <td><?php echo e($progress->percentage); ?>%</td>
                                <td><?php echo e($progress->created_at->format('d M Y, h:i A')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    No one has completed this assessment yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                
                <div class="table-mobile">
                    <?php $__empty_1 = true; $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rank-card <?php echo e($progress->user_id == auth()->id() ? 'highlight-row' : ''); ?>">
                            <div class="rank-card-header">
                                <span class="rank-badge">#<?php echo e($loop->iteration); ?></span>
                                <span class="rank-card-name">
                                    <?php echo e($progress->user->name); ?>

                                    <?php if($progress->user_id == auth()->id()): ?>
                                        <span class="badge-primary">You</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="rank-card-info">
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Score:</span>
                                    <span class="rank-card-info-value">
                                        <?php echo e($progress->achive_marks); ?> / <?php echo e($assessment->total_marks); ?>

                                    </span>
                                </div>
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Percentage:</span>
                                    <span class="rank-card-info-value"><?php echo e($progress->percentage); ?>%</span>
                                </div>
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Submitted:</span>
                                    <span class="rank-card-info-value">
                                        <?php echo e($progress->created_at->format('d M Y, h:i A')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center" style="padding: 20px;">
                            No one has completed this assessment yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="stats">
                <p><strong>Total Participants:</strong> <?php echo e($totalParticipants ?? $leaderboard->count()); ?></p>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/assessment/rank.blade.php ENDPATH**/ ?>