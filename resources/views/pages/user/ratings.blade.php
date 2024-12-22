@extends('layouts.app')

@section('title', $user->username . '\'s Ratings')

@section('content')
    <div class="ratings-page">
        <div class="ratings-header">
            <h1>Ratings</h1>
            <button class="ratings-back-button" onclick="window.history.back()"><span>Back</span></button>
        </div>
        <h2 class="ratings-count"><strong>Number of Ratings:</strong> {{ $user->ratingsReceived()->count() }}</h2>
        <ul class="ratings-list">
            @php
                $ratings = $user->ratingsReceived()->with('auction', 'rater')->get();
            @endphp
            @foreach ($ratings as $rating)
                <li class="ratings-item">
                    <div class="ratings-info">
                        <span class="ratings-username">{{ $rating->rater->getUsername() }}</span>
                        <span class="ratings-score">Score: {{ $rating->score }}/5</span>
                    </div>
                    <div class="ratings-comment">{{ $rating->comment }}</div>
                    <div class="ratings-details">
                        <span class="ratings-date">Rated on: {{ $rating->created_at->format('Y-m-d H:i') }}</span>
                        <span class="ratings-auction">
                            <strong>Auction:</strong> 
                            <a href="{{ route('auction.show', $rating->auction) }}" class="ratings-auction-link">
                                {{ $rating->auction->title }}
                            </a>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
