<style>
    .card {
        transition: transform 0.2s; /* Smooth scaling effect */
        border: none; /* Remove default border for cleaner look */
    }

    .card:hover {
        transform: scale(1.05); /* Scale up on hover */
    }

    .img {
        overflow: hidden; /* Prevent overflow */
        border-top-left-radius: .25rem; /* Rounded corners */
        border-top-right-radius: .25rem; /* Rounded corners */
        height: 200px; /* Set a fixed height for uniformity */
        position: relative; /* Positioning for absolute children */
    }

    .img img {
        width: 100%; /* Ensure the image fills the container */
        height: 100%; /* Fill the height of the container */
        object-fit: cover; /* Crop the image to cover the entire area */
        position: absolute; /* Absolute positioning for full coverage */
        top: 0; /* Align to top */
        left: 0; /* Align to left */
        transition: transform 0.3s ease; /* Smooth zoom effect */
    }

    .img img:hover {
        transform: scale(1.1); /* Zoom on hover */
    }

    .price-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Align prices on both sides */
    }

    .price-old {
        text-decoration: line-through; /* Style for old price */
        color: #dc3545; /* Bootstrap danger color for price */
    }

    .btn-warning {
        transition: background-color 0.3s; /* Smooth background change */
    }

    .btn-warning:hover {
        background-color: #e0a800; /* Slightly darker yellow on hover */
    }
</style>

<?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col">
        <div class="card shadow-sm rounded">
            <div class="img">
                <img src="<?php echo e(getImageUrl($course->banner)); ?>" class="card-img-top img-fluid" alt="<?php echo e($course->name); ?>" loading="lazy">
            </div>
            <div class="card-body">
                <h4 class="card-title text-dark"><?php echo e($course->name); ?></h4>

                <?php if($course->is_pricing == 1): ?>
                <p class="card-text text-muted">Duration: <?php echo e($course->detail?->duration); ?> <?php echo e($course->detail?->type); ?></p>
                <div class="price-wrapper">
                    <?php if($course->detail?->sell_price): ?>
                        <p class="card-text mb-0">
                            Price: <span class="price-old"><?php echo e($course->detail?->price); ?> <strong>৳</strong></span>
                            <span class="text-success fw-bold"><?php echo e($course->detail?->sell_price); ?> <strong>৳</strong></span>
                        </p>
                    <?php else: ?>
                        <p class="card-text text-dark fw-bold mb-0">Price: <?php echo e($course->detail?->price); ?> <strong>৳</strong></p>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div style="padding-bottom: 52px"></div>
                <?php endif; ?>
                <a href="<?php echo e(route('courses.details', $course->slug)); ?>" class="btn btn-warning mt-3 d-flex align-items-center">
                    <span>More Details</span>
                    <i class="bi bi-arrow-right-circle ms-2" style="font-size: 20px;"></i>
                </a>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/includes/course.blade.php ENDPATH**/ ?>