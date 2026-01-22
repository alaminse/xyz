/**
 * DependentDropdownHandler - Reusable handler for Course -> Chapter -> Lesson cascading dropdowns
 * File: backend/js/dependent-dropdown-handler.js
 *
 * Usage: Can be used for Notes, Quizzes, Assignments, Videos, or any module that has
 * Course -> Chapter -> Lesson relationship
 */
class DependentDropdownHandler {
    constructor(config) {
        this.config = {
            courseSelect: '#courseSelect',
            chapterSelect: '#chapterSelect',
            lessonSelect: '#lessonSelect',
            chaptersUrl: '/admin/chapters/get',
            lessonsUrl: '/admin/lessons/get',
            moduleType: 'note', // note, quiz, assignment, video, etc.
            allowMultipleCourses: true, // Set to false for single course selection
            ...config
        };

        this.init();
    }

    /**
     * Initialize Select2 and attach event listeners
     */
    init() {
        this.initializeSelect2();
        this.attachEventListeners();
    }

    /**
     * Initialize Select2 on select elements
     */
    initializeSelect2() {
        $('.select2').select2();
    }

    /**
     * Attach event listeners to form elements
     */
    attachEventListeners() {
        const self = this;

        // Course selection change
        $(this.config.courseSelect).on('change', function() {
            const courseIds = self.config.allowMultipleCourses
                ? $(this).val() // Array for multiple
                : [$(this).val()]; // Convert single to array

            console.log('Selected course IDs:', courseIds);

            if (courseIds && courseIds.length > 0 && courseIds[0] !== null) {
                self.fetchChapters(courseIds);
            } else {
                self.clearChaptersAndLessons();
            }
        });

        // Chapter selection change
        $(this.config.chapterSelect).on('change', function() {
            const chapterId = $(this).val();
            console.log('Selected chapter ID:', chapterId);

            if (chapterId) {
                self.fetchLessons(chapterId);
            } else {
                self.clearLessons();
            }
        });
    }

    /**
     * Fetch chapters based on selected course IDs
     */
    fetchChapters(courseIds, selectedChapterId = null) {
        const self = this;

        if (!courseIds || courseIds.length === 0) {
            this.clearChaptersAndLessons();
            return;
        }

        $.ajax({
            url: this.config.chaptersUrl,
            method: 'GET',
            data: {
                course_ids: courseIds,
                for: this.config.moduleType
            },
            beforeSend: function() {
                $(self.config.chapterSelect).empty().append(
                    '<option value="" selected disabled>Loading...</option>'
                );
            },
            success: function(response) {
                console.log('Chapters response:', response);

                $(self.config.chapterSelect).empty().append(
                    '<option value="" selected disabled>Select Chapter</option>'
                );

                if (response.chapters && response.chapters.length > 0) {
                    $.each(response.chapters, function(index, chapter) {
                        const selected = selectedChapterId == chapter.id ? 'selected' : '';
                        $(self.config.chapterSelect).append(
                            `<option value="${chapter.id}" ${selected}>${chapter.name}</option>`
                        );
                    });
                } else {
                    $(self.config.chapterSelect).append(
                        '<option value="" disabled>No chapters available for selected courses</option>'
                    );
                }

                self.clearLessons();
            },
            error: function(xhr, status, error) {
                console.error('Error loading chapters:', error);
                $(self.config.chapterSelect).empty().append(
                    '<option value="" selected disabled>Error loading chapters</option>'
                );
            }
        });
    }

