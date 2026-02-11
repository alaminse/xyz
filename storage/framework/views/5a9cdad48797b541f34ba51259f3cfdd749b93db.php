<?php $__env->startSection('title', 'Contact'); ?>
<?php $__env->startSection('content'); ?>

    <style>
        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d1f2eb;
            border: 1px solid #0f5132;
            color: #0f5132;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #842029;
            color: #842029;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.75rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        button[type="submit"]:hover:not(:disabled) {
            background: #1d4ed8;
        }

        button[type="submit"]:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
    <section id="thehero">
        <div class="the-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h1 class="active">Home <a href="#" class="text-white">/ Contact</a></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-us">
        <h2 class="text-center fs-2 heading text-uppercase">FAQ</h2>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    


                    <div class="form-card">
                        <h3 class="mb-4">Keep In Touch</h3>

                        <!-- Success Message -->
                        <div class="alert alert-success d-none" id="successMessage">
                            <i class="bi bi-check-circle me-2"></i>
                            <span id="successText"></span>
                        </div>

                        <!-- Error Message -->
                        <div class="alert alert-danger d-none" id="errorMessage">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <span id="errorText"></span>
                        </div>

                        <form id="contactForm" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Your Name" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Your Email" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone"
                                    required>
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>

                            <div class="mb-3">
                                <textarea class="form-control" name="message" id="message" rows="3" placeholder="Message" required></textarea>
                                <div class="invalid-feedback" id="messageError"></div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" id="submitBtn">
                                    <span class="btn-text">Send Message</span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <?php
                        $value = [];
                        if ($faqs?->value) {
                            $value = json_decode($faqs->value);
                        }
                    ?>
                    <div class="accordion" id="accordionExample">
                        <?php if(!empty($value)): ?>
                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-<?php echo e($index); ?>" aria-expanded="false'"
                                            aria-controls="collapse-<?php echo e($index); ?>">
                                            <?php echo e($item->question); ?>

                                        </button>
                                    </h2>
                                    <div id="collapse-<?php echo e($index); ?>" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <?php echo e($item->answer); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const successText = document.getElementById('successText');
            const errorText = document.getElementById('errorText');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                clearErrors();

                // Disable button and show loading
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');

                // Get form data
                const formData = new FormData(form);

                // Send AJAX request
                fetch('<?php echo e(route('contact.send')); ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button
                        submitBtn.disabled = false;
                        btnText.classList.remove('d-none');
                        btnLoading.classList.add('d-none');

                        if (data.success) {
                            // Show success message
                            successText.textContent = data.message;
                            successMessage.classList.remove('d-none');
                            errorMessage.classList.add('d-none');

                            // Reset form
                            form.reset();

                            // Hide success message after 5 seconds
                            setTimeout(() => {
                                successMessage.classList.add('d-none');
                            }, 5000);
                        } else {
                            // Show error message
                            if (data.errors) {
                                displayErrors(data.errors);
                            } else {
                                errorText.textContent = data.message ||
                                    'Something went wrong. Please try again.';
                                errorMessage.classList.remove('d-none');
                                successMessage.classList.add('d-none');
                            }
                        }
                    })
                    .catch(error => {
                        // Reset button
                        submitBtn.disabled = false;
                        btnText.classList.remove('d-none');
                        btnLoading.classList.add('d-none');

                        // Show error message
                        errorText.textContent = 'Failed to send message. Please try again later.';
                        errorMessage.classList.remove('d-none');
                        successMessage.classList.add('d-none');

                        console.error('Error:', error);
                    });
            });

            function clearErrors() {
                // Remove is-invalid class from all inputs
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Clear error messages
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                });
            }

            function displayErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById(field + 'Error');

                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = messages[0];
                    }
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/contact.blade.php ENDPATH**/ ?>