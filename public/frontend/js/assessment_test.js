/**
 * Assessment Test JavaScript
 * Handles quiz navigation, timer, and submission
 */

class AssessmentTest {
    constructor(config) {
        this.assessmentId = config.assessmentId;
        this.courseId = config.courseId;
        this.questions = config.questions;
        this.time = config.time;
        this.csrfToken = config.csrfToken;
        this.submitRoute = config.submitRoute;

        this.currentIndex = 0;
        this.selectedOptions = {};
        this.totalTimeInSeconds = this.time * 60;
        this.timeRemaining = this.totalTimeInSeconds;
        this.timer = null;
        this.maxVisitedIndex = 0;

        this.init();
    }

    /**
     * Initialize the assessment
     */
    init() {
        this.initializeSelectedOptions();
        this.bindEvents();
        this.startTimer();
        this.showQuestion(this.currentIndex);
        this.updateResult();
    }

    /**
     * Initialize selectedOptions for all questions
     */
    initializeSelectedOptions() {
        this.questions.forEach((question, index) => {
            this.selectedOptions[index] = {
                id: question.id,
                type: question.question_type
            };
            if (question.question_type === 'mcq') {
                this.selectedOptions[index].options = {};
            }
        });
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        const self = this;

        // Navigation buttons
        $(document).on('click', '.next', function() {
            self.handleNext();
        });

        $(document).on('click', '.back', function() {
            self.handleBack();
        });

        // Submit buttons
        $(document).on('click', '.submit', function() {
            self.showSubmitModal();
        });

        $(document).on('click', '.submit-from-sidebar', function() {
            self.showSubmitModal();
        });

        $(document).on('click', '#confirmSubmitBtn', function() {
            self.handleConfirmSubmit();
        });

        // Progress item click
        $(document).on('click', '.progress-item', function() {
            const index = $(this).data('index');
            self.showQuestion(index);
        });

        // Radio button change
        $(document).on('change', 'input[type=radio]', function() {
            self.handleRadioChange($(this));
        });
    }

    /**
     * Start countdown timer
     */
    startTimer() {
        if (this.timer) {
            clearInterval(this.timer);
        }

        const self = this;
        this.timer = setInterval(function() {
            if (self.timeRemaining <= 0) {
                clearInterval(self.timer);
                alert("Time's up! Submitting your assessment...");
                self.submitAnswer();
                return;
            }

            const minutes = Math.floor(self.timeRemaining / 60).toString().padStart(2, '0');
            const seconds = (self.timeRemaining % 60).toString().padStart(2, '0');

            $("#timer").text(`${minutes}:${seconds}`);

            // Warning when less than 5 minutes
            if (self.timeRemaining <= 300 && self.timeRemaining > 0) {
                $("#timer").addClass('timer-warning');
            }

            self.timeRemaining--;
        }, 1000);
    }

    /**
     * Show specific question by index
     */
    showQuestion(index) {
        this.currentIndex = index;

        // Update max visited index
        if (index > this.maxVisitedIndex) {
            this.maxVisitedIndex = index;
        }

        // Hide all questions and show current
        $('.sba-question, .mcq-question').hide();
        $(`.sba-question[data-index=${index}], .mcq-question[data-index=${index}]`).show();

        // Restore selected state visually
        this.restoreSelectedState(index);

        // Update progress display
        this.updateResult();
    }

    /**
     * Restore selected state for a question
     */
    restoreSelectedState(index) {
        // Remove all selected classes first
        $(`.sba-question[data-index=${index}] .option-container`).removeClass('selected');

        if (this.selectedOptions[index]) {
            if (this.selectedOptions[index].type === 'sba' && this.selectedOptions[index].option) {
                // Restore SBA selection
                const optionValue = this.selectedOptions[index].option;
                $(`.sba-question[data-index=${index}] .option-container[data-option="${optionValue}"]`)
                    .addClass('selected');
            }
            // MCQ selections are already preserved in radio buttons
        }
    }

    /**
     * Handle next button click
     */
    handleNext() {
        if (this.currentIndex < this.questions.length - 1) {
            this.currentIndex++;
            this.showQuestion(this.currentIndex);
        }
    }

