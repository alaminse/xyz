/**
 * NotesHandler - Reusable handler for loading notes based on course, chapter, and lesson
 */
class NotesHandler {
    constructor(config) {
        this.config = {
            courseSelect: '#courseSelect',
            chapterSelect: '#chapterSelect',
            lessonSelect: '#lessonSelect',
            noteSelect: '#note_id',
            notesUrl: '/admin/mcqs/get-notes',
            autoLoad: true, // Auto-load on dropdown changes
            ...config
        };

        if (this.config.autoLoad) {
            this.attachEventListeners();
        }
    }

    /**
     * Attach event listeners to dropdowns
     */
    attachEventListeners() {
        const self = this;

        $(this.config.courseSelect).on('change', function() {
            self.loadNotes();
        });

        $(this.config.chapterSelect).on('change', function() {
            self.loadNotes();
        });

        $(this.config.lessonSelect).on('change', function() {
            self.loadNotes();
        });
    }

    /**
     * Load notes based on selected course, chapter, and lesson
     */
    loadNotes(selectedNoteId = null) {
        const self = this;
        const courseIds = $(this.config.courseSelect).val();
        const chapterId = $(this.config.chapterSelect).val();
        const lessonId = $(this.config.lessonSelect).val();

        // Clear notes dropdown
        $(this.config.noteSelect).empty().append('<option value="">Select Note</option>');

        // Need at least one course ID
        if (!courseIds || courseIds.length === 0) {
            return;
        }

        // Prepare data
        const data = {
            course_ids: Array.isArray(courseIds) ? courseIds : [courseIds]
        };

        if (chapterId) {
            data.chapter_id = chapterId;
        }

        if (lessonId) {
            data.lesson_id = lessonId;
        }

        // Show loading state
        $(this.config.noteSelect).prop('disabled', true);

        // Fetch notes using GET
        $.ajax({
            url: this.config.notesUrl,
            type: 'GET',
            data: data,
            dataType: 'json',
            success: function(response) {
                $(self.config.noteSelect).prop('disabled', false);

                if (response.success && response.notes) {
                    // Populate notes dropdown
                    response.notes.forEach(function(note) {
                        // Check if note should be selected
                        const isSelected = selectedNoteId ? (note.id == selectedNoteId) : false;

                        const option = new Option(
                            note.title,
                            note.id,
                            false,
                            isSelected
                        );
                        $(self.config.noteSelect).append(option);
                    });

                    // Trigger Select2 update
                    $(self.config.noteSelect).trigger('change');

                    // If there's an old value from data attribute, select it
                    if (!selectedNoteId) {
                        const oldNoteId = $(self.config.noteSelect).data('old-value');
                        if (oldNoteId) {
                            $(self.config.noteSelect).val(oldNoteId).trigger('change');
                        }
                    }

                    // Show message if no notes found
                    if (response.count === 0) {
                        console.log('No notes found for the selected criteria');
                    }
                }
            },
            error: function(xhr, status, error) {
                $(self.config.noteSelect).prop('disabled', false);
                console.error('Error loading notes:', error);

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            }
        });
    }

    /**
     * Initialize with existing note ID (for edit mode)
     */
    initializeWithNote(noteId) {
        const self = this;
        setTimeout(function() {
            self.loadNotes(noteId);
        }, 500);
    }

    /**
     * Clear notes dropdown
     */
    clearNotes() {
        $(this.config.noteSelect).empty().append('<option value="">Select Note</option>');
        $(this.config.noteSelect).trigger('change');
    }
}
