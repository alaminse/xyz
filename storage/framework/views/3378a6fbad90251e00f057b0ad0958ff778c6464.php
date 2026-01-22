
<?php $__env->startSection('title', 'Mock Viva Test'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h5 class="p-3">Mock Viva Test</h5>
    </div>

    <div class="row mt-4">
        <div class="col-sm-12 col-md-7 col-lg-5 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div id="question-container">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="p-2">
                                <button type="button" class="btn btn-sm button-yellow" disabled id="prevBtn">
                                    <i class="bi bi-arrow-left-circle-fill"></i>
                                </button>
                            </div>
                            <div class="p-2">
                                <h6>Question <span id="current-question-number">1</span> of
                                    <span id="total-question-number"><?php echo e(count($questions)); ?></span>
                                </h6>
                            </div>
                            <div class="p-2">
                                <button type="button" class="btn btn-sm button-yellow" id="nextBtn">
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </button>
                            </div>
                        </div>

                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $questionList = json_decode($item->questions); ?>
                            <div class="question-card" id="question-<?php echo e($in); ?>" style="display: none;">
                                <?php $__currentLoopData = $questionList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mt-3 p-3 border rounded text-dark">
                                        <div class="question-text p-2 mb-2 rounded"
                                             style="cursor: pointer; font-weight: bold; background-color: #e2f0fb;">
                                            <?php echo e($index + 1); ?>: <?php echo e($q->question); ?>

                                        </div>
                                        <div class="answer-text p-2 rounded"
                                             style="display: none; background-color: #d4edda;">
                                            <?php echo $q->answer; ?>

                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Finish Button -->
                        <div class="mt-4 text-center" id="finishSection" style="display: none;">
                            <a href="<?php echo e(route('mockvivas.index', ['course' => $course_slug])); ?>" class="btn button-yellow px-5">
                                Finish
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(function () {
                let questions = $('.question-card');
                let currentIndex = 0;

                function showQuestion(index) {
                    questions.hide();
                    $('#finishSection').hide();

                    $(questions[index]).fadeIn();
                    $('#current-question-number').text(index + 1);

                    $('#prevBtn').prop('disabled', index === 0);
                    $('#nextBtn').prop('disabled', index === questions.length - 1);

                    // Show "Finish" button only on the last question
                    if (index === questions.length - 1) {
                        $('#finishSection').fadeIn();
                    }
                }

                $(document).on('click', '.question-text', function () {
                    $(this).next('.answer-text').slideToggle();
                });

                $('#nextBtn').on('click', function () {
                    if (currentIndex < questions.length - 1) {
                        currentIndex++;
                        showQuestion(currentIndex);
                    }
                });

                $('#prevBtn').on('click', function () {
                    if (currentIndex > 0) {
                        currentIndex--;
                        showQuestion(currentIndex);
                    }
                });

                showQuestion(currentIndex);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/mock-viva/test.blade.php ENDPATH**/ ?>