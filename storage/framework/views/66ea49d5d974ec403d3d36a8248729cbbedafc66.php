<?php $__env->startSection('title', 'Verify Email — Medi Maniac'); ?>

<?php $__env->startSection('content'); ?>
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 4H4C2.9 4 2 4.9 2 6v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </div>

        <h1 class="mm-title">Check your inbox</h1>
        <p class="mm-subtitle">We sent a verification link to your email address.</p>

        <?php if(session('resent')): ?>
            <div class="mm-alert mm-alert-success">
                <span class="mm-alert-icon">✓</span>
                <span><?php echo e(__('A fresh verification link has been sent to your email address.')); ?></span>
            </div>
        <?php endif; ?>

        <p style="font-size:0.92rem; color:#4a5568; line-height:1.6; margin-bottom:1.75rem;">
            <?php echo e(__('Before proceeding, please check your email for a verification link.')); ?>

        </p>

        <div class="mm-divider">didn't receive it?</div>

        <form method="POST" action="<?php echo e(route('verification.resend')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="mm-btn">
                Resend Verification Email
            </button>
        </form>

        <p class="mm-footer-text">
            Wrong account? <a href="<?php echo e(route('logout')); ?>" class="mm-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
        </p>
        <form id="logout-form" method="POST" action="<?php echo e(route('logout')); ?>" style="display:none;"><?php echo csrf_field(); ?></form>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/auth/verify.blade.php ENDPATH**/ ?>