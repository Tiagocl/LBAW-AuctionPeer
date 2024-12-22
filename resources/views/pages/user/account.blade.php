@extends('layouts.app')


@section('content')


<section class="max-w-full mx-auto mt-8 p-6 rounded-lg shadow-lg flex space-x-8">
    <!-- profile info -->
    <div class="flex-none w-48">
        <div class="bg-gray-400 w-32 h-32 rounded-full mx-auto mb-4" style="background-image: url('{{ asset('storage/' . $user->profile_picture) }}'); background-size: cover;"></div>
        <div class="text-center">
            <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
            <a href="{{ route('user.ratings', $user) }}">
            <div class="flex justify-center items-center mt-4">
                @php
                    $rating = round($user->ratingsReceived->avg('rating') ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-500' }} text-3xl">&#9733;</span>
                @endfor
            </div>
            </a>
            <p class="">Joined in: {{ $user->created_at->format('d.m.Y') }}</p>
            <a href="{{ route('user.edit', $user) }}" id="user-edit"> Edit Profile </a>
            <form action="{{route('user.destroy', $user)}}" method="POST" id="user-delete">
                @csrf
                <button type="submit" id="delete-account-button"><p>Delete User</p></button>
            </form>
            <a href="{{ route('logout') }}" class="mt-4 inline-block">Log Out</a>
        </div>
    </div>

    <!-- auction sections -->
    <div class="flex-1 space-y-8">

        <button onClick="window.location='{{ route('auctions.create') }}'">Create New Auction</button>

        <!-- auction lists -->
        <div class="grid grid-cols-3 gap-4">

            <!-- active auctions -->
            <div class="bg-gray-100 p-4 rounded-lg shadow user-auction-lists">
                <h3 class="text-white font-semibold mb-4">My Active Auctions</h3>
                <ul class="space-y-4">
                    @if ($user->auctionsCreated->isEmpty())
                        <p class="text-white"> This user has not created any auctions at the moment. </p>
                    @else
                        @foreach ($user->paginatedAuctionsCreated(3) as $auction)
                        <a href="{{route('auction.show', $auction)}}">
                        <li class="flex space-x-3">
                            <div class="w-40 h-40 bg-gray-300 rounded" style="background-image: url('{{ asset('storage/' . $auction->picture) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-4xl font-semibold">{{ $auction->title }}</h4>
                                <p class="text-xl text-white">Starting price: ${{ $auction->minimum_bid }}</p>
                                <p class="text-xl text-white">Ends: {{ $auction->end_date->format('d.m.Y') }}</p>
                            </div>
                        </li>
                        </a>
                        @endforeach
                        <a href="{{ route('user.auctions', $user) }}" > See all {{ $user->auctionsCreated->count() }} </a>
                    @endif
                </ul>
            </div>

            <!-- won auctions -->
            <div class="bg-gray-100 p-4 rounded-lg shadow user-auction-lists">
                <h3 class="text-white font-semibold mb-4">My Won Auctions</h3>
                <ul class="space-y-4">
                    @if ($user->auctionsBought->isEmpty())
                        <p class="text-white"> This user has not won any auctions at the moment. </p>
                    @else
                        @foreach ($user->paginatedAuctionsBought(3) as $auction)
                        <a href="{{route('auction.show', $auction)}}">
                        <li class="flex space-x-3">
                            <div class="w-40 h-40 bg-gray-300 rounded" style="background-image: url('{{ asset('storage/' . $auction->picture) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-4xl font-semibold">{{ $auction->title }}</h4>
                                <p class="text-xl text-white">Final price: ${{ $auction->current_bid }}</p>
                                <p class="text-xl text-white">Purchased on: {{ $auction->end_date->format('d.m.Y') }}</p>
                            </div>
                        </li>
                        </a>
                        @endforeach
                        <a href="{{ route('user.wonAuctions', $user) }}" > See all {{ $user->auctionsBought->count() }} </a>
                    @endif
                </ul>
            </div>

            <!-- my auction offers -->
            <div class="p-4 rounded-lg shadow user-auction-lists">
                <h3 class="text-white font-semibold mb-4">My Auction Offers</h3>
                <ul class="space-y-4">
                    @if ($user->bids->isEmpty())
                        <p class="text-white"> This user has no placed bids at the moment. </p>
                    @else
                        @foreach ($user->paginatedBids(3) as $bid)
                        <a href="{{route('auction.show', $bid->auction)}}">
                        <li class="flex space-x-3">
                            <div class="w-40 h-40 bg-gray-300 rounded" style="background-image: url('{{ asset('storage/' . $bid->auction->picture) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-4xl font-semibold">{{ $bid->auction->title }}</h4>
                                <p class="text-xl text-white">Your highest offer: ${{ $bid->amount }}</p>
                                <p class="text-xl text-white">Current highest bid: ${{ $bid->auction->current_highest_bid }}</p>
                            </div>
                        </li>
                        </a>
                        @endforeach
                        <a href="{{ route('user.bids', $user) }}" > See all {{ $user->bids->count() }} </a>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>



@endsection
