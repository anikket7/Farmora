@extends('layouts.app')

@section('title', 'Your Harvest Cart')

@section('content')
    <div class="max-w-[1200px] mx-auto px-container-margin py-12 fade-in relative z-10">
        <div class="flex items-center gap-4 mb-10">
            <div
                class="w-14 h-14 rounded-full bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                <span class="material-symbols-outlined text-secondary-fixed text-2xl">shopping_cart</span>
            </div>
            <h1 class="font-headline-md text-3xl md:text-[40px] font-semibold text-white tracking-tight">Your Harvest Cart
            </h1>
        </div>

        @if(count($products) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 flex flex-col gap-4">
                    @foreach($products as $item)
                        <div
                            class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[24px] p-4 flex flex-col sm:flex-row items-start sm:items-center gap-5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] transition-all hover:border-white/10 group">
                            {{-- Image / Icon --}}
                            <a href="{{ route('marketplace.show', $item['product']) }}"
                                class="w-24 h-24 shrink-0 rounded-[20px] overflow-hidden bg-surface-container-high/50 flex items-center justify-center relative hover:ring-2 hover:ring-secondary-fixed/50 transition-all">
                                @if($item['product']->images->first() && $item['product']->images->first()->image_path !== 'products/placeholder.jpg')
                                    <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                        alt="{{ $item['product']->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl">{{ $item['product']->category->icon ?? '🌾' }}</span>
                                @endif
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-[20px] pointer-events-none">
                                </div>
                            </a>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('marketplace.show', $item['product']) }}"
                                    class="font-headline-md text-[20px] font-semibold text-white mb-1 truncate group-hover:text-secondary-fixed transition-colors block">{{ $item['product']->title }}</a>
                                <div
                                    class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase flex items-center gap-2">
                                    <span class="bg-white/5 px-2 py-1 rounded-md">{{ $item['product']->category->name }}</span>
                                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                    <span>by <strong class="text-white">{{ $item['product']->farmer->name }}</strong></span>
                                </div>
                                <div
                                    class="mt-3 text-secondary-fixed font-price-display text-base drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">
                                    ₹{{ number_format($item['product']->price, 2) }}<span
                                        class="text-on-surface-variant text-[11px] drop-shadow-none">/{{ $item['product']->unit }}</span></span>
                                </div>
                            </div>

                            {{-- Actions & Price --}}
                            <div
                                class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end mt-2 sm:mt-0 pt-4 sm:pt-0 border-t border-white/5 sm:border-t-0">
                                {{-- Quantity Update --}}
                                <div class="flex flex-col gap-1.5">
                                    <span
                                        class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase ml-1">Quantity</span>
                                    <form method="POST" action="{{ route('cart.update', $item['product']) }}"
                                        class="flex items-center gap-2 bg-surface-container-high border border-white/10 rounded-full px-1 py-1">
                                        @csrf @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" step="1"
                                            class="w-16 bg-transparent text-white font-body-md text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            onchange="this.form.submit()">
                                        <span
                                            class="text-on-surface-variant text-xs font-label-caps pr-3">{{ $item['product']->unit }}</span>
                                    </form>
                                </div>

                                {{-- Subtotal --}}
                                <div class="text-right shrink-0">
                                    <div class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase mb-1">
                                        Subtotal</div>
                                    <div class="font-price-display text-xl text-white">₹{{ number_format($item['subtotal'], 2) }}
                                    </div>
                                </div>

                                {{-- Remove --}}
                                <form method="POST" action="{{ route('cart.remove', $item['product']) }}" class="ml-2">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-[0_0_10px_rgba(239,68,68,0)] hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]"
                                        title="Remove Item">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div
                    class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[32px] p-8 shadow-[0_8px_32px_rgba(0,0,0,0.37)] sticky top-24">
                    <h3 class="font-headline-md text-[24px] font-semibold text-white mb-6">Order Summary</h3>

                    <div class="flex flex-col gap-4 font-body-md text-sm border-b border-white/5 pb-6 mb-6">
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Subtotal ({{ count($products) }} items)</span>
                            <span class="text-white">₹{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Platform Fee</span>
                            <span class="text-secondary-fixed">Free</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <span class="font-label-caps text-sm text-on-surface-variant tracking-widest uppercase mb-1">Total
                            Amount</span>
                        <span
                            class="font-price-display text-[32px] text-secondary-fixed drop-shadow-[0_0_15px_rgba(203,163,88,0.3)] leading-none">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="flex flex-col gap-4">
                        <a href="{{ route('checkout') }}"
                            class="w-full bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-8 py-4 rounded-full font-label-caps text-[12px] tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.3)] hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] transform hover:-translate-y-1 flex items-center justify-center gap-2 text-center font-bold">
                            Proceed to Checkout <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('marketplace') }}"
                            class="w-full bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-8 py-4 rounded-full font-label-caps text-[11px] tracking-widest uppercase transition-all flex items-center justify-center text-center">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div
                class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[40px] p-16 text-center max-w-2xl mx-auto shadow-[0_8px_32px_rgba(0,0,0,0.37)] mt-12 flex flex-col items-center">
                <div
                    class="w-24 h-24 rounded-full bg-surface-container-high/50 flex items-center justify-center mb-6 shadow-[inset_0_2px_10px_rgba(0,0,0,0.2)] border border-white/5">
                    <span
                        class="material-symbols-outlined text-[48px] text-on-surface-variant/50">production_quantity_limits</span>
                </div>
                <h2 class="font-headline-md text-[28px] font-bold text-white mb-3">Your Cart is Empty</h2>
                <p class="font-body-md text-on-surface-variant mb-8 max-w-md">Looks like you haven't added any harvest to your
                    cart yet. Discover fresh produce directly from farmers on the network.</p>
                <a href="{{ route('marketplace') }}"
                    class="bg-secondary-container/20 text-secondary-fixed hover:bg-secondary-fixed hover:text-on-secondary-fixed px-8 py-4 rounded-full font-label-caps text-[12px] tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.2)] hover:shadow-[0_0_30px_rgba(203,163,88,0.4)] border border-secondary-fixed/30 flex items-center justify-center gap-2 transform hover:-translate-y-1">
                    <span class="material-symbols-outlined text-[18px]">storefront</span> Explore Marketplace
                </a>
            </div>
        @endif
    </div>
@endsection