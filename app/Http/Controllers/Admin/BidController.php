<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BidController extends Controller
{
    public function index(Request $request): View
    {
        $query = BidSession::with(['product.farmer', 'bids.consumer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bidSessions = $query->latest()->paginate(15);

        return view('admin.bids.index', compact('bidSessions'));
    }

    public function forceClose(BidSession $bidSession): RedirectResponse
    {
        $bidSession->closeSession();

        return back()->with('success', 'Bid session closed and order created for highest bidder.');
    }

    public function cancel(BidSession $bidSession): RedirectResponse
    {
        $bidSession->cancelSession();

        return back()->with('success', 'Bid session has been cancelled and product reverted to active.');
    }
}
