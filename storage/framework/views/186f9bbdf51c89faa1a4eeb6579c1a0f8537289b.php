<?php $__env->startSection('title', 'Notes'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <style>
        .button-yellow:hover,
        .button-white:hover {
            color: white;
        }
        .list-group {
            background-color: transparent !important;
        }

        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="d-flex justify-content-center">
            <div class="col-sm-12 col-md-6">
                <form action="<?php echo e(route('notes.search')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="course_slug" value="<?php echo e($course->slug); ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="query" class="form-control" placeholder="Search notes..."
                            aria-label="Search notes" aria-describedby="button-addon2"
                            style="background: none; color: aliceblue; box-shadow: none !important; border-color: white"
                            required>
                        <button class="btn button-yellow" type="submit" id="button-addon2" style="height: 38px">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Notes Topic</h5>
                    </div>
                    <section id="info-utile" class="">
                        <div class="container">
                            <div class="accordion" id="accordionExample">
                                <?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                            <a class="accordion-button collapsed" href="#" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-<?php echo e($key); ?>" aria-expanded="false"
                                                aria-controls="collapse-<?php echo e($key); ?>">
                                                <?php echo e($topic->name); ?>

                                            </a>
                                        </h2>
                                        <div id="collapse-<?php echo e($key); ?>" class="accordion-collapse collapse"
                                            aria-labelledby="heading-<?php echo e($key); ?>"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    
                                                    <?php $__currentLoopData = $topic->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="list-group-item">
                                                            <a
                                                                href="<?php echo e(route('notes.details', ['course' => $course->slug, 'chapter' => $topic->slug, 'lesson' => $lesson->slug])); ?>"><?php echo e($lesson->name); ?></a>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Recent</h5>
                    </div>
                    <?php $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-3 p-3 topic-card">
                            <div class="d-flex">
                                <h5 class="flex-grow-1"><?php echo e($item->title); ?></h5>
                                <a href="<?php echo e(route('notes.single.details', $item->slug)); ?>"
                                    class="btn btn-sm button-yellow">Details</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/notes/index.blade.php ENDPATH**/ ?>