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


//mimicking diffForHumans
function timeFromNow(dateString) {
    const now = new Date();
    const targetDate = new Date(dateString);
    const diffMs = targetDate - now; // Difference in milliseconds
    const diffSeconds = Math.round(diffMs / 1000);
    const diffMinutes = Math.round(diffMs / (1000 * 60));
    const diffHours = Math.round(diffMs / (1000 * 60 * 60));
    const diffDays = Math.round(diffMs / (1000 * 60 * 60 * 24));
    const diffWeeks = Math.round(diffMs / (1000 * 60 * 60 * 24 * 7));
    const diffMonths = Math.round(diffMs / (1000 * 60 * 60 * 24 * 30));
    const diffYears = Math.round(diffMs / (1000 * 60 * 60 * 24 * 365));

    const pluralize = (count, singular, plural) => (count === 1 ? singular : plural);

    if (diffMs > 0) {
        if (diffSeconds < 60) {
            return `${diffSeconds} ${pluralize(diffSeconds, 'second', 'seconds')} from now`;
        } else if (diffMinutes < 60) {
            return `${diffMinutes} ${pluralize(diffMinutes, 'minute', 'minutes')} from now`;
        } else if (diffHours < 24) {
            return `${diffHours} ${pluralize(diffHours, 'hour', 'hours')} from now`;
        } else if (diffDays < 7) {
            return `${diffDays} ${pluralize(diffDays, 'day', 'days')} from now`;
        } else if (diffWeeks < 4) {
            return `${diffWeeks} ${pluralize(diffWeeks, 'week', 'weeks')} from now`;
        } else if (diffMonths < 12) {
            return `${diffMonths} ${pluralize(diffMonths, 'month', 'months')} from now`;
        } else {
            return `${diffYears} ${pluralize(diffYears, 'year', 'years')} from now`;
        }
    } else {
        const pastDiffSeconds = Math.abs(diffSeconds);
        const pastDiffMinutes = Math.abs(diffMinutes);
        const pastDiffHours = Math.abs(diffHours);
        const pastDiffDays = Math.abs(diffDays);
        const pastDiffWeeks = Math.abs(diffWeeks);
        const pastDiffMonths = Math.abs(diffMonths);
        const pastDiffYears = Math.abs(diffYears);

        if (pastDiffSeconds < 60) {
            return `${pastDiffSeconds} ${pluralize(pastDiffSeconds, 'second', 'seconds')} ago`;
        } else if (pastDiffMinutes < 60) {
            return `${pastDiffMinutes} ${pluralize(pastDiffMinutes, 'minute', 'minutes')} ago`;
        } else if (pastDiffHours < 24) {
            return `${pastDiffHours} ${pluralize(pastDiffHours, 'hour', 'hours')} ago`;
        } else if (pastDiffDays < 7) {
            return `${pastDiffDays} ${pluralize(pastDiffDays, 'day', 'days')} ago`;
        } else if (pastDiffWeeks < 4) {
            return `${pastDiffWeeks} ${pluralize(pastDiffWeeks, 'week', 'weeks')} ago`;
        } else if (pastDiffMonths < 12) {
            return `${pastDiffMonths} ${pluralize(pastDiffMonths, 'month', 'months')} ago`;
        } else {
            return `${pastDiffYears} ${pluralize(pastDiffYears, 'year', 'years')} ago`;
        }
    }
}


