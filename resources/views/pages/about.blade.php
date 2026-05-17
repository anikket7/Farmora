@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<!-- Hero Section -->
<section class="relative pt-32 pb-16 px-container-margin w-screen -ml-[calc(50vw-50%)]">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,var(--tw-gradient-stops))] from-primary-fixed/10 via-transparent to-transparent opacity-60 pointer-events-none"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <h1 class="font-display-lg text-[48px] md:text-[64px] text-white font-semibold mb-6">
            Cultivating the <span class="text-primary-fixed italic font-light drop-shadow-[0_0_20px_rgba(6,205,172,0.3)]">Future</span>
        </h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed max-w-2xl mx-auto">
            Farmora was born from a simple belief: the distance between the earth and your plate should be measured in transparency, not middlemen.
        </p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[40px] p-2 overflow-hidden group">
            <div class="h-[400px] rounded-[32px] overflow-hidden relative">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1HC9JWwHWiN_PTYYm3x-gUGTg5vhmO5SWBDLFY2KcQjxrs4TSUXJP4cyYo1iTh50jzVbRDNyrtXp4e7-CbB94W8JX_A4QiNbGiFvFUd80onyMiBfXUJxY8xg-QNKNLSvWLQxEVDIFZDZcyg8IQDwYEoAQ5NBRrboFXu5QontbmBZ1VN8QKR-lLHyuobWH4rp8XjXUa3PGfenZDQvE0yDkk8s22MTxdHAsFa4ExGZzEjNfrO80damBGJD5gFK-MuHGzhdorOnPia4" alt="Vertical Farming" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80" />
                <div class="absolute inset-0 bg-linear-to-t from-background to-transparent opacity-50"></div>
            </div>
        </div>
        <div>
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-surface-container/80 backdrop-blur-md border border-white/10 text-white font-label-caps tracking-widest mb-6">
                <span class="material-symbols-outlined text-primary-fixed text-sm">public</span>
                OUR MISSION
            </div>
            <h2 class="font-headline-md text-[32px] text-white mb-6 leading-tight">Empowering Local Economies Through Global Technology</h2>
            <p class="font-body-md text-on-surface-variant leading-relaxed mb-6">
                We are building a decentralized agricultural marketplace that replaces opaque supply chains with real-time bidding, fair valuations, and direct-to-consumer delivery. By leveraging intelligent auction algorithms and strict organic verification, we ensure farmers get the margin they deserve while consumers receive peak-freshness produce.
            </p>
            <div class="flex items-center gap-6 mt-8">
                <div class="flex flex-col">
                    <span class="font-display-lg text-secondary-fixed text-[36px]">0%</span>
                    <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Hidden Fees</span>
                </div>
                <div class="w-px h-12 bg-white/10"></div>
                <div class="flex flex-col">
                    <span class="font-display-lg text-primary-fixed text-[36px]">100%</span>
                    <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Verified Farms</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto text-center w-full relative">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,var(--tw-gradient-stops))] from-secondary-fixed/5 via-transparent to-transparent opacity-40 pointer-events-none"></div>
    <h2 class="font-headline-md text-[32px] text-white mb-16 relative inline-block">
        Core Tenets
        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-12 h-1 bg-linear-to-r from-primary-fixed to-secondary-fixed rounded-full"></div>
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-surface-container-low/80 backdrop-blur-md border border-white/5 rounded-[32px] p-10 hover:border-white/20 transition-colors">
            <span class="material-symbols-outlined text-primary-fixed text-[48px] mb-6 block">nature_people</span>
            <h3 class="font-headline-md text-white text-[24px] mb-4">Sustainability</h3>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">We strictly onboard farms dedicated to regenerative agriculture, reducing carbon footprints by cutting out intermediate logistics.</p>
        </div>
        <div class="bg-surface-container-low/80 backdrop-blur-md border border-white/5 rounded-[32px] p-10 hover:border-white/20 transition-colors">
            <span class="material-symbols-outlined text-secondary-fixed text-[48px] mb-6 block">monitoring</span>
            <h3 class="font-headline-md text-white text-[24px] mb-4">Transparency</h3>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">Our live bidding engine ensures price discovery is openly visible to both the buyer and the seller, establishing true market value.</p>
        </div>
        <div class="bg-surface-container-low/80 backdrop-blur-md border border-white/5 rounded-[32px] p-10 hover:border-white/20 transition-colors">
            <span class="material-symbols-outlined text-tertiary text-[48px] mb-6 block">local_shipping</span>
            <h3 class="font-headline-md text-white text-[24px] mb-4">Velocity</h3>
            <p class="font-body-md text-sm text-on-surface-variant leading-relaxed">Produce isn't meant to sit in warehouses. The auction-to-dispatch pipeline guarantees harvest-to-table delivery in record time.</p>
        </div>
    </div>
