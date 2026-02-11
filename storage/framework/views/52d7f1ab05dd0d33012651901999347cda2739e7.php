<?php $__env->startSection('title', 'Contact Info'); ?>
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
        <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="x_content">
            <!-- Contact Info Section -->
            <div id="contact-display">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section">Contact Info</span>
                    <button type="button" id="edit-contact-btn" class="btn btn-primary btn-sm">Edit</button>
                </div>

                <?php
                    $value = [];
                    if ($contact?->value) {
                        $value = json_decode($contact->value);
                    }
                ?>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($value->address ?? 'Not set'); ?></span>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Phone Number</label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($value->phone ?? 'Not set'); ?></span>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($value->email ?? 'Not set'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Contact Info Edit Form (Initially Hidden) -->
            <form id="contact-form" action="<?php echo e(route('admin.settings.contact.update', $contact->id ?? null)); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="section">Contact Info</span>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="address" value="<?php echo e($value->address ?? ''); ?>" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Phone Number</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="number" name="phone" value="<?php echo e($value->phone ?? ''); ?>" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="email" name="email" value="<?php echo e($value->email ?? ''); ?>" />
                    </div>
                </div>

                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type="button" id="cancel-contact-btn" class="btn btn-secondary mr-2">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Social Info Section -->
            <div id="social-display">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
                    <span class="section">Social Info</span>
                    <button type="button" id="edit-social-btn" class="btn btn-primary btn-sm">Edit</button>
                </div>

                <?php
                    $svalue = [];
                    if ($socials?->value) {
                        $svalue = json_decode($socials->value);
                    }
                ?>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Facebook <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($svalue->facebook ?? 'Not set'); ?></span>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Instagram</label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($svalue->instagram ?? 'Not set'); ?></span>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Linked In</label>
                    <div class="col-md-6 col-sm-6">
                        <span class="form-control-plaintext"><?php echo e($svalue->linkedin ?? 'Not set'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Social Info Edit Form (Initially Hidden) -->
            <form id="social-form" action="<?php echo e(route('admin.settings.socials.update', $socials->id ?? null)); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
                    <span class="section">Social Info</span>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Facebook <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="facebook" value="<?php echo e($svalue->facebook ?? ''); ?>" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Instagram</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="instagram" value="<?php echo e($svalue->instagram ?? ''); ?>" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Linked In</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="linkedin" value="<?php echo e($svalue->linkedin ?? ''); ?>" />
                    </div>
                </div>

                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type="button" id="cancel-social-btn" class="btn btn-secondary mr-2">Cancel</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contact Info Edit Toggle
            const editContactBtn = document.getElementById('edit-contact-btn');
            const cancelContactBtn = document.getElementById('cancel-contact-btn');
            const contactDisplay = document.getElementById('contact-display');
            const contactForm = document.getElementById('contact-form');

            editContactBtn.addEventListener('click', function() {
                contactDisplay.style.display = 'none';
                contactForm.style.display = 'block';
            });

            cancelContactBtn.addEventListener('click', function() {
                contactDisplay.style.display = 'block';
                contactForm.style.display = 'none';
            });

            // Social Info Edit Toggle
            const editSocialBtn = document.getElementById('edit-social-btn');
            const cancelSocialBtn = document.getElementById('cancel-social-btn');
            const socialDisplay = document.getElementById('social-display');
            const socialForm = document.getElementById('social-form');

            editSocialBtn.addEventListener('click', function() {
                socialDisplay.style.display = 'none';
                socialForm.style.display = 'block';
            });

            cancelSocialBtn.addEventListener('click', function() {
                socialDisplay.style.display = 'block';
                socialForm.style.display = 'none';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/settings/contact.blade.php ENDPATH**/ ?>