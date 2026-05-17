<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? '🌿',
            'color' => $request->color ?? '#10b981',
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? $category->icon,
            'color' => $request->color ?? $category->color,
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'Category status updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
