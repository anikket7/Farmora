<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_consumers' => User::where('role', 'consumer')->count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'active_listings' => Product::where('status', 'active')->count(),
            'active_bids' => BidSession::where('status', 'active')->count(),
            'total_orders' => Order::count(),
        ];

        $recentUsers = User::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $activeBids = BidSession::with(['product.farmer', 'bids'])
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'activeBids'));
    }
}
