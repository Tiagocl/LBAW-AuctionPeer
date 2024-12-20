@extends('layouts.app')

@section('title', 'Cards')

@section('content')

<div class="main-page">
    <div class="filter-section">
    <div class="filter">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    <span>Filter</span>
    </div>
        <div id="sort-by">
            <label for="sort-by">Sort By:</label>
            <select name="sort-by" id="sort-select">
                <option value="">Select Price</option>
                <option value="lowest">Lowest Price</option>
                <option value="highest">Highest Price</option>
                <option value="soonest">Ending Soonest</option>
            </select>
        </div>
        <div class="category">
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>

        <!-- will add price display inm the future// for know just a simple range -->
        <div class="entry-price">
            <label for="price">Entry Price:</label>
            <input type="range" id="entry-price-range" name="price" min="0" max="10000" step="100" value="0">
            <span id="entry-price-value">0</span>
        </div>

        <div class="current-bid">
            <label for="price">Current Bid:</label>
            <input type="range" id="current-bid-range" name="price" min="0" max="10000" step="100" value="0">
            <span id="current-bid-value">0</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="apply-filters">Apply Filters</button>
        <a href="#" id="clear-filters" class="clear-filters">Clean filters</a>

    </div>
    <div class="cards-container">

        <div class="search-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <form action="{{ route('search.results', ['query' => request('query')]) }}" method="GET">
                @csrf
                <input type="search" name="query" id="query" class="form-control" placeholder="Search auctions" required>
            </form>
        </div>

        <div class="all-cards">
            @foreach($results as $auction)
            @include('partials.card', ['auction' => $auction])
            @endforeach
        </div>
    </div>


</div>
@endsection