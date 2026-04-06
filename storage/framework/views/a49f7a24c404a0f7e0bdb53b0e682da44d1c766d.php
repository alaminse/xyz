<?php $__env->startSection('title', 'Written Assessment Details'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-sm-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Written Assessment Details</h5>
                    <a class="btn btn-sm btn-warning"
                        href="<?php echo e(route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug])); ?>">
                        Back
                    </a>
                </div>

                <section id="info-utile" class="">
                    <?php if(isset($assessment)): ?>
                        <div class="mt-3 p-3">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">

                                    <h2 class="mb-4 note-title">
                                        <u>Question:</u> <?php echo $assessment->question; ?>

                                    </h2>

                                    <?php if($isPaid && $isLocked): ?>
                                        
                                        <div class="text-center py-4 px-3"
                                            style="background:#fff8e1; border:2px dashed #ffc107; border-radius:12px;">
                                            <i class="bi bi-lock-fill text-warning" style="font-size:2.5rem;"></i>
                                            <h5 class="mt-3 mb-2">Answer is locked</h5>
                                            <p class="text-muted mb-3">
                                                This is Premium content. Please upgrade your plan to see the answer.
                                            </p>
                                            <a href="<?php echo e(route('courses.checkout', ['course' => $course_slug])); ?>"
                                                class="btn btn-warning fw-bold">
                                                <i class="bi bi-unlock-fill"></i> Upgrade to Premium
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="note-description">
                                            <h2>Answer</h2>
                                            <?php echo $assessment->answer; ?>

                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const query = <?php echo json_encode($query ?? '', 15, 512) ?>;
            if (query) {
                const noteDescription = document.querySelector('.note-description');
                const noteTitle = document.querySelector('.note-title');

                if (noteDescription) {
                    const regex = new RegExp(query, 'gi');
                    noteDescription.innerHTML = noteDescription.innerHTML.replace(regex,
                        match => `<span style="background: #F1A909; color: white">${match}</span>`);
                }
                if (noteTitle) {
                    const regex = new RegExp(query, 'gi');
                    noteTitle.innerHTML = noteTitle.innerHTML.replace(regex,
                        match => `<span style="background: #F1A909; color: white">${match}</span>`);
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/details.blade.php ENDPATH**/ ?>