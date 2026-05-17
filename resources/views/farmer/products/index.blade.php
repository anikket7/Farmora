@extends('layouts.farmer')
@section('title', 'My Products')
@section('page-title', 'My Product Listings')
@section('content')
    <div class="fade-in max-w-[1440px] mx-auto w-full">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h2 class="font-headline-md text-[28px] text-white">Your Harvest</h2>
                <p class="font-body-md text-on-surface-variant text-sm mt-1">Managing <span
                        class="text-secondary-fixed font-bold">{{ $products->total() }}</span> active listings in the
                    network.</p>
            </div>
            <a href="{{ route('farmer.products.create') }}"
                class="bg-secondary-fixed text-surface-container-lowest font-label-caps text-[12px] tracking-widest px-6 py-3 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex items-center gap-2 hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[18px]">add</span> ADD PRODUCT
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 xl:gap-6">
            @forelse($products as $product)
                @php
                    $session = $product->bidSession;
                    $isLive = $session && $session->isActive();
                    $isEnded = $session && $session->isEnded();
                    $isBuyOnly = $product->listing_type === 'buy';
                    $isOutOfStock = $isBuyOnly && $product->quantity <= 0;
                @endphp
                <div class="bg-surface-container/30 backdrop-blur-xl border {{ $isLive ? 'border-secondary-fixed/40 shadow-[0_0_25px_rgba(203,163,88,0.08)]' : 'border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)]' }} rounded-DEFAULT overflow-hidden group hover:border-secondary-fixed hover:-translate-y-1 transition-all duration-300 flex flex-col relative hover:shadow-[0_0_30px_rgba(203,163,88,0.15)]">
                    <div class="absolute inset-0 {{ $isLive ? 'bg-secondary-fixed/5' : 'bg-transparent' }} pointer-events-none group-hover:bg-secondary-fixed/10 transition-colors"></div>
                    
                    <!-- Image Header -->
                    <div class="relative h-48 overflow-hidden">
                        @if($product->images->first() && $product->images->first()->image_path !== 'products/placeholder.jpg')
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out {{ $isOutOfStock ? 'grayscale-50 brightness-75' : '' }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl bg-surface-container-high/50 group-hover:scale-105 transition-transform duration-700 ease-out {{ $isOutOfStock ? 'grayscale-50 brightness-75' : '' }}">{{ $product->category->icon ?? '🌾' }}</div>
                        @endif
                        <div class="absolute inset-0 bg-linear-to-t from-surface-container/90 via-surface-container/20 to-transparent"></div>

                        <!-- Status Badge (Top-Left) -->
                        @if($product->status === 'pending')
                            <div class="absolute top-4 left-4 bg-orange-500/90 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_12px_rgba(249,115,22,0.5)] font-bold">
                                <span class="material-symbols-outlined text-[14px]">pending</span> PENDING
                            </div>
                        @elseif($isOutOfStock)
                            <div class="absolute top-4 left-4 bg-red-500 text-white px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_16px_rgba(239,68,68,0.6)] font-bold">
                                <span class="material-symbols-outlined text-[14px]">inventory</span> OUT OF STOCK
                            </div>
                        @elseif($isBuyOnly)
                            <div class="absolute top-4 left-4 bg-secondary-fixed text-on-secondary-fixed px-4 py-1.5 rounded-full font-label-caps text-[11px] tracking-wider flex items-center gap-1.5 shadow-[0_2px_12px_rgba(203,163,88,0.5)] font-bold">
                                <span class="material-symbols-outlined text-[14px]">shopping_cart</span> DIRECT BUY
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
                                <span class="material-symbols-outlined text-[14px]">schedule</span> AUCTION
                            </div>
                        @endif

                        <!-- Timer / Category (Top-Right) -->
                        @if($isLive)
                            <div class="absolute top-4 right-4 bg-surface-container-high/90 backdrop-blur-md text-white px-3 py-1.5 rounded-full font-mono text-[12px] flex items-center gap-1.5 border border-white/20 shadow-[0_0_15px_rgba(0,0,0,0.3)] z-20">
                                <span class="material-symbols-outlined text-[16px] text-secondary-fixed animate-pulse">timer</span>
                                <span data-text-countdown="{{ $session->end_time->toISOString() }}" class="tracking-wider"></span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-card-padding flex flex-col grow z-10 -mt-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-headline-md text-[20px] leading-tight font-semibold text-on-surface group-hover:text-secondary-fixed transition-colors">{{ $product->title }}</h3>
                        </div>
                        <div class="flex items-center gap-1 text-on-surface-variant mb-4">
                            <span class="bg-white/5 border border-white/10 px-2 py-0.5 rounded font-label-caps text-[10px]">{{ $product->category->name }}</span>
                            <span class="text-sm line-clamp-1 ml-1">• {{ (int) $product->quantity }} {{ $product->unit }}</span>
                        </div>
                        
                        <div class="mt-auto flex items-end justify-between">
                            <div>
                                @if($isBuyOnly)
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Asking Price</p>
                                    <p class="font-price-display text-price-display {{ $isOutOfStock ? 'text-on-surface-variant/50 line-through' : 'text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]' }}">₹{{ number_format($product->price, 2) }}</p>
                                @elseif($isLive)
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Current Bid</p>
                                    <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($session->currentPrice(), 2) }}</p>
                                @else
                                    <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Asking Price</p>
                                    <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">{{ $product->price ? '₹' . number_format($product->price, 2) : 'Bid Only' }}</p>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-1 text-on-surface-variant bg-white/5 px-2 py-1.5 rounded-md border border-white/10">
                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                <span class="font-label-caps text-[10px]">{{ $product->views_count }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-5 pt-4 border-t border-white/10 flex gap-3">
                            <a href="{{ route('farmer.products.edit', $product) }}"
                                class="flex-1 bg-white/5 hover:bg-white/15 text-white font-label-caps text-[11px] tracking-widest py-2 rounded-xl transition-all text-center flex items-center justify-center gap-2 border border-white/10 hover:border-secondary-fixed/50 hover:text-secondary-fixed">
                                <span class="material-symbols-outlined text-[16px]">edit</span> EDIT
                            </a>
                            <form method="POST" action="{{ route('farmer.products.destroy', $product) }}" class="flex-1"
                                onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 font-label-caps text-[11px] tracking-widest py-2 rounded-xl transition-all text-center flex items-center justify-center gap-2 border border-red-500/20 hover:border-red-500/50 hover:text-red-300">
                                    <span class="material-symbols-outlined text-[16px]">delete</span> DELETE
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-16 flex flex-col items-center justify-center bg-surface-container/30 border border-white/5 rounded-[32px] backdrop-blur-xl">
                    <div
                        class="w-20 h-20 bg-surface-container-high rounded-full flex items-center justify-center mb-6 shadow-[inset_0_2px_10px_rgba(0,0,0,0.2)]">
                        <span class="material-symbols-outlined text-[32px] text-on-surface-variant">psychiatry</span>
                    </div>
                    <h3 class="font-headline-md text-xl text-white mb-2">No Active Harvests</h3>
                    <p class="font-body-md text-on-surface-variant max-w-md text-center mb-8">You haven't listed any produce on
                        the network yet. Begin cultivating your digital presence.</p>
                    <a href="{{ route('farmer.products.create') }}"
                        class="bg-secondary-fixed text-surface-container-lowest font-label-caps text-[12px] tracking-widest px-8 py-4 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex items-center gap-2 hover:-translate-y-1">
                        <span class="material-symbols-outlined text-[18px]">add</span> INITIALIZE LISTING
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-8 pagination-wrapper">{{ $products->links() }}</div>
    </div>
@endsection