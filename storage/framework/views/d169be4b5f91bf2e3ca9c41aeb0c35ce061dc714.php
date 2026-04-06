<?php $__env->startSection('title', 'Set New Password — Medi Maniac'); ?>

<?php $__env->startSection('content'); ?>
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
            </svg>
        </div>

        <h1 class="mm-title">Set new password</h1>
        <p class="mm-subtitle">Choose a strong password for your account.</p>

        <form method="POST" action="<?php echo e(route('password.update')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>">

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
                    value="<?php echo e($email ?? old('email')); ?>"
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

            <div class="mm-field">
                <label for="password" class="mm-label">New Password</label>
                <input
                    id="password"
                    type="password"
                    class="mm-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 characters"
                >
                <?php $__errorArgs = ['password'];
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

            <div class="mm-field">
                <label for="password-confirm" class="mm-label">Confirm Password</label>
                <input
                    id="password-confirm"
                    type="password"
                    class="mm-input"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repeat your new password"
                >
            </div>

            <button type="submit" class="mm-btn">
                Reset Password
            </button>

        </form>

        <p class="mm-footer-text">
            <a href="<?php echo e(route('login')); ?>" class="mm-link">← Back to sign in</a>
        </p>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/auth/passwords/reset.blade.php ENDPATH**/ ?>