</section>

<!-- Scale/Infrastructure -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto w-full border-t border-white/5">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
            <h2 class="font-headline-md text-[32px] text-white mb-6">Built for Continental Scale</h2>
            <p class="font-body-md text-on-surface-variant leading-relaxed mb-8">
                The Farmora infrastructure is designed to handle thousands of concurrent live auctions without lag. By utilizing decentralized edge nodes and real-time WebSocket communication, price discovery happens instantaneously across the globe.
            </p>
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-secondary-fixed text-2xl">speed</span>
                    <span class="font-body-md text-white">Sub-50ms Auction Bidding Latency</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-secondary-fixed text-2xl">verified_user</span>
                    <span class="font-body-md text-white">Military-Grade Transaction Security</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-secondary-fixed text-2xl">cloud_sync</span>
                    <span class="font-body-md text-white">100% Cloud-Native Uptime</span>
                </div>
            </div>
        </div>
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,var(--tw-gradient-stops))] from-primary-fixed/10 via-transparent to-transparent opacity-50"></div>
            <div class="relative z-10 flex flex-col gap-6">
                <div class="bg-surface-container/50 border border-white/10 rounded-2xl p-6">
                    <p class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2">Network Status</p>
                    <div class="flex items-center justify-between">
                        <span class="font-headline-md text-white text-xl">All Systems Operational</span>
                        <span class="w-3 h-3 rounded-full bg-secondary-fixed animate-pulse"></span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-surface-container/50 border border-white/10 rounded-2xl p-6 text-center">
                        <span class="font-display-lg text-white text-3xl block mb-1">14.2K</span>
                        <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Active Nodes</span>
                    </div>
                    <div class="bg-surface-container/50 border border-white/10 rounded-2xl p-6 text-center">
                        <span class="font-display-lg text-primary-fixed text-3xl block mb-1">99.9%</span>
                        <span class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Uptime SLA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto text-center w-full bg-surface-container-lowest border-y border-white/5 rounded-[40px] mb-16">
    <h2 class="font-headline-md text-[32px] text-white mb-16 relative inline-block">
        System Architects
        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-12 h-1 bg-linear-to-r from-primary-fixed to-secondary-fixed rounded-full"></div>
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-5xl mx-auto px-8">
        <div class="group cursor-pointer">
            <div class="w-24 h-24 mx-auto rounded-full bg-surface-container border border-white/10 mb-4 overflow-hidden group-hover:border-secondary-fixed/50 transition-colors">
                <img src="https://i.pravatar.cc/150?img=11" alt="CEO" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
            </div>
            <h4 class="font-headline-md text-white text-lg">Dr. Elena Rostova</h4>
            <p class="font-label-caps text-[10px] text-secondary-fixed tracking-widest uppercase mt-1">Chief Executive Officer</p>
        </div>
        <div class="group cursor-pointer">
            <div class="w-24 h-24 mx-auto rounded-full bg-surface-container border border-white/10 mb-4 overflow-hidden group-hover:border-secondary-fixed/50 transition-colors">
                <img src="https://i.pravatar.cc/150?img=33" alt="CTO" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
            </div>
            <h4 class="font-headline-md text-white text-lg">Marcus Chen</h4>
            <p class="font-label-caps text-[10px] text-secondary-fixed tracking-widest uppercase mt-1">Chief Technology Officer</p>
        </div>
        <div class="group cursor-pointer">
            <div class="w-24 h-24 mx-auto rounded-full bg-surface-container border border-white/10 mb-4 overflow-hidden group-hover:border-secondary-fixed/50 transition-colors">
                <img src="https://i.pravatar.cc/150?img=44" alt="Head of Ag" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
            </div>
            <h4 class="font-headline-md text-white text-lg">Sarah Jenkins</h4>
            <p class="font-label-caps text-[10px] text-secondary-fixed tracking-widest uppercase mt-1">VP of Agronomy</p>
        </div>
        <div class="group cursor-pointer">
            <div class="w-24 h-24 mx-auto rounded-full bg-surface-container border border-white/10 mb-4 overflow-hidden group-hover:border-secondary-fixed/50 transition-colors">
                <img src="https://i.pravatar.cc/150?img=60" alt="Head of Supply" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
            </div>
            <h4 class="font-headline-md text-white text-lg">David Okafor</h4>
            <p class="font-label-caps text-[10px] text-secondary-fixed tracking-widest uppercase mt-1">Head of Logistics</p>
        </div>
    </div>
</section>
@endsection
