<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        \App\Models\BidSession::checkAndCloseExpired();

        $query = Product::with(['farmer', 'category', 'primaryImage', 'bidSession.bids.consumer'])
            ->leftJoin('bid_sessions', 'bid_sessions.product_id', '=', 'products.id')
            ->select('products.*')
            ->whereIn('products.status', ['active', 'sold'])
            ->where('products.is_available', true);

        $isAuctionFilter = $request->filled('listing_type') && $request->listing_type === 'bid';

        if ($isAuctionFilter) {
            $query->where('products.listing_type', 'bid');
        }

        if ($request->filled('category')) {
            $query->where('products.category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('products.price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('products.price', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.title', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%")
                    ->orWhereHas('farmer', fn ($fq) => $fq->where('name', 'like', "%{$search}%"));
            });
        }

        // Primary sort: Active first, Sold out, Sold, Cancelled & Completed/Expired at the bottom
        $query->orderByRaw("CASE WHEN (products.listing_type = 'buy' AND products.quantity <= 0) OR (products.status = 'sold') OR (products.listing_type = 'bid' AND (bid_sessions.status = 'cancelled' OR bid_sessions.status = 'completed' OR bid_sessions.end_time <= NOW())) THEN 1 ELSE 0 END ASC");

        // Secondary sort: Selected sorting criteria
        $sortBy = $request->input('sort', 'newest');
        if ($sortBy === 'price_low') {
            $query->orderByRaw("CASE WHEN products.listing_type = 'buy' THEN products.price ELSE COALESCE((SELECT MAX(amount) FROM bids WHERE bids.bid_session_id = bid_sessions.id), bid_sessions.start_price) END asc");
        } elseif ($sortBy === 'price_high') {
            $query->orderByRaw("CASE WHEN products.listing_type = 'buy' THEN products.price ELSE COALESCE((SELECT MAX(amount) FROM bids WHERE bids.bid_session_id = bid_sessions.id), bid_sessions.start_price) END desc");
        } else {
            $query->orderBy('products.created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('marketplace.index', compact('products', 'categories', 'isAuctionFilter'));
    }

    public function show(Product $product): View
    {
        if ($product->bidSession) {
            if ($product->bidSession->status === 'active' && $product->bidSession->end_time->isPast()) {
                $product->bidSession->closeSession();
                $product = $product->fresh();
            }
        }

        $product->increment('views_count');
        $product->load(['farmer.farmerProfile', 'category', 'images', 'bidSession.bids.consumer']);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with(['primaryImage', 'farmer'])
            ->take(4)
            ->get();

        return view('marketplace.show', compact('product', 'relatedProducts'));
    }
}
