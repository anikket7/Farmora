<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['farmer:id,name,location', 'category:id,name,slug,icon', 'primaryImage'])
            ->where('status', 'active')
            ->where('is_available', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully.',
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['farmer:id,name,location', 'category', 'images', 'bidSession.bids']);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product details retrieved.',
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved.',
        ]);
    }

    public function bidStatus(BidSession $bidSession): JsonResponse
    {
        $bidSession->load('bids.consumer:id,name');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $bidSession->id,
                'current_price' => $bidSession->currentPrice(),
                'min_increment' => $bidSession->min_increment,
                'total_bids' => $bidSession->bids->count(),
                'end_time' => $bidSession->end_time,
                'is_active' => $bidSession->isActive(),
                'highest_bidder' => $bidSession->highestBid()?->consumer?->name,
                'bids' => $bidSession->bids->map(fn ($bid) => [
                    'amount' => $bid->amount,
                    'bidder' => $bid->consumer->name,
                    'placed_at' => $bid->placed_at->diffForHumans(),
                ]),
            ],
            'message' => 'Bid status retrieved.',
        ]);
    }
}