    /**
     * Fetch lessons based on selected chapter ID
     */
    fetchLessons(chapterId, selectedLessonId = null) {
        const self = this;

        if (!chapterId) {
            this.clearLessons();
            return;
        }

        $.ajax({
            url: this.config.lessonsUrl,
            method: 'GET',
            data: {
                chapterid: chapterId,
                for: this.config.moduleType
            },
            beforeSend: function() {
                $(self.config.lessonSelect).empty().append(
                    '<option value="" selected disabled>Loading...</option>'
                );
            },
            success: function(response) {
                console.log('Lessons response:', response);

                $(self.config.lessonSelect).empty().append(
                    '<option value="" selected disabled>Select Lesson</option>'
                );

                if (response.chapter && response.chapter.lessons && response.chapter.lessons.length > 0) {
                    $.each(response.chapter.lessons, function(index, lesson) {
                        const selected = selectedLessonId == lesson.id ? 'selected' : '';
                        $(self.config.lessonSelect).append(
                            `<option value="${lesson.id}" ${selected}>${lesson.name}</option>`
                        );
                    });
                } else {
                    $(self.config.lessonSelect).append(
                        '<option value="" disabled>No lessons available</option>'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading lessons:', error);
                $(self.config.lessonSelect).empty().append(
                    '<option value="" selected disabled>Error loading lessons</option>'
                );
            }
        });
    }

    /**
     * Clear chapters and lessons dropdowns
     */
    clearChaptersAndLessons() {
        this.clearChapters();
        this.clearLessons();
    }

    /**
     * Clear only chapters dropdown
     */
    clearChapters() {
        $(this.config.chapterSelect).empty().append(
            '<option value="" selected disabled>Select Chapter</option>'
        );
    }

    /**
     * Clear only lessons dropdown
     */
    clearLessons() {
        $(this.config.lessonSelect).empty().append(
            '<option value="" selected disabled>Select Lesson</option>'
        );
    }

    /**
     * Initialize form with existing data (for edit mode or validation errors)
     */
    initializeWithData(courseIds, chapterId, lessonId) {
        const self = this;

        console.log('Initializing with data:', {
            courseIds: courseIds,
            chapterId: chapterId,
            lessonId: lessonId
        });

        // Ensure courseIds is an array
        if (!Array.isArray(courseIds)) {
            courseIds = courseIds ? [courseIds] : [];
        }

        if (courseIds && courseIds.length > 0) {
            this.fetchChapters(courseIds, chapterId);

            if (chapterId) {
                // Wait for chapters to load before fetching lessons
                setTimeout(function() {
                    self.fetchLessons(chapterId, lessonId);
                }, 500);
            }
        }
    }

    /**
     * Get currently selected values
     */
    getSelectedValues() {
        return {
            courseIds: $(this.config.courseSelect).val(),
            chapterId: $(this.config.chapterSelect).val(),
            lessonId: $(this.config.lessonSelect).val()
        };
    }

    /**
     * Reset all dropdowns
     */
    reset() {
        $(this.config.courseSelect).val(null).trigger('change');
        this.clearChaptersAndLessons();
    }
}

/**
 * SummernoteHandler - Reusable handler for Summernote WYSIWYG editor with image upload
 * File: backend/js/summernote-handler.js
 */
class SummernoteHandler {
    constructor(config) {
        this.config = {
            selector: '.summernote',
            height: 300,
            csrfToken: '',
            uploadUrl: '',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            ...config
        };

        this.init();
    }

    /**
     * Initialize Summernote
     */
    init() {
        const self = this;

        $(this.config.selector).summernote({
            height: this.config.height,
            toolbar: this.config.toolbar,
            callbacks: {
                onImageUpload: function(files) {
                    self.uploadImage(files[0], $(this));
                }
            }
        });
    }

    /**
     * Upload image to server
     */
    uploadImage(file, summernoteInstance) {
        const formData = new FormData();
        formData.append('image', file);
        const url = this.config.uploadUrl + "?_token=" + this.config.csrfToken;

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
                console.error('Error uploading image:', xhr.responseText);
                alert('Failed to upload image. Please try again.');
            }
        });
    }

    /**
     * Get content from Summernote
     */
    getContent() {
        return $(this.config.selector).summernote('code');
    }

    /**
     * Set content to Summernote
     */
    setContent(content) {
        $(this.config.selector).summernote('code', content);
    }

    /**
     * Clear Summernote content
     */
    clear() {
        $(this.config.selector).summernote('reset');
    }

    /**
     * Destroy Summernote instance
     */
    destroy() {
        $(this.config.selector).summernote('destroy');
    }
}


/**
 * FormHandler - Combines DependentDropdown and Summernote for complete form management
 * Use this for modules that need both dependent dropdowns and WYSIWYG editor
 */
class FormHandler {
    constructor(config) {
        this.config = {
            // Dependent dropdown config
            courseSelect: '#courseSelect',
            chapterSelect: '#chapterSelect',
            lessonSelect: '#lessonSelect',
            chaptersUrl: '/admin/chapters/get',
            lessonsUrl: '/admin/lessons/get',
            moduleType: 'note',
            allowMultipleCourses: true,

            // Summernote config
            summernoteSelector: '.summernote',
            summernoteHeight: 300,
            csrfToken: '',
            summernoteUploadUrl: '',

            ...config
        };

        this.init();
    }

    /**
     * Initialize both handlers
     */
    init() {
        // Initialize dependent dropdowns
        this.dropdownHandler = new DependentDropdownHandler({
            courseSelect: this.config.courseSelect,
            chapterSelect: this.config.chapterSelect,
            lessonSelect: this.config.lessonSelect,
            chaptersUrl: this.config.chaptersUrl,
            lessonsUrl: this.config.lessonsUrl,
            moduleType: this.config.moduleType,
            allowMultipleCourses: this.config.allowMultipleCourses
        });

        // Initialize Summernote if selector exists
        if ($(this.config.summernoteSelector).length > 0) {
            this.summernoteHandler = new SummernoteHandler({
                selector: this.config.summernoteSelector,
                height: this.config.summernoteHeight,
                csrfToken: this.config.csrfToken,
                uploadUrl: this.config.summernoteUploadUrl
            });
        }
    }

    /**
     * Initialize with existing data
     */
    initializeWithData(courseIds, chapterId, lessonId) {
        this.dropdownHandler.initializeWithData(courseIds, chapterId, lessonId);
    }

    /**
     * Get all form values
     */
    getFormValues() {
        const values = this.dropdownHandler.getSelectedValues();

        if (this.summernoteHandler) {
            values.content = this.summernoteHandler.getContent();
        }

        return values;
    }

    /**
     * Reset entire form
     */
    reset() {
        this.dropdownHandler.reset();

        if (this.summernoteHandler) {
            this.summernoteHandler.clear();
        }
    }
}

// Make classes available globally
if (typeof window !== 'undefined') {
    window.DependentDropdownHandler = DependentDropdownHandler;
    window.SummernoteHandler = SummernoteHandler;
    window.FormHandler = FormHandler;
}
