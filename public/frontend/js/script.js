document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('searchForm');

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const searchQuery = document.querySelector('.input-search').value;
        // console.log('Search query:', searchQuery);
    });
});
