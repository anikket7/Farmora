<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\BidSession;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'images', 'bidSession'])
            ->where('farmer_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('farmer.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('farmer.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:kg,gram,dozen,piece,liter',
            'listing_type' => 'required|in:buy,bid',
            'price' => 'required_if:listing_type,buy|nullable|numeric|min:1',
            'start_bid_price' => 'required_if:listing_type,bid|nullable|numeric|min:1',
            'min_increment' => 'required_if:listing_type,bid|nullable|numeric|min:1',
            'bid_end_time' => 'required_if:listing_type,bid|nullable|date|after:now',
            'harvest_date' => 'nullable|date',
            'origin_location' => 'nullable|string|max:255',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpg,png,jpeg,webp|max:20480',
        ]);

        $product = Product::create([
            'farmer_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
            'listing_type' => $request->listing_type,
            'harvest_date' => $request->harvest_date,
            'origin_location' => $request->origin_location ?? Auth::user()->location,
        ]);

        // Handle image uploads with standard sizing (800x600)
        $manager = new ImageManager(new Driver());
        foreach ($request->file('images') as $index => $image) {
            $path = 'products/' . uniqid() . '_' . time() . '.jpg';
            $img = $manager->read($image->getRealPath());
            $img->cover(800, 600, 'center');
            $encoded = $img->toJpeg(85)->toString();
            Storage::disk('public')->put($path, $encoded);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $index === 0,
            ]);
        }

        // Create bid session if applicable
        if ($request->listing_type === 'bid') {
            BidSession::create([
                'product_id' => $product->id,
                'start_price' => $request->start_bid_price,
                'min_increment' => $request->min_increment,
                'start_time' => now(),
                'end_time' => $request->bid_end_time,
                'status' => 'active',
            ]);
        }

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product listed successfully!');
    }

    public function edit(Product $product): View
    {
        $this->authorizeProduct($product);

        $categories = Category::where('is_active', true)->get();
        $product->load(['images', 'bidSession']);

        return view('farmer.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:kg,gram,dozen,piece,liter',
            'price' => 'nullable|numeric|min:1',
            'harvest_date' => 'nullable|date',
            'origin_location' => 'nullable|string|max:255',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $product->update($request->only([
            'title', 'category_id', 'description', 'quantity', 'unit',
            'price', 'harvest_date', 'origin_location',
        ]));

        if ($product->status === 'sold' && $product->quantity > 0) {
            $product->update(['status' => 'active']);
        }

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());
            foreach ($request->file('images') as $image) {
                $path = 'products/' . uniqid() . '_' . time() . '.jpg';
                $img = $manager->read($image->getRealPath());
                $img->cover(800, 600, 'center');
                $encoded = $img->toJpeg(85)->toString();
                Storage::disk('public')->put($path, $encoded);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        if ($request->has('delete_images') && is_array($request->delete_images)) {
            $imagesToDelete = ProductImage::whereIn('id', $request->delete_images)
                                          ->where('product_id', $product->id)
                                          ->get();
            /** @var \App\Models\ProductImage $img */
            foreach ($imagesToDelete as $img) {
                if ($img->image_path !== 'products/placeholder.jpg') {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
        }

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $product->delete();

        return redirect()->route('farmer.products.index')
            ->with('success', 'Product deleted.');
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->farmer_id !== Auth::id()) {
            abort(403);
        }
    }
}
