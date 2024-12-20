document.addEventListener('DOMContentLoaded', function () {

    const sortSelect = document.getElementById('sort-select');
    const categorySelect = document.getElementById('category');
    const minPriceInput = document.querySelector('.entry-price input');
    const maxPriceInput = document.querySelector('.current-bid input');
    const applyFiltersButton = document.querySelector('.apply-filters');
    const clearFilterButton = document.getElementById('clear-filters');
    const cardsContainer = document.querySelector('.all-cards'); // Updated to target only the grid

    async function fetchFilteredAuctions() {
        const sortBy = sortSelect.value;
        const categoryId = categorySelect.value;
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;

        const queryParams = new URLSearchParams({
            sort_by: sortBy,
            category_id: categoryId,
            min_price: minPrice,
            max_price: maxPrice,
        });

        try {
            const response = await fetch(`/api/auction/filter?${queryParams.toString()}`);
            const data = await response.json();

            if (data.status === 'success') {
                renderAuctions(data.auctions);
            } else {
                console.error('Failed to fetch auctions');
            }
        } catch (error) {
            console.error('Failed to fetch auctions:', error);
        }
    }

    function renderAuctions(auctions) {
        cardsContainer.innerHTML = ''; // Clear existing cards only

        if (auctions.length === 0) {
            cardsContainer.innerHTML = '<p>No auctions found.</p>';
            return;
        }

        auctions.forEach(auction => {
            const imageUrl = auction.picture
                ? `${baseUrl}/storage/${auction.picture}`
                : 'https://placehold.co/300x300/white/212027';
            const auctionCard = `
                <a href="${baseUrl}/auction/${auction.id}" class="auction-card-link">
                    <div class="auction-card rectangle-div">
                        <div class="expire-date">
                            <span>Auction expires in: ${timeFromNow(auction.end_date)}</span>
                        </div>
                        <div class="product-img">
                            <img src="${imageUrl}" alt="${auction.title}">
                        </div>
                        <div class="product-info">
                            <div class="product-name">
                                <span>${auction.title}</span>
                            </div>
                            <div class="border"></div>
                            <div class="description">
                                <span>Description:</span>
                                <p>${auction.description}</p>
                            </div>
                            <div class="border"></div>
                            <div class="prices">
                                <div class="entry-price">
                                    <span id="price">Entry Price</span>
                                    <span id="value">$${auction.minimum_bid}</span>
                                </div>
                                <div class="current-bid-price">
                                    <span id="price">Current price</span>
                                    <span id="value">$${auction.current_bid}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            `;
            cardsContainer.innerHTML += auctionCard;
        });
    }

    applyFiltersButton.addEventListener('click', function (event) {
        event.preventDefault();
        fetchFilteredAuctions();
    });

    clearFilterButton.addEventListener('click', function (event) {
        event.preventDefault();

        sortSelect.value = '';
        categorySelect.value = '';
        minPriceInput.value = 0;
        maxPriceInput.value = 10000;

        fetchFilteredAuctions();
    });
});

function timeFromNow(endDate) {
    const now = new Date();
    const end = new Date(endDate);
    const distance = end - now;

    if (distance < 0) {
        return "Auction ended";
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
}
