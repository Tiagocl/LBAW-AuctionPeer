<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

use App\Events\AuctionFollowed;
use App\Events\AuctionCanceled;
use App\Events\AuctionEnded;
use App\Events\AuctionEdited;


use Pusher\Pusher;
use App\Models\Rating;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        return view('pages.auction.index', compact('auctions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Auction::class);
        $categories = Category::all();
        return view('pages.auction.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::debug('Creating auction');
            $this->authorize('create', Auction::class);
            Log::debug($request);
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'minimum_bid' => 'required|numeric|min:0',
                'end_date' => 'required|date|after:now',
                'category_id' => 'required|integer',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            Log::debug('Validation passed');

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filePath = $file->store('auction-pictures', 'public'); // Store the file in the 'profile_pictures' directory in the 'public' disk
                $validated['picture'] = $filePath;
            } else {
                $validated['picture'] = 'auction-pictures/placeholder.png';
            }

            Auction::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'minimum_bid' => $validated['minimum_bid'],
                'current_bid' => $validated['minimum_bid'],
                'end_date' => $validated['end_date'],
                'user_id' => Auth::id(), // Owner of the auction
                'start_date' => now(),
                'status' => 'active',
                'category_id' => $validated['category_id'],
                'creator_id' => Auth::id(),
                'picture' => $validated['picture'],
            ]);

            return redirect()->route('auctions.index')->with('success', 'Auction created successfully.');
        } catch (\Exception $exception) {
            // Handle specific PostgreSQL error codes or messages
            if (str_contains($exception->getMessage(), 'The auction end date must be at')) {
                return redirect()->route('auctions.create')->with('error', 'End date must be at least one day greater than start date.');
            }

            // For other database errors
            // Log the error for further investigation
            Log::error('An error occurred while creating the auction: ' . $exception->getMessage());
            return redirect()->route('auctions.create')->with('error', 'An error occurred while creating the auction: ' . $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        $this->authorize('view', $auction);
        $user = Auth::user();

        return view('pages.auction.show', compact('auction', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        $this->authorize('update', $auction);
        $categories = Category::all();

        // Notify the auction owner that the auction has been edited
        event(new AuctionEdited($auction, $auction->creator));

        // Notify other bidders that the auction has been edited
        $bidders = $auction->bids()->get();

        foreach ($bidders as $bidder) {
            event(new AuctionEdited($auction, $bidder));
        }

        // Notify followers that the auction has been edited
        $followers = $auction->followers()->get();
        foreach ($followers as $follower) {
            event(new AuctionEdited($auction, $follower));
        }

        return view('pages.auction.edit', compact('auction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        $this->authorize('update', $auction);

        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'end_date' => 'required|date|after:now',
            'category_id' => 'required|integer|exists:category,id',
        ]);

        // Update the auction
        $auction->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'end_date' => $validated['end_date'],
            'category_id' => $validated['category_id'],
        ]);

        $user = Auth::user();
        return redirect()->route('auction.show', $auction)->with('success', 'Auction updated successfully.');
    }

    /**
     * Cancel an auction
     */
    public function cancel(Auction $auction)
    {
        $this->authorize('cancel', $auction);

        $auction->update([
            'status' => 'canceled',
        ]);

        // Notify all bidders that the auction has been canceled
        $bidders = $auction->bids()->get();
        foreach ($bidders as $bidder) {
            event(new AuctionCanceled($auction, $bidder->user));
        }

        return redirect()->route('auctions.index')->with('success', 'Auction cancelled successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->back()->with('error', 'Search query cannot be empty.');
        }

        // Call the search function in the Auction model
        $results = Auction::search($query)->all();

        $categories = Category::all();

        return view('pages.auction.search', compact('results', 'query', 'categories'));
    }

    public function myAuctions()
    {
        // Fetch auctions belonging to the authenticated user
        $auctions = Auction::where('user_id', auth()->id())->get();

        return view('pages.auction.my_auctions', compact('auctions'));
    }

    public function biddingHistory(Auction $auction)
    {
        // Retrieve bids for this auction, ordered by the bid amount or created_at
        $bids = $auction->bids()->orderBy('created_at', 'desc')->get();

        return view('pages.auction.bidding_history', compact('auction', 'bids'));
    }

    public function apiIndex()
    {
        $auctions = Auction::select('auction.title', 'auction.description', 'auction.start_date', 'auction.end_date', 'auction.status', 'auction.minimum_bid', 'auction.current_bid', 'category.name as category_name', 'users.username as user_name')
            ->join('category', 'auction.category_id', '=', 'category.id')
            ->join('users', 'auction.creator_id', '=', 'users.id')
            ->get();

        return response()->json($auctions);
    }

    public function apiShow(Auction $auction)
    {
        $auction = Auction::select('auction.title', 'auction.description', 'auction.start_date', 'auction.end_date', 'auction.status', 'auction.minimum_bid', 'auction.current_bid', 'category.name as category_name', 'users.username as user_name')
            ->join('category', 'auction.category_id', '=', 'category.id')
            ->join('users', 'auction.creator_id', '=', 'users.id')
            ->where('auction.id', $auction->id)
            ->get();

        return response()->json($auction);
    }

    public function filter(Request $request)
    {
    // Sanitize inputs and set defaults for the filters
    $sortBy = $request->input('sort_by'); // Default to 'lowest'
    $categoryId = $request->input('category_id'); // Category ID from the request
    $minPrice = $request->input('min_price'); // Default min price
    $maxPrice = $request->input('max_price'); // Default max price

    try {
        $auctions = Auction::query(); // Start with the auction query

        // Apply category filter if category_id is provided
        if ($categoryId) {
            $auctions->where('category_id', $categoryId);
        }

        // Apply price filters
        if ($minPrice) {
            $auctions->where('current_bid', '>=', $minPrice);
        }
        if ($maxPrice) {
            $auctions->where('current_bid', '<=', $maxPrice);
        }

        // Apply sorting
        if ($sortBy === 'highest') {
            $auctions->orderBy('current_bid', 'desc');
        } elseif ($sortBy === 'lowest') {
            $auctions->orderBy('current_bid', 'asc');
        } elseif ($sortBy === 'soonest') {
            $auctions->orderBy('end_date', 'asc');
        }

        $auctions = $auctions->get(); // Get the filtered auctions

        return response()->json([
            'status' => 'success',
            'auctions' => $auctions
        ]);
    } catch (\Exception $e) {
        // Return error response in case of failure
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
    }

    public function adminIndex()
    {
        $this->authorize('viewAdmin', Auction::class);
        $auctions = Auction::all();
        return view('pages.admin.auction_index', compact('auctions'));
    }

    public function report(Auction $auction)
    {
        $this->authorize('report', $auction);
        return view('pages.auction.report', compact('auction'));
    }

    public function follow(Auction $auction)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to follow an auction.');
        }
        if (!$auction->isFollowedBy($user)) {
            $auction->followers()->attach($user->id);

            // Trigger the event
            event(new AuctionFollowed($user, $auction->creator, $auction));
        }

        return redirect()->back()->with('success', 'Auction followed successfully');
    }

    public function unfollow(Auction $auction)
    {
        $user = auth()->user();
        if ($auction->isFollowedBy($user)) {
            $auction->followers()->detach($user->id);
        }

        return redirect()->back()->with('success', 'Auction unfollowed successfully');
    }

    public function withdrawFunds(Auction $auction) { 
        $user = User::find(auth()->id());// Get the logged-in user (auction owner)
        $highestBid = $auction->highestBid()->first(); // Get the highest bid
        $endDate = $auction->end_date;

        Log::info('End Date:' . $endDate);
        // Check if the auction owner is the logged-in user
        if ($auction->creator_id === $user->id) {
            // Update the balance of the auction owner
            $user->update([
                'balance' => $user->balance + $highestBid->amount
            ]);
        
            return redirect()->back()->with('success', 'Funds withdrawn successfully and balance updated.');
        } else {
            return redirect()->back()->with('error', 'You cannot withdraw funds from this auction.');
        }
        
    }

    public function rateBuyer(Request $request, $auctionId)
    {
        // Fetch the auction by its ID
        $auction = Auction::findOrFail($auctionId);
        Log::info('Auction found: ' . $auction);
        // Ensure the auction has ended (closed auction)
       $user = User::find(auth()->id());
    
        // Ensure the authenticated user is the seller (creator of the auction)
        if ($auction->creator_id !== $user) {
            return redirect()->route('auction.show', $auctionId)
                ->withErrors('You can only rate the buyer if you are the seller.');
        }
    
        // Validate the rating input
        $request->validate([
            'score' => 'required|integer|min:0|max:5',  // Rating score should be between 1 and 5
            'comment' => 'nullable|string|max:500',    // Optional comment with a maximum length of 500 characters
        ]);
    
        // Create the rating for the buyer
        Rating::create([
            'score' => $request->input('score'),
            'comment' => $request->input('comment'),
            'auction_id' => $auctionId,
            'rater_id' => $user,  // The seller is the rater
            'receiver_id' => $auction->buyer_id,  // The buyer is being rated
        ]);
    
        // Redirect to the auction page with a success message
        return redirect()->route('auction.show', $auctionId)
            ->with('success', 'Buyer rated successfully.');
    }

    public function rateSeller(Request $request, $auctionId)
    {
        // Fetch the auction by its ID
        $auction = Auction::findOrFail($auctionId);
    
        // Fetch the currently authenticated user
        $user = User::find(auth()->id());
    
    
        // Ensure the authenticated user is the buyer (highest bidder)
        $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();
        if (!$highestBid || $highestBid->user_id !== $user->id) {
            return redirect()->route('auction.show', $auctionId)
                ->withErrors('You can only rate the seller if you were the highest bidder.');
        }
    
        // Validate the rating input
        $request->validate([
            'score' => 'required|integer|min:0|max:5',  // Rating score should be between 0 and 5
            'comment' => 'nullable|string|max:500',    // Optional comment with a maximum length of 500 characters
        ]);
    
        // Create the rating for the seller
        Rating::create([
            'score' => $request->input('score'),
            'comment' => $request->input('comment'),
            'auction_id' => $auctionId,
            'rater_id' => $user->id,  // The buyer is the rater
            'receiver_id' => $auction->creator_id,  // The seller is being rated
        ]);
    
        // Redirect to the auction page with a success message
        return redirect()->route('auction.show', $auctionId)
            ->with('success', 'Seller rated successfully.');
    }
}
