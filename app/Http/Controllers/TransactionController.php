<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function pay(Transaction $transaction)
    {
        $auction = Auction::findOrFail($transaction->auction_id);
        $buyer = User::findOrFail($auction->buyer_id);
        $seller = User::findOrFail($auction->creator_id);

        if ($buyer->balance < $transaction->amount) {
            return redirect()->back()->with('error', 'Insufficient funds.');
        }

        if ($transaction->is_payed) {
            return redirect()->back()->with('error', 'Transaction has already been payed.');
        }

        $buyer->balance -= $transaction->amount;
        $seller->balance += $transaction->amount;
        $transaction->is_payed = true;

        $buyer->save();
        $seller->save();
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction payed successfully.');
    }
}
