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
                        <h5 class="card-title mb-0">Written Assessment Topic</h5>
                        <a class="btn btn-sm btn-warning" href="<?php echo e(route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug])); ?>">Back</a>
                    </div>

                    <section id="info-utile" class="">
                        <?php if(isset($assessment)): ?>
                            <?php echo $__env->make('frontend.dashboard.written-assessment.partial', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const query = <?php echo json_encode($query ?? '', 15, 512) ?>; // Pass the query from the server to JavaScript
                if (query) {
                    // Target the element that contains the note's description
                    const noteDescription = document.querySelector('.note-description'); // Adjust if necessary
                    const noteTitle = document.querySelector('.note-title'); // Adjust if necessary

                    if (noteDescription || noteTitle) {
                        const regex = new RegExp(query, 'gi'); // Create a regex for case-insensitive matching
                        noteDescription.innerHTML = noteDescription.innerHTML.replace(regex, match =>
                            `<span style="background: #F1A909; color: white">${match}</span>`);
                        noteTitle.innerHTML = noteTitle.innerHTML.replace(regex, match =>
                            `<span style="background: #F1A909; color: white">${match}</span>`);
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/details.blade.php ENDPATH**/ ?>