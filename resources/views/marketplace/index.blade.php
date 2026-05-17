@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<!-- Main Content Area -->
<div class="px-container-margin py-10 w-full flex flex-col md:flex-row gap-8 relative z-10">
    <!-- Sidebar Filters -->
    <aside class="w-full md:w-64 shrink-0 flex flex-col gap-8 sticky top-28 self-start h-fit">
        <form method="GET" action="{{ route('marketplace') }}" id="marketplace-form" class="bg-surface-container/30 backdrop-blur-2xl border border-white/5 rounded-DEFAULT p-card-padding">
            <h2 class="font-headline-md text-headline-md text-secondary-fixed mb-6">Filters</h2>

            <!-- Categories -->
            <div class="mb-8">
                <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-4">CATEGORIES</h3>
                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="w-5 h-5 rounded-full border {{ !request('category') ? 'border-secondary-fixed' : 'border-outline group-hover:border-secondary-fixed' }} flex items-center justify-center transition-colors relative">
                            <input type="radio" name="category" value="" onchange="this.form.submit()" class="absolute opacity-0" {{ !request('category') ? 'checked' : '' }}>
                            <div class="w-3 h-3 bg-secondary-fixed rounded-full {{ !request('category') ? '' : 'hidden' }}"></div>
                        </div>
                        <span class="{{ !request('category') ? 'text-secondary-fixed' : 'text-on-surface group-hover:text-secondary-fixed' }} transition-colors">All Categories</span>
                    </label>

                    @foreach($categories as $cat)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="w-5 h-5 rounded-full border {{ request('category') == $cat->id ? 'border-secondary-fixed' : 'border-outline group-hover:border-secondary-fixed' }} flex items-center justify-center transition-colors relative">
                                <input type="radio" name="category" value="{{ $cat->id }}" onchange="this.form.submit()" class="absolute opacity-0" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                <div class="w-3 h-3 bg-secondary-fixed rounded-full {{ request('category') == $cat->id ? '' : 'hidden' }}"></div>
                            </div>
                            <span class="{{ request('category') == $cat->id ? 'text-secondary-fixed' : 'text-on-surface group-hover:text-secondary-fixed' }} transition-colors">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>


            
            <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
        </form>
    </aside>

    <!-- Product Grid Area -->
    <div class="grow flex flex-col gap-6">
        <!-- Top Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <p class="text-on-surface-variant">Showing <span class="text-secondary-fixed font-bold">{{ $products->total() }}</span> results</p>
            
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="grow sm:grow-0 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                    <input type="text" form="marketplace-form" name="search" value="{{ request('search') }}" placeholder="Search produce..." class="bg-surface-container/50 border border-white/10 rounded-full pl-10 pr-4 py-2 w-full font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant" onchange="this.form.submit()">
                </div>
                
                <div class="flex items-center gap-3 shrink-0">
                    <span class="font-label-caps text-label-caps text-on-surface-variant hidden sm:inline-block">SORT:</span>
                    <div class="relative">
                        <select form="marketplace-form" name="sort" onchange="this.form.submit()" class="bg-surface-container/50 border border-white/10 rounded-full pl-4 pr-10 py-2 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed appearance-none cursor-pointer hover:bg-surface-container/80 transition-colors">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price Low-High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price High-Low</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant text-sm">expand_more</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($products as $product)
                @php
                    $session = $product->bidSession;
                    $isLive = $session && $session->isActive();
                    $isEnded = $session && $session->isEnded();
                    $winner = ($isEnded && $session->status !== 'cancelled') ? $session->highestBid() : null;
                    $isBuyOnly = $product->listing_type === 'buy';
                    $isOutOfStock = $isBuyOnly && $product->quantity <= 0;
                @endphp
                <a href="{{ route('marketplace.show', $product) }}" class="bg-surface-container/30 backdrop-blur-xl border {{ $isLive ? 'border-secondary-fixed/40 shadow-[0_0_25px_rgba(203,163,88,0.08)]' : (($session && $session->status === 'cancelled') ? 'border-red-500/30 shadow-[0_8px_32px_rgba(0,0,0,0.37)]' : (($isEnded && $winner) ? 'border-green-500/20 shadow-[0_8px_32px_rgba(0,0,0,0.37)]' : ($isOutOfStock ? 'border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]' : 'border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]'))) }} rounded-DEFAULT overflow-hidden group hover:border-secondary-fixed hover:-translate-y-1 transition-all duration-300 flex flex-col relative hover:shadow-[0_0_30px_rgba(203,163,88,0.15)]">
                    <div class="absolute inset-0 {{ $isLive ? 'bg-secondary-fixed/5' : 'bg-transparent' }} pointer-events-none group-hover:bg-secondary-fixed/10 transition-colors"></div>
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        @if($product->primaryImage && $product->primaryImage->image_path !== 'products/placeholder.jpg')
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out {{ ($isEnded && $winner) || ($session && $session->status === 'cancelled') || $isOutOfStock ? 'grayscale-50 brightness-75' : '' }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl bg-surface-container-high/50 group-hover:scale-105 transition-transform duration-700 ease-out {{ ($isEnded && $winner) || ($session && $session->status === 'cancelled') || $isOutOfStock ? 'grayscale-50 brightness-75' : '' }}">{{ $product->category->icon ?? '🌾' }}</div>
                        @endif
                        <div class="absolute inset-0 bg-linear-to-t from-surface-container/90 via-surface-container/20 to-transparent"></div>

                        {{-- SOLD overlay --}}
                        @if($isEnded && $winner)
                            <div class="absolute inset-0 bg-black/40 pointer-events-none"></div>
                        @endif

                        {{-- CANCELLED overlay --}}
                        @if($session && $session->status === 'cancelled')
                            <div class="absolute inset-0 bg-red-950/40 pointer-events-none"></div>
                        @endif

                        {{-- Status Badge (Top-Left) --}}
                        @if($isOutOfStock)
                            <div class="absolute top-4 left-4 bg-red-500 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_16px_rgba(239,68,68,0.6)] font-bold">
                                <span class="material-symbols-outlined text-[14px]">inventory</span> OUT OF STOCK
                            </div>
                        @elseif($session && $session->status === 'cancelled')
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_16px_rgba(220,38,38,0.7)] font-bold ring-2 ring-red-400/40">
                                <span class="material-symbols-outlined text-[14px]">cancel</span> CANCELLED
                            </div>
                        @elseif($isEnded && $winner)
                            <div class="absolute top-4 left-4 bg-green-600 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_16px_rgba(22,163,74,0.6)] font-bold ring-2 ring-green-400/40">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> SOLD
                            </div>
                        @elseif($isBuyOnly)
                            <div class="absolute top-4 left-4 bg-secondary-fixed text-on-secondary-fixed px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_12px_rgba(203,163,88,0.5)] font-bold">
                                <span class="material-symbols-outlined text-[14px]">shopping_cart</span> BUY NOW
                            </div>
                        @elseif($isLive)
                            <div class="absolute top-4 left-4 bg-surface-container/90 backdrop-blur-md border border-red-500/30 text-red-400 px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider flex items-center gap-1.5 font-bold shadow-[0_0_12px_rgba(239,68,68,0.25)]">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                <span class="material-symbols-outlined text-[14px] text-red-400">gavel</span> LIVE
                            </div>
                        @elseif($isEnded)
                            <div class="absolute top-4 left-4 bg-indigo-600 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 font-bold shadow-[0_4px_16px_rgba(79,70,229,0.6)] ring-2 ring-indigo-400/40">
                                <span class="material-symbols-outlined text-[14px]">timer_off</span> ENDED
                            </div>
                        @else
                            <div class="absolute top-4 left-4 bg-blue-600/90 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 font-bold shadow-[0_2px_12px_rgba(37,99,235,0.5)]">
                                <span class="material-symbols-outlined text-[14px]">schedule</span> UPCOMING
                            </div>
                        @endif

                        {{-- Timer / Category (Top-Right) --}}
                        @if($isLive)
                            <div class="absolute top-4 right-4 bg-surface-container-high/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full font-mono text-[12px] flex items-center gap-1.5 border border-white/20 shadow-[0_0_15px_rgba(0,0,0,0.3)] z-20">
                                <span class="material-symbols-outlined text-[16px] text-secondary-fixed animate-pulse">timer</span>
                                <span data-text-countdown="{{ $session->end_time->toISOString() }}" class="tracking-wider"></span>
                            </div>
                        @elseif($product->category)
                            <div class="absolute top-4 right-4 bg-surface/80 backdrop-blur-md text-secondary-fixed px-3 py-1 rounded-full font-label-caps text-[10px] border border-secondary-fixed/40 shadow-[0_0_10px_rgba(203,163,88,0.2)]">
                                {{ $product->category->name }}
                            </div>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="p-card-padding flex flex-col grow z-10 -mt-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-headline-md text-[20px] leading-tight font-semibold {{ ($isEnded && $winner) || ($session && $session->status === 'cancelled') ? 'text-on-surface-variant/60' : 'text-on-surface group-hover:text-secondary-fixed' }} transition-colors">{{ $product->title }}</h3>
                        </div>
                        <div class="flex items-center gap-1 text-on-surface-variant mb-4">
                            <span class="material-symbols-outlined text-[16px] text-secondary-fixed">verified</span>
                            <span class="text-sm line-clamp-1 group-hover:text-secondary-fixed transition-colors">{{ $product->farmer->farmerProfile?->farm_name ?? $product->farmer->name }}</span>
                        </div>
                        <div class="mt-auto flex items-end justify-between">
                            <div>
                                @if($isBuyOnly)
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">{{ $isOutOfStock ? 'Was' : 'Price' }}</p>
                                    <p class="font-price-display text-price-display {{ $isOutOfStock ? 'text-on-surface-variant/50 line-through' : 'text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]' }}">₹{{ number_format($product->price, 2) }}<span class="text-sm text-on-surface-variant font-normal drop-shadow-none">/{{ $product->unit }}</span></p>
                                @elseif($isLive)
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Current Bid</p>
                                    <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($session->currentPrice(), 2) }}<span class="text-sm text-on-surface-variant font-normal drop-shadow-none">/{{ $product->unit }}</span></p>
                                @elseif($session && $session->status === 'cancelled')
                                    <p class="text-[10px] text-red-400 mb-1 uppercase tracking-widest font-semibold">Cancelled</p>
                                    <p class="font-price-display text-price-display text-on-surface-variant/40 line-through">₹{{ number_format($session->start_price, 2) }}<span class="text-xs font-normal drop-shadow-none">/{{ $product->unit }}</span></p>
                                @elseif($isEnded && $winner)
                                    <p class="text-[10px] text-green-400 mb-1 uppercase tracking-widest font-semibold">Winning Bid</p>
                                    <p class="font-price-display text-price-display text-green-400 drop-shadow-[0_0_8px_rgba(34,197,94,0.3)]">₹{{ number_format($winner->amount, 2) }}</p>
                                    <p class="text-xs text-white mt-1 flex items-center gap-1 font-medium"><span class="material-symbols-outlined text-[14px] text-secondary-fixed">emoji_events</span> {{ $winner->consumer->name }}</p>
                                @elseif($isEnded)
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Auction Ended</p>
                                    <p class="font-price-display text-price-display text-on-surface-variant/50">No Bids</p>
                                @else
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Starting Price</p>
                                    <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($product->price ?? 0, 2) }}<span class="text-sm text-on-surface-variant font-normal drop-shadow-none">/{{ $product->unit }}</span></p>
                                @endif
                            </div>
                            @if($isOutOfStock)
                                <div class="bg-white/5 border border-white/10 text-on-surface-variant/40 px-5 py-2.5 rounded-full font-label-caps text-label-caps">
                                    Sold Out
                                </div>
                            @elseif($session && $session->status === 'cancelled')
                                <div class="bg-red-600/20 border border-red-500/30 text-red-400 px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all">
                                    Cancelled
                                </div>
                            @elseif($isBuyOnly)
                                <div class="bg-secondary-fixed text-on-secondary-fixed px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all transform group-hover:scale-105 group-hover:shadow-[0_0_15px_rgba(203,163,88,0.4)]">
                                    Buy Now
                                </div>
                            @elseif($isLive)
                                <div class="bg-secondary-fixed text-on-secondary-fixed px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all transform group-hover:scale-105 group-hover:shadow-[0_0_15px_rgba(203,163,88,0.4)]">
                                    Bid Now
                                </div>
                            @elseif($isEnded && !$winner)
                                <div class="bg-white/5 border border-white/10 text-on-surface-variant/40 px-5 py-2.5 rounded-full font-label-caps text-label-caps">
                                    No Bids
                                </div>
                            @elseif($isEnded)
                                <div class="bg-green-600/20 border border-green-500/30 text-green-400 px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all">
                                    View Result
                                </div>
                            @else
                                <div class="bg-blue-600/20 border border-blue-500/30 text-blue-400 px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all transform group-hover:scale-105">
                                    View Details
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-DEFAULT">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/50 mb-4 block">search_off</span>
                    <h3 class="font-headline-md text-white text-[24px] mb-2">{{ $isAuctionFilter ? 'No Auctions Found' : 'No Products Found' }}</h3>
                    <p class="text-on-surface-variant font-body-md max-w-md mx-auto">{{ $isAuctionFilter ? 'There are no active auctions matching your criteria.' : 'There are no products matching your criteria.' }} Check back soon for fresh harvests.</p>
                    <a href="{{ route('marketplace') }}" class="inline-block mt-6 px-6 py-2 rounded-full border border-white/10 text-on-surface hover:border-secondary-fixed hover:text-secondary-fixed transition-colors font-label-caps text-label-caps">Clear Filters</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
