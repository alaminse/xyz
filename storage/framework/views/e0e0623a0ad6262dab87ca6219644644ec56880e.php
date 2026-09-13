<?php $__env->startSection('title', 'Edit Notice'); ?>
<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edit Notice</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="btn btn-warning text-white" href="<?php echo e(route('admin.notices.index')); ?>"> Back</a></li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <form action="<?php echo e(route('admin.notices.update', $notice->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <label for="start_at" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?php echo old('title', $notice->title); ?>">
                    </div>
                    <div class="col-sm-12 mb-3">
                        <label for="message" class="form-label">Message <span class="required text-danger">*</span></label>
                        <textarea class="form-control summernote" name="message" rows="4" required><?php echo old('message', $notice->message); ?></textarea>
                        <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="type" class="form-label">Type <span class="required text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="info" <?php echo e(old('type', $notice->type) == 'info' ? 'selected' : ''); ?>>Info (blue)</option>
                            <option value="warning" <?php echo e(old('type', $notice->type) == 'warning' ? 'selected' : ''); ?>>Warning (yellow)</option>
                            <option value="urgent" <?php echo e(old('type', $notice->type) == 'urgent' ? 'selected' : ''); ?>>Urgent (red)</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="start_at" class="form-label">Start At (optional)</label>
                        <input type="datetime-local" class="form-control" name="start_at"
                               value="<?php echo e(old('start_at', $notice->start_at?->format('Y-m-d\TH:i'))); ?>">
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="end_at" class="form-label">End At (optional)</label>
                        <input type="datetime-local" class="form-control" name="end_at"
                               value="<?php echo e(old('end_at', $notice->end_at?->format('Y-m-d\TH:i'))); ?>">
                    </div>

                    <div class="col-sm-12 mb-3">
                        <label for="is_active" class="form-label">Active</label>
                        <br>
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $notice->is_active) ? 'checked' : ''); ?>>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        $('.summernote').summernote({ height: 150 });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/notice/edit.blade.php ENDPATH**/ ?>