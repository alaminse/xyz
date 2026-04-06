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

        // ✅ isPaid + isLocked replaces isPremium
        this.isPaid   = config.isPaid   ?? false;
        this.isLocked = config.isLocked ?? false;

        // ✅ Truly locked only if isPaid AND isLocked
        this.contentLocked = this.isPaid && this.isLocked;

        this.currentIndex = 0;
        this.selectedOptions = {};
        this.totalTimeInSeconds = this.time * 60;
        this.timeRemaining = this.totalTimeInSeconds;
        this.timer = null;
        this.maxVisitedIndex = 0;

        this.init();
    }

    init() {
        this.initializeSelectedOptions();
        this.bindEvents();
        this.startTimer();
        this.showQuestion(this.currentIndex);
        this.updateResult();
    }

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

    bindEvents() {
        const self = this;

        $(document).on('click', '.next', function() {
            self.handleNext();
        });

        $(document).on('click', '.back', function() {
            self.handleBack();
        });

        $(document).on('click', '.submit', function() {
            // 🔒 Block submit if content is locked
            if (self.contentLocked) return;
            self.showSubmitModal();
        });

        $(document).on('click', '.submit-from-sidebar', function() {
            // 🔒 Block submit if content is locked
            if (self.contentLocked) return;
            self.showSubmitModal();
        });

        $(document).on('click', '#confirmSubmitBtn', function() {
            self.handleConfirmSubmit();
        });

        $(document).on('click', '.progress-item', function() {
            const index = $(this).data('index');
            self.showQuestion(index);
        });

        $(document).on('change', 'input[type=radio]', function() {
            self.handleRadioChange($(this));
        });
    }

    startTimer() {
        if (this.timer) {
            clearInterval(this.timer);
        }

        const self = this;
        this.timer = setInterval(function() {
            if (self.timeRemaining <= 0) {
                clearInterval(self.timer);

                // 🔒 Don't auto-submit if content is locked
                if (self.contentLocked) {
                    alert("Time's up!");
                    return;
                }

                alert("Time's up! Submitting your assessment...");
                self.submitAnswer();
                return;
            }

            const minutes = Math.floor(self.timeRemaining / 60).toString().padStart(2, '0');
            const seconds = (self.timeRemaining % 60).toString().padStart(2, '0');

            $("#timer").text(`${minutes}:${seconds}`);

            if (self.timeRemaining <= 300 && self.timeRemaining > 0) {
                $("#timer").addClass('timer-warning');
            }

            self.timeRemaining--;
        }, 1000);
    }

    showQuestion(index) {
        this.currentIndex = index;

        if (index > this.maxVisitedIndex) {
            this.maxVisitedIndex = index;
        }

        $('.sba-question, .mcq-question').hide();
        $(`.sba-question[data-index=${index}], .mcq-question[data-index=${index}]`).show();

        this.restoreSelectedState(index);
        this.updateResult();
    }

    restoreSelectedState(index) {
        $(`.sba-question[data-index=${index}] .option-container`).removeClass('selected');

        if (this.selectedOptions[index]) {
            if (this.selectedOptions[index].type === 'sba' && this.selectedOptions[index].option) {
                const optionValue = this.selectedOptions[index].option;
                $(`.sba-question[data-index=${index}] .option-container[data-option="${optionValue}"]`)
                    .addClass('selected');
            }
        }
    }

    handleNext() {
        if (this.currentIndex < this.questions.length - 1) {
            this.currentIndex++;
            this.showQuestion(this.currentIndex);
        }
    }

    handleBack() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.showQuestion(this.currentIndex);
        }
    }

    handleRadioChange($radio) {
        const optionValue = $radio.val();
        const $container = $radio.closest(".sba-question, .mcq-question");
        const questionId = $container.data("questionid");
        const questionType = $container.data("type");
        const questionIndex = $container.data("index");

        if (questionId === undefined) return;

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
            this.selectedOptions[questionIndex]['option'] = optionValue;
            $container.find('.option-container').removeClass('selected');
            $radio.closest('.option-container').addClass('selected');
        }

        this.updateResult();
    }

    showSubmitModal() {
        let answeredCount = 0;

        this.questions.forEach((question, index) => {
            const questionType = question.question_type;
            let isAnswered = false;

            if (questionType === 'sba') {
                const selectedOption = this.selectedOptions[index]?.option;
                isAnswered = selectedOption !== undefined && selectedOption !== null && selectedOption !== '';
            } else if (questionType === 'mcq') {
                const selectedOptionsList = this.selectedOptions[index]?.options || {};
                isAnswered = Object.keys(selectedOptionsList).length > 0;
            }

            if (isAnswered) answeredCount++;
        });

        const remainingCount = this.questions.length - answeredCount;

        $('#modal-answered-questions').text(answeredCount);
        $('#modal-remaining-questions').text(remainingCount);

        const submitModal = new bootstrap.Modal(document.getElementById('submitConfirmModal'));
        submitModal.show();
    }

    handleConfirmSubmit() {
        const submitModal = bootstrap.Modal.getInstance(document.getElementById('submitConfirmModal'));
        if (submitModal) {
            submitModal.hide();
        }
        this.submitAnswer();
    }

    submitAnswer() {
        if (this.timer) {
            clearInterval(this.timer);
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.submitRoute;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = this.csrfToken;
        form.appendChild(csrfToken);

        const assessmentIdInput = document.createElement('input');
        assessmentIdInput.type = 'hidden';
        assessmentIdInput.name = 'assessment_id';
        assessmentIdInput.value = this.assessmentId;
        form.appendChild(assessmentIdInput);

        const courseInput = document.createElement('input');
        courseInput.type = 'hidden';
        courseInput.name = 'course_id';
        courseInput.value = this.courseId;
        form.appendChild(courseInput);

        const selectedOptionsInput = document.createElement('input');
        selectedOptionsInput.type = 'hidden';
        selectedOptionsInput.name = 'selected_options';
        selectedOptionsInput.value = JSON.stringify(this.selectedOptions);
        form.appendChild(selectedOptionsInput);

        document.body.appendChild(form);
        form.submit();
    }

    updateResult() {
        let resultHtml = "";
        let answeredCount = 0;

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
                isAnswered = Object.keys(selectedOptionsList).length > 0;
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

        // ✅ Show lock note if isPaid && isLocked
        const lockNote = this.contentLocked
            ? `<small class="d-block mt-2 text-center"
                    style="color:#92400e; font-size:11px; background:#fffbeb;
                           border:1px dashed #f59e0b; border-radius:4px; padding:4px 8px;">
                   🔒 This is Premium content — Upgrade to unlock answers
               </small>`
            : '';

        // ✅ Hide submit button in sidebar if locked
        const submitButtonHtml = this.contentLocked
            ? `<div class="submit-section">
                   <div class="text-center py-3 px-2"
                       style="background:#fff8e1; border:2px dashed #ffc107; border-radius:8px;">
                       <i class="bi bi-lock-fill text-warning" style="font-size:1.5rem;"></i>
                       <p class="mb-2 mt-1 fw-semibold" style="font-size:13px;">Premium Content</p>
                       <small class="text-muted">Upgrade your plan to submit and see answers.</small>
                   </div>
               </div>`
            : `<div class="submit-section">
                   <button type="button" class="btn btn-success btn-block submit-from-sidebar">
                       <i class="fas fa-paper-plane"></i> Submit Assessment
                   </button>
                   <small class="text-muted d-block mt-2 text-center">
                       ${answeredCount} of ${this.questions.length} total questions answered
                   </small>
                   ${lockNote}
               </div>`;

        $("#progress-count").html(summaryHtml);
        $("#selected-options-count").html(resultHtml);
        $("#submitButton").html(submitButtonHtml);
    }
}

$(document).ready(function() {
    if (typeof assessmentConfig !== 'undefined') {
        new AssessmentTest(assessmentConfig);
    }
});