/* Laravel pagination override for dark theme */
nav[role="navigation"] {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
nav[role="navigation"] p {
    color: var(--color-on-surface-variant);
    font-size: 0.875rem;
}
nav[role="navigation"] span.relative.z-0.inline-flex, nav[role="navigation"] div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:last-child > span {
    display: flex;
    gap: 0.5rem;
    box-shadow: none;
}
nav[role="navigation"] span.relative.z-0.inline-flex > span,
nav[role="navigation"] span.relative.z-0.inline-flex > a {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.5rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

/* Base style for inactive links */
nav[role="navigation"] a {
    color: var(--color-on-surface-variant);
    border: 1px solid rgba(255, 255, 255, 0.1);
    background-color: transparent;
}
nav[role="navigation"] a:hover {
    color: var(--color-secondary-fixed);
    border-color: rgba(255, 255, 255, 0.2);
    z-index: 2;
}

/* Base style for disabled elements (like dots) */
nav[role="navigation"] span[aria-disabled="true"] {
    color: var(--color-on-surface-variant);
    opacity: 0.5;
}

/* Style for active page */
nav[role="navigation"] span[aria-current="page"] > span {
    background-color: var(--color-secondary-fixed);
    color: var(--color-on-secondary-fixed);
    border: 1px solid var(--color-secondary-fixed);
    box-shadow: 0 0 15px rgba(203,163,88, 0.3);
    border-radius: 9999px;
    min-width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