    /**
     * Handle back button click
     */
    handleBack() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.showQuestion(this.currentIndex);
        }
    }

    /**
     * Handle radio button change
     */
    handleRadioChange($radio) {
        const optionValue = $radio.val();
        const $container = $radio.closest(".sba-question, .mcq-question");
        const questionId = $container.data("questionid");
        const questionType = $container.data("type");
        const questionIndex = $container.data("index");

        if (questionId === undefined) return;

        // Initialize if not exists
        if (!this.selectedOptions[questionIndex]) {
            this.selectedOptions[questionIndex] = {
                id: questionId,
                type: questionType
            };
        }

        if (questionType === 'mcq') {
            const optionId = $radio.attr("name").match(/\[(.*)\]/)?.[1];
            if (optionId) {
                if (!this.selectedOptions[questionIndex]['options']) {
                    this.selectedOptions[questionIndex]['options'] = {};
                }
                this.selectedOptions[questionIndex]['options'][optionId] = optionValue;
            }
        } else if (questionType === 'sba') {
            // Set the option value
            this.selectedOptions[questionIndex]['option'] = optionValue;

            // Add visual feedback for SBA
            $container.find('.option-container').removeClass('selected');
            $radio.closest('.option-container').addClass('selected');
        }

        // Update the result display
        this.updateResult();
    }

    /**
     * Show submit confirmation modal
     */
    showSubmitModal() {
        let answeredCount = 0;

        // Count answered questions
        this.questions.forEach((question, index) => {
            const questionType = question.question_type;
            let isAnswered = false;

            if (questionType === 'sba') {
                const selectedOption = this.selectedOptions[index]?.option;
                isAnswered = selectedOption !== undefined && selectedOption !== null && selectedOption !== '';
            } else if (questionType === 'mcq') {
                const selectedOptionsList = this.selectedOptions[index]?.options || {};
                const optionsCount = Object.keys(selectedOptionsList).length;
                isAnswered = optionsCount > 0;
            }

            if (isAnswered) {
                answeredCount++;
            }
        });

        const remainingCount = this.questions.length - answeredCount;

        // Update modal content
        $('#modal-answered-questions').text(answeredCount);
        $('#modal-remaining-questions').text(remainingCount);

        // Show modal
        const submitModal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
        submitModal.show();
    }

    /**
     * Handle confirm submit button click
     */
    handleConfirmSubmit() {
        // Close modal
        const submitModal = bootstrap.Modal.getInstance(document.getElementById('submitConfirmModal'));
        if (submitModal) {
            submitModal.hide();
        }

        // Submit the assessment
        this.submitAnswer();
    }

    /**
     * Submit the assessment
     */
    submitAnswer() {
        if (this.timer) {
            clearInterval(this.timer);
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.submitRoute;

        // CSRF Token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = this.csrfToken;
        form.appendChild(csrfToken);

        // Assessment ID
        const assessmentIdInput = document.createElement('input');
        assessmentIdInput.type = 'hidden';
        assessmentIdInput.name = 'assessment_id';
        assessmentIdInput.value = this.assessmentId;
        form.appendChild(assessmentIdInput);

        // Course ID
        const courseInput = document.createElement('input');
        courseInput.type = 'hidden';
        courseInput.name = 'course_id';
        courseInput.value = this.courseId;
        form.appendChild(courseInput);

        // Selected Options
        const selectedOptionsInput = document.createElement('input');
        selectedOptionsInput.type = 'hidden';
        selectedOptionsInput.name = 'selected_options';
        selectedOptionsInput.value = JSON.stringify(this.selectedOptions);
        form.appendChild(selectedOptionsInput);

        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Update progress display
     */
    updateResult() {
        let resultHtml = "";
        let answeredCount = 0;

        // Only show questions up to maxVisitedIndex
        for (let index = 0; index <= this.maxVisitedIndex; index++) {
            const question = this.questions[index];
            const questionType = question.question_type;
            let isAnswered = false;
            let statusIcon = '';

            if (questionType === 'sba') {
                const selectedOption = this.selectedOptions[index]?.option;
                isAnswered = selectedOption !== undefined && selectedOption !== null && selectedOption !== '';
            } else if (questionType === 'mcq') {
                const selectedOptionsList = this.selectedOptions[index]?.options || {};
                const optionsCount = Object.keys(selectedOptionsList).length;
                isAnswered = optionsCount > 0;
            }

            if (isAnswered) {
                answeredCount++;
                statusIcon = '<i class="fas fa-check-circle"></i>';
            } else {
                statusIcon = '<i class="far fa-circle"></i>';
            }

            resultHtml += `
                <li class="progress-item ${isAnswered ? 'answered' : 'unanswered'} ${index === this.currentIndex ? 'active' : ''}"
                    data-index="${index}" style="cursor: pointer;">
                    <span class="progress-number">Question ${index + 1}</span>
                    <span class="progress-status">${statusIcon}</span>
                </li>
            `;
        }

        // Summary at the top
        const summaryHtml = `
            <div class="summary-stat">
                <span class="stat-label">Answered:</span>
                <span class="stat-value">${answeredCount} / ${this.questions.length}</span>
            </div>
            <div class="progress-summary">
                <div class="progress-bar-wrapper">
                    <div class="progress-bar-fill" style="width: ${(answeredCount / this.questions.length * 100)}%"></div>
                </div>
            </div>
        `;

        // Submit button always visible
        const submitButtonHtml = `
            <div class="submit-section">
                <button type="button" class="btn btn-success btn-block submit-from-sidebar">
                    <i class="fas fa-paper-plane"></i> Submit Assessment
                </button>
                <small class="text-muted d-block mt-2 text-center">
                    ${answeredCount} of ${this.questions.length} total questions answered
                </small>
            </div>
        `;

        $("#progress-count").html(summaryHtml);
        $("#selected-options-count").html(resultHtml);
        $("#submitButton").html(submitButtonHtml);
    }
}

// Initialize when document is ready
$(document).ready(function() {
    // Check if assessment config exists
    if (typeof assessmentConfig !== 'undefined') {
        new AssessmentTest(assessmentConfig);
    }
});
