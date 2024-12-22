<a href="{{ route('auction.show', $auction) }}" class="auction-card-link">
    <div class="auction-card rectangle-div">
        <div class="expire-date">
            <span>Auction expires in: {{ $auction->end_date->diffForHumans() }}</span>
        </div>
        <div class="product-img">
            @if($auction->picture)
                <img src="{{ asset('storage/' . $auction->picture) }}" alt="{{ $auction->title }}">
            @else
                <img src="https://placehold.co/300x300/white/212027" alt="{{ $auction->title }}">
            @endif
        </div>
        <div class="product-info">
            <div class="product-name">
                <span>{{ $auction->title }}</span>
            </div>
            <div class="border"></div>
            <div class="description">
                <span>Description:</span>
                <p>{{ $auction->description }}</p>
            </div>
            <div class="border"></div>
            <div class="prices">
                <div class="entry-price">
                    <span id="price">Entry Price</span>
                    <span id="value">€ {{ $auction->minimum_bid }}</span>
                </div>
                <div class="current-bid-price">
                    <span id="price">Current price</span>
                    <span id="value">€ {{ $auction->current_bid }}</span>
                </div>
            </div>
        </div>
    </div>
</a>