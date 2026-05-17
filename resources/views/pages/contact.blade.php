@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-24 px-container-margin w-screen -ml-[calc(50vw-50%)] overflow-hidden">
    <div class="absolute inset-0 z-0">
        <!-- Abstract map-like background -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute inset-0 bg-linear-to-b from-background via-background/80 to-background"></div>
        <div class="absolute right-[-10%] top-[-20%] w-[50vw] h-[50vw] bg-secondary-fixed/5 rounded-full blur-[120px] pointer-events-none"></div>
    </div>
    
    <div class="max-w-7xl mx-auto relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-surface-container/80 backdrop-blur-md border border-secondary-fixed/20 text-secondary-fixed font-label-caps text-label-caps mb-6 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                <span class="material-symbols-outlined text-sm">support_agent</span>
                24/7 SUPPORT NETWORK
            </div>
            <h1 class="font-display-lg text-[48px] md:text-[64px] text-white font-semibold mb-6 leading-tight">
                Connect with the <span class="text-secondary-fixed italic font-light drop-shadow-[0_0_20px_rgba(203,163,88,0.3)]">Source</span>
            </h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed max-w-xl mb-10">
                Whether you're looking to digitize your harvest or secure peak-freshness produce for your business, our network engineers and support staff are standing by.
            </p>
            
            <div class="flex flex-col gap-8">
                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 backdrop-blur-md shadow-[0_0_15px_rgba(0,0,0,0.2)]">
                        <span class="material-symbols-outlined text-white text-[24px]">location_on</span>
                    </div>
                    <div>
                        <h4 class="font-label-caps text-[12px] text-on-surface-variant tracking-widest uppercase mb-2">Global Headquarters</h4>
                        <p class="font-body-md text-white text-lg">124 Eco-Tech Boulevard<br>New Earth City, NE 10001</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-secondary-container/10 flex items-center justify-center border border-secondary-fixed/20 backdrop-blur-md shadow-[0_0_15px_rgba(203,163,88,0.1)]">
                        <span class="material-symbols-outlined text-secondary-fixed text-[24px]">mail</span>
                    </div>
                    <div>
                        <h4 class="font-label-caps text-[12px] text-on-surface-variant tracking-widest uppercase mb-2">Digital Channels</h4>
                        <a href="mailto:support@farmora.io" class="font-body-md text-white text-lg hover:text-secondary-fixed transition-colors block">support@farmora.io</a>
                        <a href="mailto:partners@farmora.io" class="font-body-md text-white text-lg hover:text-secondary-fixed transition-colors block">partners@farmora.io</a>
                    </div>
                </div>
                
                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-primary-container/10 flex items-center justify-center border border-primary-fixed/20 backdrop-blur-md shadow-[0_0_15px_rgba(6,205,172,0.1)]">
                        <span class="material-symbols-outlined text-primary-fixed text-[24px]">call</span>
                    </div>
                    <div>
                        <h4 class="font-label-caps text-[12px] text-on-surface-variant tracking-widest uppercase mb-2">Direct Communications</h4>
                        <p class="font-body-md text-white text-lg">+1 (800) 555-FARM<br><span class="text-on-surface-variant text-sm mt-1 block">Mon-Fri, 9:00 AM - 6:00 PM EST</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Form Form -->
        <div class="relative">
            <div class="absolute inset-0 bg-secondary-fixed/5 rounded-[40px] blur-xl transform translate-y-4"></div>
            <div class="bg-surface-container-low/90 backdrop-blur-2xl border border-white/10 shadow-[0_20px_40px_rgba(0,0,0,0.4)] rounded-[40px] p-8 md:p-12 relative z-10 overflow-hidden">
                <!-- Decorative top bar -->
                <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-primary-fixed via-secondary-fixed to-tertiary"></div>
                
                <h3 class="font-headline-md text-[28px] text-white mb-2">Initialize Transmission</h3>
                <p class="font-body-md text-sm text-on-surface-variant mb-10">Our routing algorithm will connect you with the right specialist.</p>
                
                <form action="#" method="POST" class="flex flex-col gap-6" onsubmit="event.preventDefault(); alert('Message feature coming soon!');">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2 group">
                            <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase group-focus-within:text-secondary-fixed transition-colors">First Name</label>
                            <input type="text" class="bg-surface/50 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-secondary-fixed focus:bg-surface/80 transition-all w-full shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]" placeholder="John" required>
                        </div>
                        <div class="flex flex-col gap-2 group">
                            <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase group-focus-within:text-secondary-fixed transition-colors">Last Name</label>
                            <input type="text" class="bg-surface/50 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-secondary-fixed focus:bg-surface/80 transition-all w-full shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]" placeholder="Doe" required>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2 group">
                        <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase group-focus-within:text-secondary-fixed transition-colors">Email Address</label>
                        <input type="email" class="bg-surface/50 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-secondary-fixed focus:bg-surface/80 transition-all w-full shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]" placeholder="transmission@example.com" required>
                    </div>
                    
                    <div class="flex flex-col gap-2 group">
                        <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase group-focus-within:text-secondary-fixed transition-colors">Department Routing</label>
                        <div class="relative">
                            <select class="bg-surface/50 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-secondary-fixed focus:bg-surface/80 transition-all w-full appearance-none shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]">
                                <option value="general" class="bg-surface text-white">General Inquiry</option>
                                <option value="farmer" class="bg-surface text-white">Farmer Onboarding & Verification</option>
                                <option value="buyer" class="bg-surface text-white">Enterprise Purchasing</option>
                                <option value="tech" class="bg-surface text-white">Platform Support</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2 group">
                        <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase group-focus-within:text-secondary-fixed transition-colors">Message Payload</label>
                        <textarea rows="4" class="bg-surface/50 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-secondary-fixed focus:bg-surface/80 transition-all w-full resize-none shadow-[inset_0_2px_4px_rgba(0,0,0,0.1)]" placeholder="State your inquiry..." required></textarea>
                    </div>
                    
                    <button type="submit" class="bg-secondary-fixed text-surface-container-lowest font-label-caps text-[14px] tracking-widest px-8 py-5 rounded-2xl hover:shadow-[0_0_30px_rgba(203,163,88,0.4)] transition-all flex items-center justify-center gap-2 mt-2 w-full hover:-translate-y-1">
                        TRANSMIT PROTOCOL
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto w-full border-t border-white/5">
    <div class="mb-16 text-center max-w-2xl mx-auto">
        <h2 class="font-headline-md text-headline-md text-white mb-6">Frequently Asked Questions</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Quick answers to standard operational procedures.</p>
    </div>
    
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 hover:bg-surface-container/40 transition-colors">
            <h4 class="font-headline-md text-lg text-white mb-3">How do I verify my farm?</h4>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">Once you register as a farmer, our admin team will review your application. You may be asked to provide proof of sustainable practices before your account is fully activated.</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 hover:bg-surface-container/40 transition-colors">
            <h4 class="font-headline-md text-lg text-white mb-3">How does the bidding engine work?</h4>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">Farmers set a starting price and a time limit for a lot of produce. Buyers place live bids. When the timer expires, the highest bidder secures the lot instantly.</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 hover:bg-surface-container/40 transition-colors">
            <h4 class="font-headline-md text-lg text-white mb-3">Is delivery handled by Farmora?</h4>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">Farmora operates as a decentralized routing hub. While we facilitate the transaction and track the order status, fulfillment is directly managed between the farmer and the buyer.</p>
        </div>
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[24px] p-8 hover:bg-surface-container/40 transition-colors">
            <h4 class="font-headline-md text-lg text-white mb-3">What are the platform fees?</h4>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">We charge a flat, transparent 2.5% network utilization fee on successful bids, which is drastically lower than the 15-30% taken by traditional wholesale middlemen.</p>
        </div>
    </div>
</section>
@endsection
