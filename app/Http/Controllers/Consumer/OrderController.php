<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['product.primaryImage', 'product.farmer'])
            ->where('consumer_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('consumer.orders.index', compact('orders'));
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::with(['primaryImage', 'farmer'])->whereIn('id', array_keys($cart))->get();
        $items = [];
        $total = 0;

        foreach ($products as $product) {
            $qty = $cart[$product->id];
            $subtotal = $product->price * $qty;
            $items[] = ['product' => $product, 'quantity' => $qty, 'subtotal' => $subtotal];
            $total += $subtotal;
        }

        $user = Auth::user();

        return view('consumer.checkout', compact('items', 'total', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'contact_phone' => 'required|digits_between:10,15',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        foreach ($products as $product) {
            $qty = $cart[$product->id];

            // Only deduct stock for buy products
            if ($product->isBuyable()) {
                if ($product->quantity < $qty) {
                    return back()->with('error', "Sorry, only {$product->quantity} {$product->unit} of \"{$product->title}\" is available.");
                }
            }

            Order::create([
                'product_id' => $product->id,
                'consumer_id' => Auth::id(),
                'quantity' => $qty,
                'total_price' => $product->price * $qty,
                'delivery_address' => $request->delivery_address,
                'contact_phone' => $request->contact_phone,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            // Deduct stock for buy products
            if ($product->isBuyable()) {
                $product->decrement('quantity', $qty);

                // Mark product as sold/inactive when out of stock
                if ($product->fresh()->quantity <= 0) {
                    $product->update(['quantity' => 0, 'status' => 'sold']);
                }
            }
        }

        $request->session()->forget('cart');

        return redirect()->route('consumer.orders')
            ->with('success', 'Order placed successfully! You will receive a confirmation soon.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        if ($order->consumer_id !== Auth::id()) {
            abort(403);
        }

        $allowedStatuses = ['pending', 'confirmed', 'packed'];
        if (!in_array($order->status, $allowedStatuses)) {
            return back()->with('error', 'This order cannot be cancelled as it has already been ' . ($order->status === 'cancelled' ? 'cancelled.' : 'shipped.'));
        }

        // Restore stock if it's direct buy
        $product = $order->product;
        if ($product && $product->isBuyable()) {
            $product->increment('quantity', $order->quantity);
            if ($product->status === 'sold' && $product->quantity > 0) {
                $product->update(['status' => 'active']);
            }
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order cancelled successfully and stock restored.');
    }
}
