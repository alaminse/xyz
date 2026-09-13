<?php $__env->startSection('title', 'MediManiac — From Aspirations to Achievements'); ?>

<?php $__env->startSection('content'); ?>


<section class="hero">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <span class="hero-eyebrow">FCPS &amp; postgraduate medical prep</span>
                <h1>From aspirations to achievements.</h1>
                <p class="hero-sub">
                    <?php echo e($sliders->short_description1 ?? 'Structured courses, question banks, and mock exams built around the way medical students actually revise.'); ?>

                </p>
                <div class="hero-actions">
                    <a href="#courses" class="btn-primary-cta">
                        Explore courses <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="<?php echo e(route('contact')); ?>" class="btn-secondary-cta">
                        Talk to us
                    </a>
                </div>

                <div class="hero-stats">
                    <div>
                        <span class="hero-stat-num">10,000+</span>
                        <span class="hero-stat-label">Practice questions</span>
                    </div>
                    <div>
                        <span class="hero-stat-num"><?php echo e($courses->count() ?? '20'); ?>+</span>
                        <span class="hero-stat-label">Structured courses</span>
                    </div>
                    <div>
                        <span class="hero-stat-num">24/7</span>
                        <span class="hero-stat-label">Access on any device</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="frame">
                        <img src="<?php echo e(getImageUrl($sliders->slider1 ?? '')); ?>" alt="MediManiac">
                    </div>
                    <div class="hero-float-card">
                        <div class="icon-wrap"><i class="bi bi-mortarboard-fill"></i></div>
                        <div>
                            <span class="value"><?php echo e($sliders->heading1 ?? 'Rapid Fire'); ?></span><br>
                            <span class="label">This week's focus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="courses">
    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <section class="topic" id="<?php echo e($course->slug); ?>">
            <div class="container">
                <div class="section-heading-row">
                    <div>
                        <h2><?php echo e($course->name); ?></h2>
                        <span class="section-sub">Pick up where you left off, or start a new topic.</span>
                    </div>
                </div>
                <div class="row d-flex row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 load-course course-card-slot"></div>
            </div>
        </section>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>

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

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/index.blade.php ENDPATH**/ ?>