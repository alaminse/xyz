function showDeleteConfirmation(event) {
    if (!window.confirm("Are you sure you want to delete?")) {
        // If the user cancels the action, prevent the default form submission
        event.preventDefault();
    }
}
