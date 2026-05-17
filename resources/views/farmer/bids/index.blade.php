@extends('layouts.farmer')
@section('title', 'Bid Sessions')
@section('page-title', 'My Bid Sessions')
@section('content')
<div class="fade-in relative z-10 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="font-headline-md text-2xl font-bold text-white mb-2">Active & Past Auctions</h2>
            <p class="font-body-md text-sm text-on-surface-variant">Monitor your live bidding sessions and past results.</p>
        </div>
        <div class="bg-surface-container/30 border border-white/5 rounded-2xl px-4 py-2 flex items-center gap-3 shadow-[0_4px_15px_rgba(0,0,0,0.2)]">
            <span class="material-symbols-outlined text-secondary-fixed">gavel</span>
            <span class="font-label-caps text-xs tracking-widest uppercase text-white">{{ $bidSessions->total() }} Sessions</span>
        </div>
    </div>

    @if($bidSessions->count() > 0)
        <div class="flex flex-col gap-5">
            @foreach($bidSessions as $session)
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-2xl p-5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] transition-all hover:border-white/10 flex flex-col lg:flex-row gap-6 lg:items-center relative overflow-hidden group">
                    {{-- Side highlight line based on status --}}
                    @php
                        $statusColors = [
                            'active' => 'bg-secondary-fixed',
                            'completed' => 'bg-info',
                            'cancelled' => 'bg-error',
                        ];
                        $highlightColor = $statusColors[$session->status] ?? 'bg-white/20';
                        
                        $textColors = [
                            'active' => 'text-secondary-fixed',
                            'completed' => 'text-info',
                            'cancelled' => 'text-error',
                        ];
                        $statusTextColor = $textColors[$session->status] ?? 'text-white';
                    @endphp
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $highlightColor }} opacity-70 group-hover:opacity-100 transition-opacity"></div>

                    {{-- Image --}}
                    <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden bg-surface-container-high/50 flex items-center justify-center border border-white/5 ml-2">
                        @if($session->product->images->first() && $session->product->images->first()->image_path !== 'products/placeholder.jpg')
                            <img src="{{ asset('storage/' . $session->product->images->first()->image_path) }}" alt="{{ $session->product->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl">{{ $session->product->category->icon ?? '🌾' }}</span>
                        @endif
                    </div>

                    {{-- Session Information --}}
                    <div class="flex-1 min-w-0 grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Product --}}
                        <div class="flex flex-col justify-center">
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1">
                                @if($session->isActive())
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed animate-pulse"></span> Live Auction</span>
                                @else
                                    Auction Ended
                                @endif
                            </span>
                            <a href="{{ route('marketplace.show', $session->product) }}" class="font-headline-md text-base font-semibold text-white truncate hover:text-secondary-fixed transition-colors">{{ $session->product->title }}</a>
                        </div>

                        {{-- Financials --}}
                        <div class="flex flex-col justify-center md:border-l md:border-white/5 md:pl-6">
                            <div class="flex items-end gap-2 mb-1">
                                <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Start Price:</span>
                                <span class="font-body-md text-sm text-white font-medium">₹{{ number_format($session->start_price, 2) }}</span>
                            </div>
                            <div class="flex items-end gap-2">
                                <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Current:</span>
                                <span class="font-price-display text-lg text-secondary-fixed leading-none drop-shadow-[0_0_10px_rgba(203,163,88,0.3)]">₹{{ number_format($session->currentPrice(), 2) }}</span>
                            </div>
                        </div>

                        {{-- Engagement & Timing --}}
                        <div class="flex flex-col justify-center md:border-l md:border-white/5 md:pl-6">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant">group</span>
                                <span class="font-body-md text-sm text-white font-medium">{{ $session->bids->count() }} Bids</span>
                            </div>
                            @if($session->status === 'cancelled')
                                <div class="font-label-caps text-[10px] text-error tracking-widest uppercase">Cancelled</div>
                            @elseif($session->bids->count() > 0)
                                <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase truncate w-full">Top: <span class="text-white">{{ $session->highestBid()?->consumer?->name }}</span></div>
                            @else
                                <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">No bids yet</div>
                            @endif
                        </div>
                    </div>

                    {{-- Timer & Status & Winner --}}
                    <div class="shrink-0 flex flex-col items-end border-t lg:border-t-0 border-white/5 pt-4 lg:pt-0 min-w-[180px]">
                        <span class="font-label-caps text-[10px] tracking-widest uppercase {{ $statusTextColor }} mb-2 font-bold">{{ ucfirst($session->status) }}</span>
                        
                        @if($session->isActive())
                            <div class="bg-surface-container-high border border-secondary-fixed/30 rounded-lg px-4 py-2 w-full text-center mb-3">
                                <div data-countdown="{{ $session->end_time->toISOString() }}" class="font-price-display text-sm text-secondary-fixed"></div>
                            </div>
                            <form method="POST" action="{{ route('farmer.bids.close', $session) }}" onsubmit="return confirm('Are you sure you want to end this auction early? This will declare the current highest bidder as the winner.')" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 hover:border-red-500 px-3 py-2 rounded-xl font-label-caps text-[10px] tracking-widest uppercase transition-all shadow-[0_0_10px_rgba(239,68,68,0)] hover:shadow-[0_0_15px_rgba(239,68,68,0.3)] flex items-center justify-center gap-1 font-bold">
                                    <span class="material-symbols-outlined text-[14px]">gavel</span> End Auction
                                </button>
                            </form>
                        @elseif($session->status === 'cancelled')
                            <div class="bg-surface-container-high border border-red-500/20 rounded-lg px-4 py-2 w-full text-center">
                                <span class="font-label-caps text-[10px] text-error tracking-widest uppercase font-bold">
                                    Cancelled
                                </span>
                            </div>
                        @elseif($session->isEnded() && $session->highestBid())
                            {{-- Winner Panel --}}
                            <div class="bg-secondary-container/10 border border-secondary-fixed/30 rounded-xl px-4 py-3 w-full shadow-[0_0_15px_rgba(203,163,88,0.08)]">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="material-symbols-outlined text-secondary-fixed text-[16px]">emoji_events</span>
                                    <span class="font-label-caps text-[10px] text-secondary-fixed tracking-widest uppercase font-bold">Winner</span>
                                </div>
                                <p class="font-body-md text-sm text-white font-semibold truncate">{{ $session->highestBid()->consumer->name }}</p>
                                <p class="font-body-md text-xs text-on-surface-variant truncate">{{ $session->highestBid()->consumer->email }}</p>
                                <p class="font-price-display text-sm text-secondary-fixed mt-1 drop-shadow-[0_0_5px_rgba(203,163,88,0.3)]">₹{{ number_format($session->highestBid()->amount, 2) }}</p>
                            </div>
                        @else
                            <div class="bg-surface-container-high border border-white/5 rounded-lg px-4 py-2 w-full text-center">
                                <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">
                                    {{ $session->highestBid() ? 'Auction Ended' : 'No bids received' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $bidSessions->links('pagination::tailwind') }}
        </div>
    @else
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-3xl p-16 text-center shadow-[0_8px_32px_rgba(0,0,0,0.37)] mt-12 flex flex-col items-center">
            <div class="w-20 h-20 rounded-full bg-surface-container-high/50 flex items-center justify-center mb-6 border border-white/5 shadow-[inset_0_2px_10px_rgba(0,0,0,0.2)]">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant/50">gavel</span>
            </div>
            <h2 class="font-headline-md text-[24px] font-bold text-white mb-2">No Bid Sessions</h2>
            <p class="font-body-md text-on-surface-variant max-w-md">You haven't listed any products for auction yet. Select 'Bidding Only' when adding a new product to start an auction.</p>
        </div>
    @endif
</div>
@endsection
