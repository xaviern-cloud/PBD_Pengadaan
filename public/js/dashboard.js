document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchKatalog');
    const cards = document.querySelectorAll('.katalog-card');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();

            cards.forEach(function (card) {
                const text = card.getAttribute('data-search') || '';
                card.style.display = text.includes(keyword) ? 'flex' : 'none';
            });
        });
    }
});
