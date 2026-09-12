<?php if(isset($activeNotices) && $activeNotices->isNotEmpty()): ?>
    <div id="floating-notice-wrapper" style="position: sticky; top: 0; z-index: 1050; width: 100%;">
        <?php $__currentLoopData = $activeNotices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="floating-notice floating-notice-<?php echo e($notice->type); ?>" data-notice-id="<?php echo e($notice->id); ?>"
                 style="display:none; padding: 10px 20px; text-align: center; position: relative;">
                <span><?php echo $notice->message; ?></span>
                <button type="button" class="floating-notice-close"
                        data-notice-id="<?php echo e($notice->id); ?>"
                        style="position:absolute; right:15px; top:50%; transform:translateY(-50%);
                               background:none; border:none; font-size:18px; line-height:1;
                               cursor:pointer; color:inherit; opacity:0.7;">
                    &times;
                </button>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <style>
        .floating-notice-info    { background:#d1ecf1; color:#0c5460; border-bottom:1px solid #bee5eb; }
        .floating-notice-warning { background:#fff3cd; color:#856404; border-bottom:1px solid #ffeeba; }
        .floating-notice-urgent  { background:#f8d7da; color:#721c24; border-bottom:1px solid #f5c6cb; }
        .floating-notice-close:hover { opacity: 1; }
    </style>

    <script>
        (function () {
            const STORAGE_KEY = 'dismissed_notices';

            function getDismissed() {
                try {
                    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
                } catch (e) {
                    return [];
                }
            }

            function dismiss(id) {
                const dismissed = getDismissed();
                if (!dismissed.includes(id)) {
                    dismissed.push(id);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(dismissed));
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const dismissed = getDismissed();

                document.querySelectorAll('.floating-notice').forEach(function (el) {
                    const id = parseInt(el.getAttribute('data-notice-id'), 10);
                    if (!dismissed.includes(id)) {
                        el.style.display = 'block';
                    }
                });

                document.querySelectorAll('.floating-notice-close').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const id = parseInt(this.getAttribute('data-notice-id'), 10);
                        dismiss(id);
                        this.closest('.floating-notice').style.display = 'none';
                    });
                });
            });
        })();
    </script>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/partial.blade.php ENDPATH**/ ?>