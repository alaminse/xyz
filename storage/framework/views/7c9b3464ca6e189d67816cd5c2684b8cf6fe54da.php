<footer class="site-footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <img src="<?php echo e(asset('uploads/logo/logo.png')); ?>" alt="MediManiac" height="36" class="mb-3">
                <p class="footer-tagline">From aspirations to achievements — structured prep for medical postgraduate
                    exams.</p>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-heading">Explore</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo e(url('/')); ?>">Home</a></li>
                    <li><a href="<?php echo e(route('about')); ?>">About</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-heading">Legal</h6>
                <ul class="footer-links">
                    <li><a href="<?php echo e(route('terms.condition') ?? '#'); ?>">Terms &amp; Conditions</a></li>
                    <li><a href="<?php echo e(route('privacy.policy') ?? '#'); ?>">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="footer-heading">Get in touch</h6>
                <?php $contact = function_exists('contact') ? contact() : []; ?>
                <ul class="footer-links">
                    <li><i class="bi bi-geo-alt me-2"></i><?php echo e($contact['address'] ?? 'Dhaka, Bangladesh'); ?></li>
                    <li><i class="bi bi-telephone me-2"></i><?php echo e($contact['phone'] ?? ''); ?></li>
                    <li><i class="bi bi-envelope me-2"></i><?php echo e($contact['email'] ?? ''); ?></li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span class="footer-copy">&copy; <?php echo e(date('Y')); ?> MediManiac. All rights reserved.</span>
        </div>
    </div>
</footer>


<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/includes/footer.blade.php ENDPATH**/ ?>