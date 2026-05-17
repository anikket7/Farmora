<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\BidSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BidController extends Controller
{
    public function index(): View
    {
        $consumerId = Auth::id();

        $bidSessions = BidSession::with([
            'product.farmer',
            'bids.consumer',
        ])
            ->whereHas('bids', function ($q) use ($consumerId) {
                $q->where('consumer_id', $consumerId);
            })
            ->select('bid_sessions.*')
            ->selectSub(
                Bid::select('placed_at')
                    ->whereColumn('bid_session_id', 'bid_sessions.id')
                    ->where('consumer_id', $consumerId)
                    ->latest('placed_at')
                    ->limit(1),
                'latest_consumer_bid_at'
            )
            ->orderByDesc('latest_consumer_bid_at')
            ->paginate(15);

        return view('consumer.bids.index', compact('bidSessions'));
    }

    public function place(Request $request, BidSession $bidSession): RedirectResponse
    {
        if (! $bidSession->isActive()) {
            return back()->with('error', 'This auction has already ended.');
        }

        $minimumBid = $bidSession->currentPrice() + (float) $bidSession->min_increment;

        $request->validate([
            'amount' => "required|numeric|min:{$minimumBid}",
        ], [
            'amount.min' => "Your bid must be at least ₹" . number_format($minimumBid, 2) . ".",
        ]);

        Bid::create([
            'bid_session_id' => $bidSession->id,
            'consumer_id' => Auth::id(),
            'amount' => $request->amount,
            'placed_at' => now(),
        ]);

        return back()->with('success', 'Bid placed successfully! You are now the highest bidder.');
    }
}
