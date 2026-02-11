
<?php $__env->startSection('title', 'Site Setting'); ?>
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
            <form action="<?php echo e(route('admin.settings.logo.update', $logo->id ?? null)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <span class="section">Info</span>

                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php
                    $value = [];
                    if ($logo?->value) {
                        $value = json_decode($logo->value);
                    }
                ?>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Site Logo (686 x 175)<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="file" name="logo" id="logo" onchange="previewImage(this,'logo_preview')" value="<?php echo e(old('image')); ?>"/>
                        <br>
                        <img class="img-fluid" id="logo_preview" src="<?php echo e(getImageUrl($value->logo ?? '')); ?>"
                            alt="Logo" accept="image/png, image/jpeg" style="height: 175px; width: 686px">
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Favicon (32 x 32)<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="file" name="favicon" id="favicon" onchange="previewImage(this,'favicon_preview')" value="<?php echo e(old('favicon')); ?>"/>
                        <br>
                        <img class="img-fluid" id="favicon_preview" src="<?php echo e(getImageUrl($value->favicon ?? '')); ?>"
                            alt="Favicon Image" accept="image/png, image/jpeg" height="32px" width="32px">
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Text Logo<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="text_logo" value="<?php echo e($value->text_logo ?? ''); ?>"/>
                    </div>
                </div>

                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type='submit' class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script>
    function previewImage(input, prewiew_id)
    {
        let img = input.files[0];
        if (img) {
            let preview_img = document.getElementById(prewiew_id);

            let picture = new FileReader();
            picture.readAsDataURL(img);
            picture.addEventListener('load', function(event) {
                preview_img.setAttribute('src', event.target.result);
                preview_img.style.display = 'block';
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/settings/logo.blade.php ENDPATH**/ ?>