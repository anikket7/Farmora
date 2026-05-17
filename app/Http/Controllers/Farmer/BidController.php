<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BidController extends Controller
{
    public function index(): View
    {
        BidSession::checkAndCloseExpired();

        $bidSessions = BidSession::with(['product', 'bids.consumer'])
            ->whereHas('product', fn ($q) => $q->where('farmer_id', Auth::id()))
            ->latest()
            ->paginate(15);

        return view('farmer.bids.index', compact('bidSessions'));
    }

    public function close(BidSession $bidSession): RedirectResponse
    {
        if ($bidSession->product->farmer_id !== Auth::id()) {
            abort(403);
        }

        if (! $bidSession->isActive()) {
            return back()->with('error', 'This auction is not active.');
        }

        $bidSession->closeSession();

        return back()->with('success', 'Auction closed successfully and order generated for highest bidder!');
    }
}
