<div class="mt-3 p-3">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h2 class="mb-4 text-center note-title"> <u>{{ $note->title }}</u> </h2>
            <div class="note-description">
                {!! $note->description !!}
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const query = @json($query ?? ''); // Pass the query from the server to JavaScript
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
