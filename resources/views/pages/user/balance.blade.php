@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="rectangle-div" id="my-balance">
        <h1>My Balance</h1>
        <h2>You have a balance of {{$user->balance}}€</h2>
        <form action="{{route('user.deposit', $user)}}" method="POST">
            @csrf
            <div id="deposit">
                <input type="number" name="amount" id="amount" placeholder="Amount" required>
                <button type="submit">Deposit</button>
            </div>
        </form>
        <form action="{{route('user.withdraw', $user)}}" method="POST">
            @csrf
            <div id="deposit">
                <input type="number" name="amount" id="amount" placeholder="Amount" required>
                <button id="withdraw" type="submit">Withdraw</button>
            </div>
        </form>
    </div>
    <div class="rectangle-div" id="my-transactions">
        <h1>My Transactions</h1>
        <h2>Buying:</h2>
        @foreach($user->buyerTransactions()->get() as $transaction)
            @php($auction = $transaction->auction)
            <div class="rectangle-div transaction red">
                <span>
                    <a href="{{route('auction.show', $auction)}}">
                    {{$auction->title}}
                    </a>
                </span>
                <span>Seller:
                    <a href="{{route('user.show', $auction->creator()->first())}}">
                    {{$auction->creator()->first()->username}}
                    </a>
                </span>
                <span>{{$transaction->created_at}}</span>
                <span>{{$transaction->amount}}€</span>
                @if(!$transaction->is_payed)
                    <form action="{{route('transaction.pay', $transaction)}}" method="POST">
                        @csrf
                        <button type="submit">Pay</button>
                    </form>
                @endif
            </div>
        @endforeach

        <h2>Selling:</h2>
        @foreach($user->sellerTransactions()->get() as $transaction)
            @php($auction = $transaction->auction)
            <div class="rectangle-div transaction green">
                <span>
                    <a href="{{route('auction.show', $auction)}}">
                    {{$auction->title}}
                    </a>
                </span>
                <span>Buyer:
                    <a href="{{route('user.show', $transaction->buyer()->first())}}">
                    {{$transaction->buyer()->first()->username}}
                    </a>
                </span>
                <span>{{$transaction->created_at}}</span>
                <span>{{$transaction->amount}}€</span>
                @if(!$transaction->is_payed)
                    <span>Not Payed</span>
                @else
                    <span>Payed</span>
                @endif
            </div>
        @endforeach
    </div>

@endsection