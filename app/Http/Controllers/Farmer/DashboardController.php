<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        BidSession::checkAndCloseExpired();

        $farmerId = Auth::id();

        $stats = [
            'total_products' => Product::where('farmer_id', $farmerId)->count(),
            'active_listings' => Product::where('farmer_id', $farmerId)->where('status', 'active')->count(),
            'active_bids' => BidSession::whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))
                ->where('status', 'active')->count(),
            'total_orders' => Order::whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))->count(),
            'pending_orders' => Order::whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))
                ->where('status', 'pending')->count(),
            'total_revenue' => Order::whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))
                ->where('status', 'delivered')->sum('total_price'),
        ];

        $recentOrders = Order::with(['product', 'consumer'])
            ->whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))
            ->latest()
            ->take(5)
            ->get();

        $activeBidSessions = BidSession::with(['product', 'bids'])
            ->whereHas('product', fn ($q) => $q->where('farmer_id', $farmerId))
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        $recentProducts = Product::with(['primaryImage', 'category'])
            ->where('farmer_id', $farmerId)
            ->latest()
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact('stats', 'recentOrders', 'activeBidSessions', 'recentProducts'));
    }
}
