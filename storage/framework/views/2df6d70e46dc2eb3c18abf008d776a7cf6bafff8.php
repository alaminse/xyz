<?php $__env->startSection('title', 'Written Assessment Details'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-sm-12 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Written Assessment Topic</h5>
                    <a class="btn btn-sm btn-warning" href="<?php echo e(route('writtens.index', ['course' => $course_slug])); ?>">Back</a>
                </div>
                <section id="info-utile" class="">
                    <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>
                    <?php if(isset($written)): ?>
                    <div id="notes-container">
                        <?php $__currentLoopData = $written; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-2">
                                <div class="card-body mb-1 p-2 note-item">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                                        <h5 class="flex-grow-1 note-title mb-0"><?php echo $assessment->question; ?></h5>
                                        <?php if($isLocked): ?>
                                            <a href="<?php echo e(route('courses.checkout', ['course' => $course_slug])); ?>"
                                               class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                                <i class="bi bi-lock-fill me-1"></i> Upgrade to Premium
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('writtens.single.details', ['slug' => $assessment->slug, 'query' => $query ?? null])); ?>"
                                               class="btn btn-sm button-yellow flex-shrink-0">Details</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const query = <?php echo json_encode($query ?? '', 15, 512) ?>;
        if (query) {
            const notes = document.querySelectorAll('.note-item .note-title');
            notes.forEach(note => {
                const regex = new RegExp(query, 'gi');
                note.innerHTML = note.textContent.replace(regex, match => `<span style="color: #F1A909;">${match}</span>`);
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/lists.blade.php ENDPATH**/ ?>