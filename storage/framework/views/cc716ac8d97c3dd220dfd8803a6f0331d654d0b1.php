<?php $__env->startSection('title', 'Reset Password — Medi Maniac'); ?>

<?php $__env->startSection('content'); ?>
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.065 2C6.505 2 2 6.486 2 12s4.505 10 10.065 10C17.585 22 22 17.514 22 12S17.585 2 12.065 2zM13 17h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>

        <h1 class="mm-title">Forgot password?</h1>
        <p class="mm-subtitle">No worries — we'll email you a reset link right away.</p>

        <?php if(session('status')): ?>
            <div class="mm-alert mm-alert-success">
                <span class="mm-alert-icon">✓</span>
                <span><?php echo e(session('status')); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mm-field">
                <label for="email" class="mm-label">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="mm-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="you@example.com"
                >
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mm-invalid-msg"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="mm-btn">
                Send Reset Link
            </button>

        </form>

        <p class="mm-footer-text">
            Remember it? <a href="<?php echo e(route('login')); ?>" class="mm-link">Back to sign in</a>
        </p>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>