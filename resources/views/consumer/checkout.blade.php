@extends('layouts.app')
@section('title', 'Secure Checkout')
@section('content')
<div class="max-w-[1200px] mx-auto px-container-margin py-12 fade-in relative z-10">
    <div class="flex items-center gap-4 mb-10">
        <div class="w-14 h-14 rounded-full bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
            <span class="material-symbols-outlined text-secondary-fixed text-2xl">lock</span>
        </div>
        <h1 class="font-headline-md text-3xl md:text-[40px] font-semibold text-white tracking-tight">Secure Checkout</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
        {{-- Left: Delivery Form --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[32px] p-8 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
            <h2 class="font-headline-md text-[24px] font-semibold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary-fixed">local_shipping</span> Delivery Details
            </h2>
            <form method="POST" action="{{ route('orders.store') }}" id="checkout-form">
                @csrf
                <div class="mb-5">
                    <label for="delivery_address" class="font-label-caps text-[11px] tracking-widest text-on-surface-variant uppercase mb-2 block">Delivery Address</label>
                    <textarea id="delivery_address" name="delivery_address" class="w-full bg-surface-container-high/50 border border-white/10 rounded-2xl px-5 py-4 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors resize-none placeholder:text-on-surface-variant/50" rows="4" required placeholder="Enter your full delivery address...">{{ old('delivery_address', $user->consumerProfile?->delivery_address ?? $user->location) }}</textarea>
                    @error('delivery_address')<p class="text-error text-xs mt-2 font-body-md">{{ $message }}</p>@enderror
                </div>
                
                <div class="mb-6">
                    <label for="contact_phone" class="font-label-caps text-[11px] tracking-widest text-on-surface-variant uppercase mb-2 block">Contact Phone</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[18px]">phone</span>
                        <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $user->phone) }}" class="w-full bg-surface-container-high/50 border border-white/10 rounded-full pl-12 pr-5 py-4 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50" required placeholder="Mobile Number">
                    </div>
                    @error('contact_phone')<p class="text-error text-xs mt-2 font-body-md">{{ $message }}</p>@enderror
                </div>

                <div class="mb-8">
                    <label class="font-label-caps text-[11px] tracking-widest text-on-surface-variant uppercase mb-2 block">Payment Method</label>
                    <label class="flex items-center gap-4 bg-surface-container-high/50 border border-secondary-fixed/30 rounded-2xl p-4 cursor-pointer hover:bg-surface-container-high transition-colors shadow-[0_0_15px_rgba(203,163,88,0.1)]">
                        <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 accent-secondary-fixed bg-surface-container-highest border-white/20">
                        <div class="flex flex-col">
                            <span class="font-headline-md text-white font-medium flex items-center gap-2">
                                Cash on Delivery <span class="material-symbols-outlined text-secondary-fixed text-[18px]">payments</span>
                            </span>
                            <span class="text-xs text-on-surface-variant font-body-md">Pay when you receive your harvest</span>
                        </div>
                    </label>
                </div>
            </form>
        </div>

        {{-- Right: Order Summary --}}
        <div class="flex flex-col gap-6">
            <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 rounded-[32px] p-8 shadow-[0_8px_32px_rgba(0,0,0,0.37)]">
                <h2 class="font-headline-md text-[24px] font-semibold text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">receipt_long</span> Order Summary
                </h2>
                
                <div class="flex flex-col gap-4 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    <style>
                        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); border-radius: 4px; }
                        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
                        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
                    </style>
                    @foreach($items as $item)
                    <div class="flex items-center gap-4 py-3 border-b border-white/5 last:border-0 last:pb-0">
                        <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden bg-surface-container-high/50 flex items-center justify-center relative border border-white/5">
                            @if($item['product']->images->first() && $item['product']->images->first()->image_path !== 'products/placeholder.jpg')
                                <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}" alt="{{ $item['product']->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl">{{ $item['product']->category->icon ?? '🌾' }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-body-md text-white font-medium truncate">{{ $item['product']->title }}</h4>
                            <div class="text-xs text-on-surface-variant font-body-md mt-1">{{ (int) $item['quantity'] }} {{ $item['product']->unit }} × ₹{{ number_format($item['product']->price, 2) }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-price-display text-white text-lg">₹{{ number_format($item['subtotal'], 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-white/5 pt-6 mt-2">
                    <div class="flex justify-between items-center text-on-surface-variant mb-4 text-sm font-body-md">
                        <span>Platform Fee</span>
                        <span class="text-secondary-fixed">Free</span>
                    </div>
                    <div class="flex justify-between items-end mb-8">
                        <span class="font-label-caps text-sm text-on-surface-variant tracking-widest uppercase mb-1">Total to Pay</span>
                        <span class="font-price-display text-[32px] text-secondary-fixed drop-shadow-[0_0_15px_rgba(203,163,88,0.3)] leading-none">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" form="checkout-form" class="w-full bg-secondary-fixed text-on-secondary-fixed hover:bg-white hover:text-black px-8 py-4 rounded-full font-label-caps text-[12px] tracking-widest uppercase transition-all shadow-[0_0_20px_rgba(203,163,88,0.3)] hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] transform hover:-translate-y-1 flex items-center justify-center gap-2 text-center font-bold">
                        Confirm & Place Order <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </button>
                    <div class="text-center mt-4 text-[10px] text-on-surface-variant/50 font-label-caps tracking-widest uppercase">
                        By placing this order, you agree to Farmora's Terms of Service
                    </div>
                </div>
            </div>
            
            <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-white font-label-caps text-[11px] tracking-widest uppercase transition-colors self-center">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Return to Cart
            </a>
        </div>
    </div>
</div>
@endsection
