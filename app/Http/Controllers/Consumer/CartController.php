<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = [];
        $total = 0;

        if (! empty($cart)) {
            $productModels = Product::with(['primaryImage', 'farmer'])
                ->whereIn('id', array_keys($cart))
                ->get();

            foreach ($productModels as $product) {
                $qty = $cart[$product->id];
                $subtotal = $product->price * $qty;
                $products[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('consumer.cart.index', compact('products', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        // Block out-of-stock buy products
        if ($product->isBuyable() && $product->quantity <= 0) {
            return back()->with('error', "{$product->title} is out of stock.");
        }

        $cart = $request->session()->get('cart', []);
        $qty = (int) $request->input('quantity', 1);

        $existingQty = $cart[$product->id] ?? 0;
        $newTotal = $existingQty + $qty;

        // Cap at available stock for buy products
        if ($product->isBuyable() && $newTotal > $product->quantity) {
            $newTotal = (int) $product->quantity;
        }

        $cart[$product->id] = $newTotal;
        $request->session()->put('cart', $cart);

        return back()->with('success', "{$product->title} added to cart!");
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        $qty = (int) $request->quantity;

        // Cap at available stock for buy products
        if ($product->isBuyable() && $qty > $product->quantity) {
            $qty = (int) $product->quantity;
        }

        $cart[$product->id] = $qty;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Item removed from cart.');
    }
}
