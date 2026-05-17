@extends('layouts.admin')
@section('title', 'Bid Sessions')
@section('page-title', 'Bid Session Management')
@section('content')
<div class="flex flex-col gap-6">
    {{-- Filters --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-auto">
                 <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Status</label>
                <select name="status" class="w-full sm:w-48 bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors appearance-none">
                    <option value="" class="bg-surface">All Status</option>
                    <option value="active" class="bg-surface" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" class="bg-surface" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" class="bg-surface" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                <button type="submit" class="flex-1 sm:flex-none bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)]">Filter</button>
            </div>
        </form>
    </div>

    {{-- Bid Sessions Table --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/10 bg-surface/40">
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Product</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Farmer</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Starting Price</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Current Price</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Bids</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Time Left</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Status</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bidSessions as $session)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <a href="{{ route('marketplace.show', $session->product) }}" target="_blank" class="font-body-md text-base font-medium text-white group-hover:text-secondary-fixed transition-colors hover:underline">{{ Str::limit($session->product->title, 25) }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.show', $session->product->farmer) }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">person</span> {{ $session->product->farmer->name }}</a>
                        </td>
                        <td class="px-6 py-4">
                             <span class="font-body-md text-sm text-white/70">₹{{ number_format($session->start_price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                             <span class="font-price-display text-lg font-semibold text-secondary-fixed">₹{{ number_format($session->currentPrice(), 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-surface-container-high border border-white/10 px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest text-white inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">sell</span> {{ $session->bids->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($session->isActive())
                                <div data-compact-countdown="{{ $session->end_time->toISOString() }}"></div>
                            @else
                                <span class="font-body-md text-sm text-on-surface-variant">{{ $session->end_time->diffForHumans() }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                             @php
                                $statusColors = [
                                    'active' => 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30',
                                    'completed' => 'text-tertiary bg-tertiary/10 border-tertiary/30',
                                    'cancelled' => 'text-error bg-error/10 border-error/30'
                                ];
                                $colors = $statusColors[$session->status] ?? 'text-on-surface-variant bg-surface-container-high border-white/10';
                            @endphp
                            <span class="px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase border {{ $colors }} inline-flex items-center gap-1 shadow-[0_0_10px_currentColor]">
                                @if($session->status === 'active') <span class="w-1.5 h-1.5 rounded-full bg-primary-fixed animate-pulse"></span> @endif
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($session->status === 'active')
                                <div class="flex items-center justify-end gap-2">
                                    {{-- End Auction (Force Close) --}}
                                    <form method="POST" action="{{ route('admin.bids.close', $session) }}" onsubmit="return confirm('Force close this bid session? This will declare the current highest bidder as the winner.')">
                                        @csrf
                                        <button type="submit" class="bg-secondary-fixed/20 border border-secondary-fixed/30 text-secondary-fixed hover:bg-secondary-fixed hover:text-on-secondary-fixed px-3.5 py-1.5 rounded-full font-label-caps text-[9px] tracking-widest uppercase transition-all flex items-center gap-1 cursor-pointer" title="End Auction Early">
                                            <span class="material-symbols-outlined text-[12px]">gavel</span> End Auction
                                        </button>
                                    </form>

                                    {{-- Cancel Auction --}}
                                    <form method="POST" action="{{ route('admin.bids.cancel', $session) }}" onsubmit="return confirm('Are you sure you want to cancel this auction? The listing will be returned back to active, and no winner will be declared.')">
                                        @csrf
                                        <button type="submit" class="bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error px-3.5 py-1.5 rounded-full font-label-caps text-[9px] tracking-widest uppercase transition-all flex items-center gap-1 cursor-pointer" title="Cancel Auction">
                                            <span class="material-symbols-outlined text-[12px]">cancel</span> Cancel
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-right">
                                    <span class="text-on-surface-variant text-[20px] material-symbols-outlined opacity-50 inline-block">remove</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-4 opacity-50">gavel</span>
                                <p class="font-body-md">No bid sessions found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $bidSessions->withQueryString()->links() }}</div>
</div>
@endsection
