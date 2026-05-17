<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['product', 'consumer'])
            ->whereHas('product', fn ($q) => $q->where('farmer_id', Auth::id()))
            ->latest()
            ->paginate(15);

        return view('farmer.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:confirmed,packed,shipped,out_for_delivery,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $product = $order->product;
            if ($product && $product->isBuyable()) {
                $product->increment('quantity', $order->quantity);
                
                // If it was sold and now has stock, make it active again
                if ($product->status === 'sold' && $product->quantity > 0) {
                    $product->update(['status' => 'active']);
                }
            }
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            $product = $order->product;
            if ($product && $product->isBuyable()) {
                if ($product->quantity < $order->quantity) {
                    return back()->with('error', "Cannot restore order because only {$product->quantity} {$product->unit} of product is left in stock.");
                }
                $product->decrement('quantity', $order->quantity);
                if ($product->fresh()->quantity <= 0) {
                    $product->update(['quantity' => 0, 'status' => 'sold']);
                }
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }
}
