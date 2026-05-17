<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['farmer', 'category', 'images']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function approve(Product $product): RedirectResponse
    {
        $product->update(['status' => 'active']);

        if ($product->bidSession) {
            $product->bidSession->update(['status' => 'active']);
        }

        return back()->with('success', 'Product has been approved and is now live!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Product listing deleted.');
    }
}
