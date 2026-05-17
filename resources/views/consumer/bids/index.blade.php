@extends('layouts.app')
@section('title', 'My Bids')
@section('content')
<div class="max-w-[1200px] mx-auto px-container-margin py-12 fade-in relative z-10">
    <div class="flex items-center gap-4 mb-10">
        <div class="w-14 h-14 rounded-full bg-secondary-fixed/20 flex items-center justify-center border border-secondary-fixed/30 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
            <span class="material-symbols-outlined text-secondary-fixed text-2xl">gavel</span>
        </div>
        <h1 class="font-headline-md text-3xl md:text-[40px] font-semibold text-white tracking-tight">My Bids</h1>
    </div>

    @if($bidSessions->count() > 0)
        <div class="flex flex-col gap-6">
            @foreach($bidSessions as $session)
                @php
                    $myBids = $session->bids->where('consumer_id', auth()->id())->sortByDesc('placed_at');
                    $latestBid = $myBids->first();
                    $allBids = $session->bids->sortByDesc('placed_at');
                @endphp
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.37)] transition-all hover:border-white/10 group flex flex-col">
                    <div class="p-6 flex flex-col md:flex-row gap-6 md:items-center">
                        {{-- Image --}}
                        <a href="{{ route('marketplace.show', $session->product) }}" class="w-full md:w-32 h-32 shrink-0 rounded-[16px] overflow-hidden bg-surface-container-high/50 flex items-center justify-center relative border border-white/5 hover:ring-2 hover:ring-secondary-fixed/50 transition-all block">
                            @if($session->product->images->first() && $session->product->images->first()->image_path !== 'products/placeholder.jpg')
                                <img src="{{ asset('storage/' . $session->product->images->first()->image_path) }}" alt="{{ $session->product->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl">{{ $session->product->category->icon ?? '🌾' }}</span>
                            @endif
                        </a>

                        {{-- Details --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Placed {{ $latestBid->placed_at->diffForHumans() }}</span>
                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                
                                @if($session->isActive())
                                    @if($latestBid->amount >= $session->currentPrice())
                                        <span class="px-3 py-1 rounded-full border bg-success/20 text-success border-success/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm">
                                            Currently Winning
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full border bg-warning/20 text-warning border-warning/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm">
                                            Outbid
                                        </span>
                                    @endif
                                @elseif($session->status !== 'cancelled')
                                    @if($latestBid->amount >= $session->currentPrice())
                                        <span class="px-3 py-1 rounded-full border bg-success/20 text-success border-success/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm">
                                            Auction Won
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full border bg-error/20 text-error border-error/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm">
                                            Auction Lost
                                        </span>
                                    @endif
                                @endif
                                
                                @if($session->isActive())
                                    <span class="px-3 py-1 rounded-full border bg-secondary-fixed/20 text-secondary-fixed border-secondary-fixed/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm ml-auto md:ml-0 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed animate-pulse"></span> Live
                                    </span>
                                @elseif($session->status === 'cancelled')
                                    <span class="px-3 py-1 rounded-full border bg-error/20 text-error border-error/30 font-label-caps text-[9px] tracking-widest uppercase shadow-sm ml-auto md:ml-0">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full border bg-white/10 text-on-surface-variant border-white/20 font-label-caps text-[9px] tracking-widest uppercase shadow-sm ml-auto md:ml-0">
                                        Ended
                                    </span>
                                @endif
                            </div>
                            
                            <a href="{{ route('marketplace.show', $session->product) }}" class="block group/title">
                                <h3 class="font-headline-md text-[22px] font-semibold text-white mb-2 truncate group-hover/title:text-secondary-fixed group-hover:text-secondary-fixed transition-colors">{{ $session->product->title }}</h3>
                            </a>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center gap-x-6 gap-y-2 text-sm font-body-md text-on-surface-variant">
                                <span><strong class="text-white">Farmer:</strong> {{ $session->product->farmer->name }}</span>
                                <span><strong class="text-white">Current Highest:</strong> ₹{{ number_format($session->currentPrice(), 2) }}</span>
                            </div>
                        </div>

                        {{-- Price & Action --}}
                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 shrink-0 pt-4 md:pt-0 border-t border-white/5 md:border-0">
                            <div class="text-left md:text-right">
                                <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1">My Bid</div>
                                <div class="font-price-display text-2xl {{ $latestBid->amount >= $session->currentPrice() ? 'text-success' : 'text-secondary-fixed' }}">₹{{ number_format($latestBid->amount, 2) }}</div>
                            </div>
                            <a href="{{ route('marketplace.show', $session->product) }}" class="bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-6 py-2.5 rounded-full font-label-caps text-[11px] tracking-widest uppercase transition-all flex items-center gap-2">
                                View Auction
                            </a>
                        </div>
                    </div>

                    {{-- Bid History Details/Summary Accordion --}}
                    @if($allBids->count() > 1)
                        <details class="border-t border-white/5 bg-surface-container-low/20 group/details">
                            <summary class="list-none w-full px-6 py-3.5 flex items-center justify-between font-label-caps text-[10px] text-on-surface-variant hover:text-white tracking-widest uppercase transition-colors cursor-pointer select-none [&::-webkit-details-marker]:hidden">
                                <span class="flex items-center gap-1.5 font-bold">
                                    <span class="material-symbols-outlined text-[14px]">history</span> View Bidding War ({{ $allBids->count() }} bids)
                                </span>
                                <span class="material-symbols-outlined text-[16px] transition-transform duration-300 group-open/details:rotate-180">expand_more</span>
                            </summary>
                            
                            <div class="px-6 pb-5 pt-1 flex flex-col gap-2">
                                @foreach($allBids as $index => $historyBid)
                                    <div class="flex items-center justify-between py-2.5 border-b border-white/5 last:border-0 text-xs">
                                        <div class="flex items-center gap-3">
                                            <span class="font-body-md text-sm font-semibold {{ $historyBid->consumer_id === auth()->id() ? 'text-secondary-fixed' : 'text-white/80' }}">
                                                {{ $historyBid->consumer->name }}
                                            </span>
                                            <span class="text-on-surface-variant/40">({{ $historyBid->placed_at->diffForHumans() }})</span>
                                        </div>
                                        <div class="font-price-display text-sm {{ $historyBid->amount >= $session->currentPrice() ? 'text-success' : 'text-secondary-fixed/70' }}">
                                            ₹{{ number_format($historyBid->amount, 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $bidSessions->links() }}
        </div>
    @else
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[40px] p-16 text-center max-w-2xl mx-auto shadow-[0_8px_32px_rgba(0,0,0,0.37)] mt-12 flex flex-col items-center">
            <div class="w-24 h-24 rounded-full bg-surface-container-high/50 flex items-center justify-center mb-6 shadow-[inset_0_2px_10px_rgba(0,0,0,0.2)] border border-white/5">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant/50">gavel</span>
            </div>
            <h2 class="font-headline-md text-[28px] font-bold text-white mb-3">No Bids Yet</h2>
            <p class="font-body-md text-on-surface-variant mb-8 max-w-md">You haven't participated in any auctions yet. Explore the marketplace for exclusive harvests available for bidding.</p>
            <a href="{{ route('marketplace') }}" class="bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-8 py-4 rounded-full font-label-caps text-[12px] tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.2)] hover:shadow-[0_0_30px_rgba(203,163,88,0.4)] border border-secondary-fixed/30 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                <span class="material-symbols-outlined text-[18px]">storefront</span> Explore Marketplace
            </a>
        </div>
    @endif
</div>
@endsection
