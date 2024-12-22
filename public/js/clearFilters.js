document.getElementById('clear-filters').addEventListener('click', function(event) {
    event.preventDefault();

    const price = document.getElementById('sort-select');
    if (price) price.value = '';

    // Clear category
    const category = document.getElementById('category');
    if (category) category.value = '';

    // Clear entry price range
    const entryPriceRange = document.getElementById('entry-price-range');
    if (entryPriceRange) entryPriceRange.value = 0;

    const entryPriceDisplay = document.getElementById('entry-price-value');
    entryPriceDisplay.textContent = entryPriceRange.value;

    const currentBidRange = document.getElementById('current-bid-range');
    if (currentBidRange) currentBidRange.value = 10000;

    const currentBidDisplay = document.getElementById('current-bid-value');
    currentBidDisplay.textContent = currentBidRange.value;

    // Log success
    console.log("Filters have been cleared");
});
