<div class="mt-3 p-3">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h2 class="mb-4 text-center note-title"> <u><?php echo e($note->title); ?></u> </h2>
            <div class="note-description">
                <?php echo $note->description; ?>

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const query = <?php echo json_encode($query ?? '', 15, 512) ?>; // Pass the query from the server to JavaScript
        if (query) {
            // Target the element that contains the note's description
            const noteDescription = document.querySelector('.note-description'); // Adjust if necessary
            const noteTitle = document.querySelector('.note-title'); // Adjust if necessary

            if (noteDescription || noteTitle) {
                const regex = new RegExp(query, 'gi'); // Create a regex for case-insensitive matching
                noteDescription.innerHTML = noteDescription.innerHTML.replace(regex, match =>
                    `<span style="color: #F1A909;">${match}</span>`);
                noteTitle.innerHTML = noteTitle.innerHTML.replace(regex, match =>
                    `<span style="color: #F1A909;">${match}</span>`);
            }
        }
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/notes/partial.blade.php ENDPATH**/ ?>