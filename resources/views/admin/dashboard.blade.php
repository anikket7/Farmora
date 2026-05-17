@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')
@section('content')
<div class="flex flex-col gap-8">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
        @php
            $statItems = [
                ['label' => 'Total Farmers', 'value' => $stats['total_farmers'], 'icon' => 'agriculture', 'color' => 'primary-fixed'],
                ['label' => 'Total Consumers', 'value' => $stats['total_consumers'], 'icon' => 'shopping_cart', 'color' => 'secondary-fixed'],
                ['label' => 'Pending Approvals', 'value' => $stats['pending_approvals'], 'icon' => 'hourglass_empty', 'color' => 'tertiary'],
                ['label' => 'Active Listings', 'value' => $stats['active_listings'], 'icon' => 'eco', 'color' => 'primary-fixed-dim'],
                ['label' => 'Active Bids', 'value' => $stats['active_bids'], 'icon' => 'gavel', 'color' => 'tertiary-fixed'],
                ['label' => 'Total Orders', 'value' => $stats['total_orders'], 'icon' => 'local_shipping', 'color' => 'secondary-fixed-dim'],
            ];
        @endphp

        @foreach($statItems as $stat)
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110 text-{{ $stat['color'] }}">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
            </div>
            <div class="mb-4">
                <span class="material-symbols-outlined text-{{ $stat['color'] }} text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
            </div>
            <div class="font-display-lg-mobile text-[32px] font-semibold text-white mb-1">{{ number_format($stat['value']) }}</div>
            <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Pending Approvals --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8 flex flex-col h-full">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-headline-md text-[24px] font-semibold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary">hourglass_empty</span> Pending Approvals
                </h2>
                <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="text-secondary-fixed text-sm hover:text-white transition-colors font-label-caps text-label-caps tracking-widest uppercase flex items-center gap-1">
                    View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between py-4 border-b border-white/5 last:border-0 group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-tertiary-container/20 flex items-center justify-center text-tertiary font-bold text-lg border border-tertiary/20 shadow-[0_0_15px_rgba(251,159,117,0.1)] group-hover:scale-105 transition-transform">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-body-md text-base font-medium text-white mb-0.5">
                                <a href="{{ route('admin.users.show', $user) }}" class="hover:text-secondary-fixed transition-colors hover:underline">{{ $user->name }}</a>
                            </div>
                            <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $user->email }} • {{ $user->role }}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                            @csrf
                            <button type="submit" class="bg-primary-container/30 border border-primary-container text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all shadow-[0_0_10px_rgba(6,205,172,0.1)] hover:shadow-[0_0_20px_rgba(6,205,172,0.3)] flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">check</span> Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                            @csrf
                            <button type="submit" class="bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">close</span> Reject
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-4 opacity-50">done_all</span>
                    <p class="font-body-md">No pending approvals</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Active Bid Sessions --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8 flex flex-col h-full">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-headline-md text-[24px] font-semibold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">gavel</span> Active Auctions
                </h2>
                <a href="{{ route('admin.bids.index') }}" class="text-secondary-fixed text-sm hover:text-white transition-colors font-label-caps text-label-caps tracking-widest uppercase flex items-center gap-1">
                    View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($activeBids as $session)
                <div class="py-4 border-b border-white/5 last:border-0 group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-body-md text-base font-medium text-white">
                            <a href="{{ route('marketplace.show', $session->product) }}" class="hover:text-secondary-fixed transition-colors hover:underline">{{ $session->product->title }}</a>
                        </div>
                        <span class="bg-secondary-fixed/10 border border-secondary-fixed/30 text-secondary-fixed px-3 py-1 rounded-full font-label-caps text-[10px] tracking-widest uppercase shadow-[0_0_10px_rgba(203,163,88,0.15)] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary-fixed animate-pulse"></span> Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-3">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">person</span> {{ $session->product->farmer->name }} • {{ $session->bids->count() }} bids</span>
                        <span class="text-secondary-fixed font-price-display text-sm">₹{{ number_format($session->currentPrice(), 2) }}</span>
                    </div>
                    <div class="mt-2 text-xs font-mono text-on-surface-variant flex items-center gap-1" data-countdown="{{ $session->end_time->toISOString() }}">
                        <span class="material-symbols-outlined text-[14px] text-error">timer</span> Ends soon...
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-4 opacity-50">gavel</span>
                    <p class="font-body-md">No active bid sessions</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
