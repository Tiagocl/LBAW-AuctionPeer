@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->username }}'s Created Auctions</h1>

    @if($auctions->isEmpty())
        <p>No auctions found.</p>
    @else
        <div class="grid grid-cols-3 gap-4">
            @foreach ($auctions as $auction)
                <a href="{{ route('auction.show', $auction) }}">
                <div class="bg-gray-100 p-4 rounded shadow user-auction-lists">
                    <div class="w-full h-48 bg-gray-300 rounded" style="background-image: url('{{ asset('storage/' . $auction->picture) }}'); background-size: cover;"></div>
                    <h3 class="mt-2 text-3xl font-semibold">{{ $auction->title }}</h3>
                    <p class="text-xl text-white">Starting price: ${{ $auction->minimum_bid }}</p>
                    <p class="text-xl text-white">Ends: {{ $auction->end_date->format('d.m.Y') }}</p>
                </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $auctions->links() }} 
        </div>
    @endif
</div>
@endsection
