@extends('layouts.farmer')

@section('title', 'Farmer Dashboard')
@section('page-title', 'Farm Overview')

@section('content')
<div class="flex flex-col gap-8">
    {{-- Quick Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('farmer.products.create') }}" class="bg-primary-fixed text-on-primary-fixed px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase hover:bg-white hover:text-black transition-all shadow-[0_0_15px_rgba(6,205,172,0.3)] hover:shadow-[0_0_25px_rgba(6,205,172,0.5)] flex items-center gap-2 transform hover:-translate-y-0.5">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Add New Product
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $statItems = [
                ['label' => 'Total Products', 'value' => $stats['total_products'], 'icon' => 'inventory_2', 'color' => 'primary-fixed'],
                ['label' => 'Active Bids', 'value' => $stats['active_bids'], 'icon' => 'gavel', 'color' => 'tertiary'],
                ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'icon' => 'pending_actions', 'color' => 'tertiary-fixed'],
                ['label' => 'Total Revenue', 'value' => '₹'.number_format($stats['total_revenue'], 2), 'icon' => 'payments', 'color' => 'secondary-fixed'],
            ];
        @endphp

        @foreach($statItems as $stat)
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110 text-{{ $stat['color'] }}">
                <span class="material-symbols-outlined text-8xl" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
            </div>
            <div class="mb-4">
                <span class="material-symbols-outlined text-{{ $stat['color'] }} text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
            </div>
            <div class="font-display-lg-mobile text-[32px] font-semibold text-white mb-1">{{ $stat['value'] }}</div>
            <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Products --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8 flex flex-col h-full">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-headline-md text-[24px] font-semibold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-fixed">inventory_2</span> Recent Products
                </h2>
                <a href="{{ route('farmer.products.index') }}" class="text-primary-fixed text-sm hover:text-white transition-colors font-label-caps text-label-caps tracking-widest uppercase flex items-center gap-1">
                    View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($recentProducts as $product)
                <div class="flex items-center gap-4 py-4 border-b border-white/5 last:border-0 group hover:bg-white/5 px-2 -mx-2 rounded-xl transition-colors">
                    <div class="w-16 h-16 rounded-[16px] bg-surface-container-high overflow-hidden shrink-0 relative">
                        @if($product->primaryImage && $product->primaryImage->image_path !== 'products/placeholder.jpg')
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $product->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-500">{{ $product->category->icon ?? '🌾' }}</div>
                        @endif
                        <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-[16px]"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-body-md text-base font-medium text-white mb-0.5 truncate">{{ $product->title }}</div>
                        <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase flex gap-2">
                            <span>{{ $product->category->name ?? 'Uncategorized' }}</span>
                            <span class="text-white/20">•</span>
                            <span>{{ (int) $product->quantity }} {{ $product->unit }}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if($product->isBuyable())
                            <div class="font-price-display text-sm text-secondary-fixed">₹{{ number_format($product->price, 2) }}</div>
                            <div class="text-[10px] text-on-surface-variant uppercase tracking-widest">Buy Now</div>
                        @else
                            <span class="bg-tertiary/10 text-tertiary px-2 py-1 rounded-md text-[10px] uppercase tracking-widest border border-tertiary/20">Auction Only</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-4 opacity-50">inventory_2</span>
                    <p class="font-body-md">No products listed yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8 flex flex-col h-full">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-headline-md text-[24px] font-semibold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">local_shipping</span> Recent Orders
                </h2>
                <a href="{{ route('farmer.orders.index') }}" class="text-secondary-fixed text-sm hover:text-white transition-colors font-label-caps text-label-caps tracking-widest uppercase flex items-center gap-1">
                    View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($recentOrders as $order)
                <div class="py-4 border-b border-white/5 last:border-0 group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase flex items-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                            Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                        <span class="bg-{{ $order->status === 'pending' ? 'tertiary' : ($order->status === 'completed' ? 'primary-fixed' : 'surface-container-high') }}/10 border border-{{ $order->status === 'pending' ? 'tertiary' : ($order->status === 'completed' ? 'primary-fixed' : 'white') }}/30 text-{{ $order->status === 'pending' ? 'tertiary' : ($order->status === 'completed' ? 'primary-fixed' : 'on-surface') }} px-3 py-1 rounded-full font-label-caps text-[10px] tracking-widest uppercase shadow-[0_0_10px_rgba(0,0,0,0.1)]">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="font-body-md text-base text-white">{{ $order->consumer->name }}</div>
                        <div class="font-price-display text-sm text-secondary-fixed">₹{{ number_format($order->total_amount, 2) }}</div>
                    </div>
                    <div class="mt-2 text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $order->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-4 opacity-50">inbox</span>
                    <p class="font-body-md">No orders received yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
