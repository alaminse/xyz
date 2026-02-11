
<?php $__env->startSection('title', 'Faqs'); ?>
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
            <form action="<?php echo e(route('admin.settings.faqs.update', $faqs->id ?? null)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <?php
                    $value = [];
                    if ($faqs?->value) {
                        $value = json_decode($faqs->value);
                    }
                ?>

                <span class="section">Faqs</span>
                <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div id="dynamic-fields">
                    <?php if(!empty($value)): ?>
                        <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row" id="field-group-<?php echo e($index); ?>">
                                <div class="col-sm-12 col-md-11">
                                    <div class="form-group row mt-3">
                                        <label class="col-md-3 col-form-label">Question</label>
                                        <div class="col-md-7">
                                            <input type="text" name="questions[<?php echo e($index); ?>][question]" class="form-control" value="<?php echo e($item->question); ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-2">
                                        <label class="col-md-3 col-form-label">Answer</label>
                                        <div class="col-md-7">
                                            <input type="text" name="questions[<?php echo e($index); ?>][answer]" class="form-control" value="<?php echo e($item->answer); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1">
                                    <button type="button" class="btn btn-danger mt-2 remove-btn" data-id="<?php echo e($index); ?>">Remove</button>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-btn" class="btn btn-primary mt-3">Add Question</button>

                <div class="ln_solid">
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
            $(document).ready(function() {
                let fieldIndex = 0;

                // Handle Add button click
                $('#add-btn').click(function() {
                    fieldIndex++; // Increment the index for each new set of fields

                    // Create the new input fields for question and answer
                    let newField = `
                        <div class="row" id="field-group-${fieldIndex}">
                            <div class="col-sm-12 col-md-11">
                                <div class="form-group row mt-3">
                                    <label class="col-md-3 col-form-label">Question</label>
                                    <div class="col-md-7">
                                        <input type="text" name="questions[${fieldIndex}][question]" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group row mt-2">
                                    <label class="col-md-3 col-form-label">Answer</label>
                                    <div class="col-md-7">
                                        <input type="text" name="questions[${fieldIndex}][answer]" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-1">
                                <button type="button" class="btn btn-danger mt-2 remove-btn" data-id="${fieldIndex}">Remove</button>
                            </div>
                            <hr>
                        </div>
                    `;

                    // Append the new fields to the container
                    $('#dynamic-fields').append(newField);
                });

                // Handle Remove button click
                $(document).on('click', '.remove-btn', function() {
                    let id = $(this).data('id'); // Get the index of the clicked remove button
                    $(`#field-group-${id}`).remove(); // Remove the corresponding field group
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/settings/faqs.blade.php ENDPATH**/ ?>