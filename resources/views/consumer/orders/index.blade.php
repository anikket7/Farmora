@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
<div class="max-w-[1200px] mx-auto px-container-margin py-12 fade-in relative z-10">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                <span class="material-symbols-outlined text-secondary-fixed text-2xl">inventory_2</span>
            </div>
            <div>
                <h1 class="font-headline-md text-3xl md:text-[40px] font-semibold text-white tracking-tight">My Orders</h1>
                @if($orders->count() > 0)
                    <p class="font-body-md text-on-surface-variant text-sm mt-1">Tracking <span class="text-secondary-fixed font-bold">{{ $orders->total() }}</span> harvest deliveries</p>
                @endif
            </div>
        </div>
        @if($orders->count() > 0)
            <a href="{{ route('marketplace') }}" class="bg-secondary-fixed text-on-secondary-fixed px-6 py-3 rounded-full font-label-caps text-[11px] tracking-widest uppercase hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex items-center gap-2 hover:-translate-y-0.5 shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span> Order More
            </a>
        @endif
    </div>

    @if($orders->count() > 0)



        {{-- Orders List --}}
        <div class="flex flex-col gap-5">
            @foreach($orders as $order)
                @php
                    $statusConfig = [
                        'pending'          => ['icon' => 'hourglass_top', 'color' => 'text-secondary-fixed', 'bg' => 'bg-secondary-fixed/15 border-secondary-fixed/30', 'label' => 'Pending'],
                        'confirmed'        => ['icon' => 'check_circle', 'color' => 'text-secondary-fixed', 'bg' => 'bg-secondary-fixed/15 border-secondary-fixed/30', 'label' => 'Confirmed'],
                        'packed'           => ['icon' => 'inventory_2', 'color' => 'text-blue-400', 'bg' => 'bg-blue-400/15 border-blue-400/30', 'label' => 'Packed'],
                        'shipped'          => ['icon' => 'package_2', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-400/15 border-cyan-400/30', 'label' => 'Shipped'],
                        'out_for_delivery' => ['icon' => 'local_shipping', 'color' => 'text-purple-400', 'bg' => 'bg-purple-400/15 border-purple-400/30', 'label' => 'Out for Delivery'],
                        'delivered'        => ['icon' => 'task_alt', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-400/15 border-emerald-400/30', 'label' => 'Delivered'],
                        'cancelled'        => ['icon' => 'cancel', 'color' => 'text-red-400', 'bg' => 'bg-red-400/15 border-red-400/30', 'label' => 'Cancelled'],
                    ];
                    $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];

                    $steps = ['pending', 'confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
                    $currentStep = array_search($order->status, $steps);
                    if ($currentStep === false) $currentStep = -1;
                @endphp

                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[24px] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.37)] transition-all hover:border-secondary-fixed/20 group">

                    {{-- Order Header Strip --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-3 bg-surface-container-high/30 border-b border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $order->created_at->format('M j, Y') }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full border {{ $sc['bg'] }} font-label-caps text-[9px] tracking-widest uppercase {{ $sc['color'] }} flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[12px]">{{ $sc['icon'] }}</span> Status: {{ $sc['label'] }}
                        </span>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6 md:items-center">
                            {{-- Product Image --}}
                            <a href="{{ route('marketplace.show', $order->product) }}" class="w-full md:w-28 h-28 shrink-0 rounded-[16px] overflow-hidden bg-surface-container-high/50 flex items-center justify-center relative border border-white/5 hover:ring-2 hover:ring-secondary-fixed/50 transition-all">
                                @if($order->product->images->first() && $order->product->images->first()->image_path !== 'products/placeholder.jpg')
                                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl">{{ $order->product->category->icon ?? '🌾' }}</span>
                                @endif
                            </a>

                            {{-- Product Details --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('marketplace.show', $order->product) }}" class="font-headline-md text-[22px] font-semibold text-white mb-3 truncate group-hover:text-secondary-fixed transition-colors block">
                                    {{ $order->product->title }}
                                </a>

                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div class="bg-white/5 rounded-xl px-3 py-2">
                                        <div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mb-0.5">Farmer</div>
                                        <div class="text-white text-sm font-medium truncate">{{ $order->product->farmer->name }}</div>
                                    </div>
                                    <div class="bg-white/5 rounded-xl px-3 py-2">
                                        <div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mb-0.5">Quantity</div>
                                        <div class="text-white text-sm font-medium">{{ (int) $order->quantity }} {{ $order->product->unit }}</div>
                                    </div>
                                    <div class="bg-white/5 rounded-xl px-3 py-2">
                                        <div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mb-0.5">Payment</div>
                                        <div class="text-white text-sm font-medium">Cash on Delivery</div>
                                    </div>
                                    <div class="bg-white/5 rounded-xl px-3 py-2">
                                        <div class="font-label-caps text-[8px] text-on-surface-variant tracking-widest uppercase mb-0.5">Total Paid</div>
                                        <div class="text-secondary-fixed text-sm font-price-display drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($order->total_price, 2) }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Cancel Action --}}
                            @if(in_array($order->status, ['pending', 'confirmed', 'packed']))
                                <div class="shrink-0 w-full md:w-auto flex justify-start md:justify-end">
                                    <form method="POST" action="{{ route('consumer.orders.cancel', $order) }}" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                        @csrf
                                        <button type="submit" class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-5 py-2.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase border border-red-500/20 hover:border-red-500 transition-all shadow-[0_0_15px_rgba(239,68,68,0)] hover:shadow-[0_0_20px_rgba(239,68,68,0.3)] flex items-center justify-center gap-1.5 font-bold w-full md:w-auto">
                                            <span class="material-symbols-outlined text-[14px]">cancel</span> Cancel Order
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        {{-- Progress Tracker --}}
                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                            <div class="mt-5 pt-5 border-t border-white/5">
                                <div class="flex items-center justify-between relative">
                                    {{-- Progress Line --}}
                                    <div class="absolute top-[14px] left-[5%] right-[5%] h-[2px] bg-white/10 rounded-full"></div>
                                    @if($currentStep >= 0)
                                        <div class="absolute top-[14px] left-[5%] h-[2px] bg-secondary-fixed/60 rounded-full transition-all duration-700" style="width: {{ $currentStep > 0 ? min(($currentStep / 5) * 90, 90) : 0 }}%"></div>
                                    @endif

                                    @foreach(['Ordered', 'Confirmed', 'Packed', 'Shipped', 'In Transit', 'Delivered'] as $index => $stepLabel)
                                        <div class="flex flex-col items-center z-10 relative" style="width: 16.66%">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[14px] transition-all
                                                {{ $index <= $currentStep
                                                    ? 'bg-secondary-fixed text-on-secondary-fixed shadow-[0_0_12px_rgba(203,163,88,0.4)]'
                                                    : 'bg-surface-container-high border border-white/10 text-on-surface-variant' }}">
                                                <span class="material-symbols-outlined text-[14px]">
                                                    @if($index <= $currentStep) check @else {{ ['receipt_long', 'verified', 'inventory_2', 'package_2', 'local_shipping', 'home'][$index] }} @endif
                                                </span>
                                            </div>
                                            <span class="font-label-caps text-[8px] tracking-widest uppercase mt-2 {{ $index <= $currentStep ? 'text-secondary-fixed' : 'text-on-surface-variant/50' }}">{{ $stepLabel }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 pagination-wrapper">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[40px] p-16 text-center max-w-2xl mx-auto shadow-[0_8px_32px_rgba(0,0,0,0.37)] mt-12 flex flex-col items-center">
            <div class="w-24 h-24 rounded-full bg-surface-container-high/50 flex items-center justify-center mb-6 shadow-[inset_0_2px_10px_rgba(0,0,0,0.2)] border border-white/5">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant/50">inventory</span>
            </div>
            <h2 class="font-headline-md text-[28px] font-bold text-white mb-3">No Orders Yet</h2>
            <p class="font-body-md text-on-surface-variant mb-8 max-w-md">You haven't placed any orders. Start supporting local farmers by purchasing fresh produce directly from the source.</p>
            <a href="{{ route('marketplace') }}" class="bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-8 py-4 rounded-full font-label-caps text-[12px] tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.3)] hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] flex items-center justify-center gap-2 transform hover:-translate-y-1">
                <span class="material-symbols-outlined text-[18px]">storefront</span> Explore Marketplace
            </a>
        </div>
    @endif

</div>
@endsection
