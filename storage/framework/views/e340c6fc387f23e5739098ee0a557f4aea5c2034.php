
<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('content'); ?>
<div class="x_panel">
    <div class="x_title">
      <h2>Profile Update</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="<?php echo e(route('admin.update.profile', Auth::id())); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row">
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Name<span class=" text-danger">*</span></label>
                    <input class="form-control" name="name" value="<?php echo e($user->name); ?>" required/>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Last Name</label>
                    <input class="form-control" name="lname" value="<?php echo e($user->profile?->lname); ?>"/>
                </div>

                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Email<span class=" text-danger">*</span></label>
                    <input class="form-control" name="email" class='email' value="<?php echo e($user->email); ?>" type="email" required/>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Phone</label>
                    <input class="form-control" name="phone" class='phone' value="<?php echo e($user->profile?->phone); ?>" type="text" />
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="label-align">dob</label>
                        <input class="form-control" name="dob" class='dob' value="<?php echo e($user->profile?->dob); ?>" type="date" />
                    </div>

                    <div class="mb-3">
                        <label class="label-align">Blood Group</label>
                        <select name="blood_group" id="blood_group" class="form-control">
                            <?php echo generateBloodGroupOptions(old('blood_group', $user->profile?->blood_group ?? null)); ?>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="label-align">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <?php echo generateGenderOptions(old('gender', $user->profile?->gender ?? null)); ?>

                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Image</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                    <img id="preview-image" src="<?php echo e(getImageUrl($user->profile?->photo)); ?>" alt="Image Preview" style="max-width: 150px; height: 150px; margin-top: 10px;">
                </div>
                <div class="col-12 mt-3">
                    <button type='submit' class="btn btn-warning text-light">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function () {
            $('#photo').on('change', function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/edit_profile.blade.php ENDPATH**/ ?>