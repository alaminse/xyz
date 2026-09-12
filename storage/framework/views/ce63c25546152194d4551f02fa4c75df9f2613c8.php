
<?php $__env->startSection('title', 'Course'); ?>
<?php $__env->startSection('content'); ?>
    <section id="thehero">
        <div class="the-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h1 class="active">Course</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section class="topic" id="<?php echo e($course->slug); ?>">
            <div class="container-xl">
                <h2 class="header"><?php echo e($course->name); ?></h2>
                <div class="row d-flex justify-content-center row-cols-1 row-cols-md-4 g-4 load-course"></div>
            </div>
        </section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div style="margin-top: 50px"></div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const courseSlug = $(entry.target).attr('id');

                            $.ajax({
                                url: `/courses/${courseSlug}`,
                                type: 'GET',
                                success: function(data) {
                                    const coursesContainer = $(entry.target).find('.load-course');

                                    coursesContainer.html(data.html);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching courses:', error);
                                }
                            });
                            observer.unobserve(entry.target);
                        }
                    });
                });
                $('.topic').each(function() {
                    observer.observe(this);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/course.blade.php ENDPATH**/ ?>