@extends('layouts.app')

@section('title', $product->title)

@section('content')
    <div class="max-w-[1440px] w-full mx-auto px-container-margin py-4 fade-in relative z-10">
        <a href="{{ route('marketplace') }}"
            class="text-on-surface-variant hover:text-secondary-fixed mb-2 inline-flex items-center gap-2 font-label-caps text-label-caps tracking-widest uppercase transition-colors">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Back to Marketplace
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mt-2 items-start">
            {{-- Product Images Area --}}
            <div
                class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[24px] p-3 lg:p-4 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                <div
                    class="rounded-[20px] overflow-hidden mb-3 bg-surface-container-high/50 aspect-[4/3] flex items-center justify-center relative">
                    @if($product->images->first() && $product->images->first()->image_path !== 'products/placeholder.jpg')
                        <img id="main-product-image" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}"
                            class="w-full h-full object-cover transition-opacity duration-300">
                    @else
                        <span class="text-8xl">{{ $product->category->icon ?? '🌾' }}</span>
                    @endif
                    <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-[20px] pointer-events-none"></div>
                </div>
                @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-1">
                        <style>
                            .hide-scrollbar::-webkit-scrollbar {
                                display: none;
                            }
                        </style>
                        @foreach($product->images as $img)
                            <div onclick="switchImage(this, '{{ asset('storage/' . $img->image_path) }}')"
                                class="product-thumb w-16 h-16 shrink-0 rounded-xl overflow-hidden cursor-pointer border-2 {{ $loop->first ? 'border-secondary-fixed' : 'border-transparent' }} hover:border-secondary-fixed/50 transition-colors relative">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="">
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-xl pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <script>
                function switchImage(thumb, src) {
                    const main = document.getElementById('main-product-image');
                    if (!main) return;
                    main.style.opacity = '0';
                    setTimeout(() => {
                        main.src = src;
                        main.style.opacity = '1';
                    }, 150);
                    document.querySelectorAll('.product-thumb').forEach(el => {
                        el.classList.remove('border-secondary-fixed');
                        el.classList.add('border-transparent');
                    });
                    thumb.classList.remove('border-transparent');
                    thumb.classList.add('border-secondary-fixed');
                }
            </script>

            {{-- Product Details Area --}}
            <div class="flex flex-col gap-4">
                {{-- Header & Badges --}}
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($product->isBuyable())
                            <span
                                class="bg-surface/60 backdrop-blur-md text-primary-fixed-dim px-3 py-1.5 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-primary-fixed-dim/30 shadow-[0_0_10px_rgba(169,210,147,0.1)]">
                                <span class="material-symbols-outlined text-[14px]">shopping_cart</span> Direct Buy
                            </span>
                        @endif
                        @if($product->isBiddable())
                            <span
                                class="bg-tertiary-container/80 backdrop-blur-md text-on-tertiary-container px-3 py-1.5 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-tertiary-container/50 shadow-[0_0_10px_rgba(251,159,117,0.2)]">
                                <span class="material-symbols-outlined text-[14px]">gavel</span> Biddable
                            </span>
                        @endif
                        <span
                            class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-white/10">
                            {{ $product->category->icon ?? '' }} {{ $product->category->name }}
                        </span>
                    </div>

                    <h1
                        class="font-headline-md text-[24px] md:text-[32px] font-semibold text-white mb-2 tracking-tight leading-tight">
                        {{ $product->title }}</h1>

                    <div
                        class="flex flex-wrap items-center gap-x-6 gap-y-2 text-on-surface-variant font-label-caps text-[10px] tracking-widest uppercase">
                        <span class="flex items-center gap-1">by <strong
                                class="text-secondary-fixed font-semibold">{{ $product->farmer->name }}</strong></span>
                        <span class="flex items-center gap-1"><span
                                class="material-symbols-outlined text-[14px]">location_on</span>
                            {{ $product->origin_location }}</span>
                        <span class="flex items-center gap-1"><span
                                class="material-symbols-outlined text-[14px]">visibility</span> {{ $product->views_count }}
                            views</span>
                    </div>
                </div>

                {{-- Price / Direct Buy Section --}}
                @if($product->isBuyable() && $product->price)
                  @if($product->quantity <= 0)
                    {{-- Out of Stock --}}
                    <div class="bg-surface-container/30 backdrop-blur-xl border border-red-500/20 rounded-[16px] p-4 lg:p-5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-red-500/15 flex items-center justify-center">
                                <span class="material-symbols-outlined text-red-400 text-xl">inventory</span>
                            </div>
                            <div>
                                <span class="font-headline-md text-lg font-semibold text-red-400">Out of Stock</span>
                                <p class="text-on-surface-variant text-xs font-body-md">This product is currently unavailable.</p>
                            </div>
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="font-label-caps text-label-caps text-on-surface-variant tracking-widest uppercase mb-1 block">Was priced at</span>
                                <div class="font-price-display text-[28px] text-on-surface-variant/50 line-through">₹{{ number_format($product->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                  @else
                    <div
                        class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[16px] p-4 lg:p-5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                        <div class="flex flex-col gap-4">
                            {{-- Price & Stock Info --}}
                            <div class="flex items-end justify-between">
                                <div>
                                    <span
                                        class="font-label-caps text-label-caps text-on-surface-variant tracking-widest uppercase mb-1 block">Price
                                        Per {{ $product->unit }}</span>
                                    <div
                                        class="font-price-display text-[32px] text-secondary-fixed drop-shadow-[0_0_15px_rgba(203,163,88,0.2)]">
                                        ₹{{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase block mb-1">In Stock</span>
                                    <span class="text-white font-medium text-lg">{{ (int) $product->quantity }} {{ $product->unit }}</span>
                                </div>
                            </div>

                            @auth
                                @if(auth()->user()->isConsumer())
                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                            {{-- Quantity Selector --}}
                                            <div class="flex items-center bg-surface/80 border border-white/10 rounded-full overflow-hidden">
                                                <button type="button" onclick="changeQty(-1)"
                                                    class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-white hover:bg-white/10 transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">remove</span>
                                                </button>
                                                <input type="number" name="quantity" id="buy-qty" value="1" min="1" max="{{ (int) $product->quantity }}"
                                                    class="w-14 text-center bg-transparent text-white font-medium text-lg border-none focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                    onchange="updateTotal()">
                                                <button type="button" onclick="changeQty(1)"
                                                    class="w-10 h-10 flex items-center justify-center text-on-surface-variant hover:text-white hover:bg-white/10 transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">add</span>
                                                </button>
                                            </div>

                                            {{-- Total Price --}}
                                            <div class="flex items-center gap-2 px-4">
                                                <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Total:</span>
                                                <span id="buy-total" class="font-price-display text-[24px] text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($product->price, 2) }}</span>
                                            </div>

                                            {{-- Add to Cart --}}
                                            <button type="submit"
                                                class="ml-auto bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.3)] hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] flex items-center justify-center gap-2 transform hover:-translate-y-1">
                                                <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span> Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                    <script>
                                        const unitPrice = {{ $product->price }};
                                        const maxQty = {{ (int) $product->quantity }};
                                        function changeQty(delta) {
                                            const input = document.getElementById('buy-qty');
                                            let val = Math.max(1, Math.min(maxQty, parseInt(input.value || 1) + delta));
                                            input.value = val;
                                            updateTotal();
                                        }
                                        function updateTotal() {
                                            const qty = Math.max(1, Math.min(maxQty, parseInt(document.getElementById('buy-qty').value || 1)));
                                            document.getElementById('buy-qty').value = qty;
                                            document.getElementById('buy-total').textContent = '₹' + (unitPrice * qty).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        }
                                    </script>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="w-full bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-8 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all flex items-center justify-center text-center">Login
                                    to Buy</a>
                            @endauth
                        </div>
                    </div>
                  @endif
                @endif

                {{-- Bidding Section --}}
                @if($product->bidSession)
                    <div class="bg-surface-container/30 backdrop-blur-xl border {{ $product->bidSession->isActive() ? 'border-secondary-fixed/40 shadow-[0_0_30px_rgba(203,163,88,0.1)]' : ($product->bidSession->status === 'cancelled' ? 'border-error/30 shadow-[0_0_30px_rgba(239,68,68,0.05)]' : 'border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]') }} rounded-[16px] p-4 lg:p-5 relative overflow-hidden"
                        data-bid-session="{{ $product->bidSession->id }}">
                        @if($product->bidSession->isActive())
                            <div class="absolute inset-0 bg-secondary-fixed/5 pointer-events-none"></div>
                        @endif

                        <h3
                            class="font-headline-md text-[20px] font-semibold text-white mb-4 flex items-center gap-2 relative z-10">
                            <span class="material-symbols-outlined text-secondary-fixed">gavel</span>
                            {{ $product->bidSession->status === 'cancelled' ? 'Auction Cancelled' : 'Live Auction' }}
                            
                            @if($product->bidSession->isActive())
                                <span
                                    class="bg-secondary-fixed/20 border border-secondary-fixed/50 text-secondary-fixed px-3 py-1 rounded-full font-label-caps text-[10px] tracking-widest uppercase flex items-center gap-2 ml-auto shadow-[0_0_10px_rgba(203,163,88,0.2)]">
                                    <span
                                        class="w-2 h-2 bg-secondary-fixed rounded-full animate-pulse shadow-[0_0_8px_rgba(203,163,88,0.8)]"></span>
                                    Active
                                </span>
                            @elseif($product->bidSession->status === 'cancelled')
                                <span
                                    class="bg-error/20 border border-error/50 text-error px-3 py-1 rounded-full font-label-caps text-[10px] tracking-widest uppercase flex items-center gap-2 ml-auto shadow-[0_0_10px_rgba(239,68,68,0.2)] animate-pulse">
                                    <span
                                        class="w-2 h-2 bg-error rounded-full shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                                    Cancelled
                                </span>
                            @endif
                        </h3>

                        <div class="grid grid-cols-2 gap-4 mb-5 relative z-10">
                            <div>
                                <span
                                    class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Current
                                    Highest Bid</span>
                                <div
                                    class="font-price-display text-[24px] text-secondary-fixed current-price drop-shadow-[0_0_10px_rgba(203,163,88,0.3)]">
                                    ₹{{ number_format($product->bidSession->currentPrice(), 2) }}</div>
                            </div>
                            <div>
                                <span
                                    class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Total
                                    Bids</span>
                                <div class="font-body-lg text-[20px] text-white font-medium bid-count">
                                    {{ $product->bidSession->bids->count() }} bids</div>
                            </div>
                            <div>
                                <span
                                    class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Min
                                    Increment</span>
                                <div class="font-body-md text-lg text-on-surface-variant">
                                    ₹{{ number_format($product->bidSession->min_increment, 2) }}</div>
                            </div>
                            <div>
                                <span
                                    class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Time
                                    Remaining</span>
                                @if($product->bidSession->isActive())
                                    <div class="font-mono text-lg text-white"
                                        data-countdown="{{ $product->bidSession->end_time->toISOString() }}"></div>
                                @elseif($product->bidSession->status === 'cancelled')
                                    <div class="flex flex-col gap-1">
                                        <span class="text-error font-bold font-label-caps tracking-widest uppercase">Cancelled</span>
                                        <span class="text-on-surface-variant text-[10px] tracking-widest font-label-caps uppercase">No winner declared</span>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1">
                                        <span class="text-tertiary font-bold font-label-caps tracking-widest uppercase">Auction Ended</span>
                                        @if($product->bidSession->highestBid())
                                            <span class="text-secondary-fixed text-[10px] tracking-widest font-label-caps uppercase">Winner: <span class="text-white">{{ $product->bidSession->highestBid()->consumer->name }}</span></span>
                                        @else
                                            <span class="text-on-surface-variant text-[10px] tracking-widest font-label-caps uppercase">No bids placed</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->isConsumer() && $product->bidSession->isActive())
                                <form method="POST" action="{{ route('bids.place', $product->bidSession) }}" class="relative z-10">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <div class="flex-1 relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-price-display">₹</span>
                                            <input type="number" name="amount" step="0.01"
                                                min="{{ $product->bidSession->currentPrice() + $product->bidSession->min_increment }}"
                                                class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full pl-8 pr-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50"
                                                placeholder="Min ₹{{ number_format($product->bidSession->currentPrice() + $product->bidSession->min_increment, 2) }}"
                                                required>
                                        </div>
                                        <button type="submit"
                                            class="bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.3)] hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] transform hover:-translate-y-1 whitespace-nowrap">Place
                                            Bid</button>
                                    </div>
                                    @error('amount')<p class="text-error text-sm mt-3 font-body-md">{{ $message }}</p>@enderror
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-8 py-4 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all flex items-center justify-center text-center relative z-10">Login
                                to Place Bid</a>
                        @endauth

                        {{-- Recent Bids --}}
                        @if($product->bidSession->bids->count() > 0)
                            <div class="mt-5 pt-4 border-t border-white/5 relative z-10">
                                <h4 class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-3">
                                    Recent Bids</h4>
                                <div class="flex flex-col gap-2">
                                    @foreach($product->bidSession->bids->sortByDesc('amount')->take(3) as $bid)
                                        <div
                                            class="flex items-center justify-between bg-surface/40 px-3 py-2 rounded-lg border border-white/5">
                                            <span class="font-body-md text-sm text-on-surface-variant">{{ $bid->consumer->name }}</span>
                                            <div class="text-right">
                                                <div
                                                    class="text-secondary-fixed font-price-display text-base drop-shadow-[0_0_5px_rgba(203,163,88,0.2)]">
                                                    ₹{{ number_format($bid->amount, 2) }}</div>
                                                <div class="text-[10px] text-on-surface-variant/70">
                                                    {{ $bid->placed_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Product Info & Farmer Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Product Info --}}
                    <div
                        class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[16px] p-4 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                        <h3 class="font-headline-md text-[18px] font-semibold text-white mb-4">Product Details</h3>
                        <div class="flex flex-col gap-3 text-sm font-body-md">
                            <div class="flex justify-between border-b border-white/5 pb-3">
                                <span class="text-on-surface-variant">Quantity:</span>
                                <span class="text-white font-medium">{{ (int) $product->quantity }} {{ $product->unit }}</span>
                            </div>
                            <div class="flex justify-between border-b border-white/5 pb-3">
                                <span class="text-on-surface-variant">Category:</span>
                                <span class="text-white font-medium">{{ $product->category->name }}</span>
                            </div>
                            @if($product->harvest_date)
                                <div class="flex justify-between border-b border-white/5 pb-3">
                                    <span class="text-on-surface-variant">Harvested:</span>
                                    <span class="text-white font-medium">{{ $product->harvest_date->format('M j, Y') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between border-b border-white/5 pb-3">
                                <span class="text-on-surface-variant">Origin:</span>
                                <span class="text-white font-medium">{{ $product->origin_location }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Description & Farmer --}}
                    <div class="flex flex-col gap-4">
                        <div
                            class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[16px] p-4 shadow-[0_8px_32px_rgba(0,0,0,0.37)] grow">
                            <h4 class="font-headline-md text-[18px] font-semibold text-white mb-3">Description</h4>
                            <p class="text-on-surface-variant text-sm font-body-md leading-relaxed">
                                {{ $product->description }}</p>
                        </div>

                        <div
                            class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[16px] p-3 flex items-center gap-3 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                            <div
                                class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-fixed text-lg font-bold border border-primary-fixed/20 shadow-[0_0_10px_rgba(169,210,147,0.1)]">
                                {{ substr($product->farmer->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-body-md text-base font-semibold text-white">{{ $product->farmer->name }}
                                </div>
                                <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">
                                    {{ $product->farmer->farmerProfile?->farm_name ?? 'Farmer' }} •
                                    {{ $product->farmer->location }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->count() > 0)
            <div class="mt-20 pt-10 border-t border-white/5">
                <h2 class="font-headline-md text-[28px] font-bold text-white mb-8">Related Harvests</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('marketplace.show', $related) }}"
                            class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden group hover:border-white/10 hover:-translate-y-1 transition-all duration-300 flex flex-col relative shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                            <div class="relative h-48 overflow-hidden">
                                @if($related->primaryImage && $related->primaryImage->image_path !== 'products/placeholder.jpg')
                                    <img src="{{ asset('storage/' . $related->primaryImage->image_path) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-6xl bg-surface-container-high/50 group-hover:scale-105 transition-transform duration-700 ease-out">
                                        {{ $related->category->icon ?? '🌾' }}</div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-surface-container/90 via-surface-container/20 to-transparent">
                                </div>
                            </div>
                            <div class="p-6 flex flex-col grow z-10 -mt-6">
                                <h3
                                    class="font-headline-md text-lg font-semibold text-white mb-2 group-hover:text-secondary-fixed transition-colors truncate">
                                    {{ $related->title }}</h3>
                                <div class="mt-auto flex justify-between items-end">
                                    <p
                                        class="font-price-display {{ $related->price ? 'text-primary-fixed drop-shadow-[0_0_8px_rgba(6,205,172,0.3)]' : 'text-tertiary drop-shadow-[0_0_8px_rgba(251,159,117,0.3)]' }} text-lg">
                                        {{ $related->price ? '₹' . number_format($related->price, 2) : 'Bid Only' }}
                                    </p>
                                    <span
                                        class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-on-surface group-hover:bg-secondary-fixed group-hover:text-on-secondary-fixed group-hover:border-secondary-fixed transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection