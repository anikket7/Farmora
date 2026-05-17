@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'All Product Listings')
@section('content')
<div class="flex flex-col gap-6">
    {{-- Filters --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                 <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Search</label>
                 <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[18px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full pl-10 pr-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50" placeholder="Search products...">
                </div>
            </div>
            <div class="w-full sm:w-auto">
                 <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Status</label>
                <select name="status" class="w-full sm:w-40 bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors appearance-none">
                    <option value="" class="bg-surface">All Status</option>
                    <option value="active" class="bg-surface" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" class="bg-surface" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sold" class="bg-surface" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="inactive" class="bg-surface" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                <button type="submit" class="flex-1 sm:flex-none bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)]">Filter</button>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/10 bg-surface/40">
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Product</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Farmer</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Category</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Type</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Price</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Status</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($products as $product)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 group-hover:border-secondary-fixed/50 transition-colors" alt="">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-surface-container-high border border-white/10 flex items-center justify-center text-xl group-hover:border-secondary-fixed/50 transition-colors">🌾</div>
                                @endif
                                <a href="{{ route('marketplace.show', $product) }}" target="_blank" class="font-body-md text-base font-medium text-white group-hover:text-secondary-fixed transition-colors hover:underline">{{ Str::limit($product->title, 30) }}</a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.show', $product->farmer) }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">person</span> {{ $product->farmer->name }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-surface-container-high border border-white/10 px-3 py-1.5 rounded-full font-label-caps text-[10px] text-white tracking-widest uppercase inline-flex items-center gap-1">
                                <span class="text-[12px]">{{ $product->category->icon }}</span> {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-label-caps text-[10px] tracking-widest uppercase px-3 py-1.5 rounded-full border {{ $product->listing_type == 'bid' ? 'text-tertiary bg-tertiary/10 border-tertiary/30' : 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30' }}">
                                {{ ucfirst($product->listing_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->price)
                                <span class="font-price-display text-lg font-semibold text-secondary-fixed">₹{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="font-label-caps text-[10px] tracking-widest uppercase text-on-surface-variant">Bid Only</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                             @php
                                $statusColors = [
                                    'active' => 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30',
                                    'sold' => 'text-on-surface-variant bg-surface-container-high border-white/10',
                                    'inactive' => 'text-error bg-error/10 border-error/30',
                                    'pending' => 'text-warning bg-warning/10 border-warning/30'
                                ];
                                $colors = $statusColors[$product->status] ?? $statusColors['inactive'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase border {{ $colors }} inline-flex items-center gap-1 shadow-[0_0_10px_currentColor]">
                                @if($product->status === 'active') <span class="w-1.5 h-1.5 rounded-full bg-primary-fixed"></span> @endif
                                @if($product->status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-warning"></span> @endif
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($product->status === 'pending')
                                    <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1 shadow-[0_0_10px_rgba(6,205,172,0.2)]" title="Approve">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span> Approve
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block" onsubmit="return confirm('Delete this product? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1" title="Delete">
                                        <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                             <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-4 opacity-50">inventory_2</span>
                                <p class="font-body-md">No products found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
