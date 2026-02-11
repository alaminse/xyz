
<?php $__env->startSection('title', 'Terms & Condition'); ?>
<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="x_panel">
    <div class="x_title">
        <h2>Site Setting</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="<?php echo e(route('admin.settings.terms.update', $terms->id ?? null)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <span class="section">Terms & Condition</span>
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                <div class="col-12 mb-3">
                    <label class="label-align">Terms & Condition <span class="required text-danger">*</span></label>
                    <textarea class="form-control summernote" name="description" cols="30" rows="5"><?php echo e(old('description', $terms->value ?? '')); ?></textarea>
                </div>
                <div class="col-12">
                    <button type='submit' class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

        <script>
            function uploadImage(file, summernoteInstance) {
                var formData = new FormData();
                formData.append('image', file);
                let csrfToken = '<?php echo e(csrf_token()); ?>';
                let url = '<?php echo e(route('admin.summernote.upload')); ?>?_token=' + csrfToken;
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        summernoteInstance.summernote('insertImage', response.url);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $(`.summernote`).summernote({
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0], $(this));
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/settings/terms.blade.php ENDPATH**/ ?>