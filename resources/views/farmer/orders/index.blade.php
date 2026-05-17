@extends('layouts.farmer')
@section('title', 'Orders')
@section('page-title', 'Order Management')
@section('content')
<div class="fade-in relative z-10 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-headline-md text-2xl font-bold text-white mb-1">Recent Orders</h2>
            <p class="font-body-md text-sm text-on-surface-variant">Manage and update customer orders.</p>
        </div>
        @if($orders->count() > 0)
            <div class="bg-secondary-fixed/10 border border-secondary-fixed/20 rounded-2xl px-5 py-2.5 flex items-center gap-3 shadow-[0_0_15px_rgba(203,163,88,0.1)]">
                <span class="material-symbols-outlined text-secondary-fixed">monitoring</span>
                <span class="font-label-caps text-xs tracking-widest uppercase text-white">{{ $orders->total() }} Total</span>
            </div>
        @endif
    </div>

    @if($orders->count() > 0)



        {{-- Orders List --}}
        <div class="flex flex-col gap-4">
            @foreach($orders as $order)
                @php
                    $statusConfig = [
                        'pending'          => ['icon' => 'hourglass_top', 'color' => 'text-secondary-fixed', 'bg' => 'bg-secondary-fixed/15 border-secondary-fixed/30', 'bar' => 'bg-secondary-fixed'],
                        'confirmed'        => ['icon' => 'check_circle', 'color' => 'text-secondary-fixed', 'bg' => 'bg-secondary-fixed/15 border-secondary-fixed/30', 'bar' => 'bg-secondary-fixed'],
                        'packed'           => ['icon' => 'inventory_2', 'color' => 'text-blue-400', 'bg' => 'bg-blue-400/15 border-blue-400/30', 'bar' => 'bg-blue-400'],
                        'shipped'          => ['icon' => 'package_2', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-400/15 border-cyan-400/30', 'bar' => 'bg-cyan-400'],
                        'out_for_delivery' => ['icon' => 'local_shipping', 'color' => 'text-purple-400', 'bg' => 'bg-purple-400/15 border-purple-400/30', 'bar' => 'bg-purple-400'],
                        'delivered'        => ['icon' => 'task_alt', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-400/15 border-emerald-400/30', 'bar' => 'bg-emerald-400'],
                        'cancelled'        => ['icon' => 'cancel', 'color' => 'text-red-400', 'bg' => 'bg-red-400/15 border-red-400/30', 'bar' => 'bg-red-400'],
                    ];
                    $sc = $statusConfig[$order->status] ?? $statusConfig['pending'];

                    $steps = ['pending', 'confirmed', 'packed', 'shipped', 'out_for_delivery', 'delivered'];
                    $currentStep = array_search($order->status, $steps);
                    if ($currentStep === false) $currentStep = -1;
                @endphp

                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[20px] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.37)] transition-all hover:border-white/10 group">

                    {{-- Order Header --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 bg-surface-container-high/30 border-b border-white/5">
                        <div class="flex items-center gap-3">
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                            <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $order->created_at->format('M j, Y - g:i A') }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full border {{ $sc['bg'] }} font-label-caps text-[9px] tracking-widest uppercase {{ $sc['color'] }} flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[12px]">{{ $sc['icon'] }}</span> Status: {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row gap-5 lg:items-center">
                            {{-- Product Image --}}
                            <div class="w-20 h-20 shrink-0 rounded-[14px] overflow-hidden bg-surface-container-high/50 flex items-center justify-center border border-white/5">
                                @if($order->product->images->first() && $order->product->images->first()->image_path !== 'products/placeholder.jpg')
                                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl">{{ $order->product->category->icon ?? '📦' }}</span>
                                @endif
                            </div>

                            {{-- Product & Customer Info --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-headline-md text-lg font-semibold text-white truncate mb-2 group-hover:text-secondary-fixed transition-colors">{{ $order->product->title }}</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <div class="bg-white/5 rounded-lg px-3 py-1.5">
                                        <div class="font-label-caps text-[7px] text-on-surface-variant tracking-widest uppercase">Customer</div>
                                        <div class="text-white text-sm font-medium truncate flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px] text-secondary-fixed">person</span> {{ $order->consumer->name }}
                                        </div>
                                    </div>
                                    <div class="bg-white/5 rounded-lg px-3 py-1.5">
                                        <div class="font-label-caps text-[7px] text-on-surface-variant tracking-widest uppercase">Quantity</div>
                                        <div class="text-white text-sm font-medium">{{ (int) $order->quantity }} {{ $order->product->unit }}</div>
                                    </div>
                                    <div class="bg-white/5 rounded-lg px-3 py-1.5">
                                        <div class="font-label-caps text-[7px] text-on-surface-variant tracking-widest uppercase">Total</div>
                                        <div class="text-secondary-fixed text-sm font-price-display drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹{{ number_format($order->total_price, 2) }}</div>
                                    </div>
                                    <div class="bg-white/5 rounded-lg px-3 py-1.5">
                                        <div class="font-label-caps text-[7px] text-on-surface-variant tracking-widest uppercase">Delivery</div>
                                        <div class="text-on-surface-variant text-xs truncate flex items-center gap-1" title="{{ $order->delivery_address }}">
                                            <span class="material-symbols-outlined text-[12px]">location_on</span> {{ Str::limit($order->delivery_address, 20) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Update --}}
                            <div class="shrink-0 flex flex-col items-end gap-2 pt-4 lg:pt-0 border-t lg:border-t-0 border-white/5">
                                @if(!in_array($order->status, ['delivered', 'cancelled']))
                                    <form method="POST" action="{{ route('farmer.orders.status', $order) }}" class="relative w-full lg:w-44">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="w-full appearance-none bg-secondary-fixed/10 border border-secondary-fixed/30 rounded-xl px-4 py-2.5 pr-8 font-label-caps text-[10px] text-secondary-fixed focus:outline-none focus:border-secondary-fixed transition-colors cursor-pointer uppercase tracking-wider" style="color-scheme: dark;">
                                            <option value="" disabled selected style="color:#cbd3a8; background:#121c24;">Update Status</option>
                                            @foreach(['confirmed' => 'Confirmed', 'packed' => 'Packed', 'shipped' => 'Shipped', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $val => $label)
                                                <option value="{{ $val }}" @if($order->status === $val) disabled @endif style="color:#ffffff; background:#121c24;">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary-fixed text-[16px] pointer-events-none">expand_more</span>
                                    </form>
                                @else
                                    <span class="px-4 py-2.5 rounded-xl {{ $order->status === 'delivered' ? 'bg-emerald-400/10 text-emerald-400 border border-emerald-400/20' : 'bg-red-400/10 text-red-400 border border-red-400/20' }} font-label-caps text-[10px] tracking-widest uppercase flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">{{ $order->status === 'delivered' ? 'task_alt' : 'cancel' }}</span>
                                        {{ $order->status === 'delivered' ? 'Completed' : 'Cancelled' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Progress Tracker --}}
                        @if(!in_array($order->status, ['delivered', 'cancelled']))
                            <div class="mt-4 pt-4 border-t border-white/5">
                                <div class="flex items-center justify-between relative">
                                    <div class="absolute top-[12px] left-[5%] right-[5%] h-[2px] bg-white/10 rounded-full"></div>
                                    @if($currentStep >= 0)
                                        <div class="absolute top-[12px] left-[5%] h-[2px] bg-secondary-fixed/60 rounded-full transition-all duration-700" style="width: {{ $currentStep > 0 ? min(($currentStep / 5) * 90, 90) : 0 }}%"></div>
                                    @endif

                                    @foreach(['Ordered', 'Confirmed', 'Packed', 'Shipped', 'In Transit', 'Delivered'] as $index => $stepLabel)
                                        <div class="flex flex-col items-center z-10 relative" style="width: 16.66%">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center transition-all
                                                {{ $index <= $currentStep
                                                    ? 'bg-secondary-fixed text-on-secondary-fixed shadow-[0_0_10px_rgba(203,163,88,0.4)]'
                                                    : 'bg-surface-container-high border border-white/10 text-on-surface-variant' }}">
                                                <span class="material-symbols-outlined text-[12px]">
                                                    @if($index <= $currentStep) check @else {{ ['receipt_long', 'verified', 'inventory_2', 'package_2', 'local_shipping', 'home'][$index] }} @endif
                                                </span>
                                            </div>
                                            <span class="font-label-caps text-[7px] tracking-widest uppercase mt-1.5 {{ $index <= $currentStep ? 'text-secondary-fixed' : 'text-on-surface-variant/50' }}">{{ $stepLabel }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 pagination-wrapper">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-3xl p-16 text-center shadow-[0_8px_32px_rgba(0,0,0,0.37)] mt-12 flex flex-col items-center">
            <div class="w-20 h-20 rounded-full bg-surface-container-high/50 flex items-center justify-center mb-6 border border-white/5">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant/50">inventory_2</span>
            </div>
            <h2 class="font-headline-md text-[24px] font-bold text-white mb-2">No Orders Received</h2>
            <p class="font-body-md text-on-surface-variant max-w-md">You haven't received any orders yet. Ensure your products are well-described and competitively priced.</p>
        </div>
    @endif
</div>
@endsection
