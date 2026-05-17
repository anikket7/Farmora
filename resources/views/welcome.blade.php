@extends('layouts.app')

@section('title', 'The Future of Freshness')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[921px] flex items-center justify-center px-container-margin py-section-gap pt-32 -mt-24 w-screen -ml-[calc(50vw-50%)]">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img alt="Hero Background" class="w-full h-full object-cover opacity-60 scale-105" data-alt="A cinematic, high-tech indoor vertical farm environment at night. Rows of vibrant green leafy vegetables are illuminated by futuristic, glowing neon lime green grow lights. The atmosphere is sleek, damp, and highly advanced, embodying eco-futurism. Minimalist glass panels and clean metallic structures reflect the soft green glow, contrasting with deep, rich black shadows in the background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1HC9JWwHWiN_PTYYm3x-gUGTg5vhmO5SWBDLFY2KcQjxrs4TSUXJP4cyYo1iTh50jzVbRDNyrtXp4e7-CbB94W8JX_A4QiNbGiFvFUd80onyMiBfXUJxY8xg-QNKNLSvWLQxEVDIFZDZcyg8IQDwYEoAQ5NBRrboFXu5QontbmBZ1VN8QKR-lLHyuobWH4rp8XjXUa3PGfenZDQvE0yDkk8s22MTxdHAsFa4ExGZzEjNfrO80damBGJD5gFK-MuHGzhdorOnPia4"/>
        <div class="absolute inset-0 bg-linear-to-b from-background via-background/40 to-background"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,var(--tw-gradient-stops))] from-secondary-fixed/5 via-transparent to-transparent opacity-50"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto text-center flex flex-col items-center gap-8">
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-surface-container/80 backdrop-blur-md border border-secondary-fixed/20 text-secondary-fixed font-label-caps text-label-caps mb-4 shadow-[0_0_15px_rgba(203,163,88,0.15)]">
            <span class="material-symbols-outlined text-sm" data-icon="eco" data-weight="fill" style="font-variation-settings: 'FILL' 1;">eco</span>
            Precision Nature Suite 2026
        </div>
        <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-white font-semibold">
            The Future of <span class="text-secondary-fixed italic font-light drop-shadow-[0_0_20px_rgba(203,163,88,0.3)]">Freshness</span>
        </h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            Direct from the soil to your table. Experience the transparent, high-tech marketplace regenerating the earth through precision agriculture.
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 mt-12 justify-center items-center w-full">
            <a href="{{ route('marketplace') }}" class="bg-secondary-fixed text-surface-container-lowest font-label-caps text-[14px] tracking-widest px-10 py-4 rounded-full hover:shadow-[0_0_30px_rgba(203,163,88,0.5)] transition-all flex items-center gap-2 justify-center hover:-translate-y-1">
                <span class="material-symbols-outlined text-[20px]" data-icon="storefront">storefront</span>
                EXPLORE MARKETPLACE
            </a>
            @guest
            <a href="{{ route('register', ['role' => 'farmer']) }}" class="glass-panel text-white font-label-caps text-[14px] tracking-widest px-10 py-4 rounded-full hover:bg-white/10 transition-all border border-white/20 hover:border-secondary-fixed/50 bg-white/5 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.37)] flex items-center gap-2 justify-center hover:-translate-y-1">
                <span class="material-symbols-outlined text-[20px]" data-icon="agriculture">agriculture</span>
                JOIN AS FARMER
            </a>
            @else
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="glass-panel text-white font-label-caps text-[14px] tracking-widest px-10 py-4 rounded-full hover:bg-white/10 transition-all border border-white/20 hover:border-secondary-fixed/50 bg-white/5 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.37)] flex items-center gap-2 justify-center hover:-translate-y-1">
                <span class="material-symbols-outlined text-[20px]" data-icon="dashboard">dashboard</span>
                GO TO DASHBOARD
            </a>
            @elseif(auth()->user()->role === 'farmer')
            <a href="{{ route('farmer.dashboard') }}" class="glass-panel text-white font-label-caps text-[14px] tracking-widest px-10 py-4 rounded-full hover:bg-white/10 transition-all border border-white/20 hover:border-secondary-fixed/50 bg-white/5 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.37)] flex items-center gap-2 justify-center hover:-translate-y-1">
                <span class="material-symbols-outlined text-[20px]" data-icon="dashboard">dashboard</span>
                GO TO DASHBOARD
            </a>
            @else
            <a href="{{ route('consumer.orders') }}" class="glass-panel text-white font-label-caps text-[14px] tracking-widest px-10 py-4 rounded-full hover:bg-white/10 transition-all border border-white/20 hover:border-secondary-fixed/50 bg-white/5 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.37)] flex items-center gap-2 justify-center hover:-translate-y-1">
                <span class="material-symbols-outlined text-[20px]" data-icon="inventory_2">inventory_2</span>
                MY ORDERS
            </a>
            @endif
            @endguest
        </div>
    </div>
</section>

<!-- Features Bento Grid -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto w-full relative">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,var(--tw-gradient-stops))] from-primary-fixed/5 via-transparent to-transparent opacity-40 pointer-events-none"></div>
    <div class="mb-16 text-center max-w-2xl mx-auto">
        <h2 class="font-headline-md text-headline-md text-white mb-6">Ecosystem Advantages</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">A transparent, efficient, and organic supply chain powered by next-generation technology.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
        <!-- Feature 1 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-10 flex flex-col gap-8 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 hover:shadow-[inset_0_0_40px_rgba(203,163,88,0.05)]">
            <div class="absolute -right-8 -top-8 p-8 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110">
                <span class="material-symbols-outlined text-[180px] text-primary-fixed" data-icon="local_florist">local_florist</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-primary-container/40 flex items-center justify-center border border-primary-fixed/30 backdrop-blur-md shadow-[0_0_15px_rgba(6,205,172,0.15)]">
                <span class="material-symbols-outlined text-primary-fixed" data-icon="agriculture" data-weight="fill" style="font-variation-settings: 'FILL' 1;">agriculture</span>
            </div>
            <div class="z-10 mt-auto">
                <h3 class="font-headline-md text-white text-[28px] mb-4 group-hover:text-primary-fixed transition-colors">Direct from the Soil</h3>
                <p class="font-body-md text-on-surface-variant leading-relaxed">Zero middlemen. Connect directly with local farmers for the freshest produce, ensuring fair compensation and reduced transit times.</p>
            </div>
        </div>
        
        <!-- Feature 2 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-10 flex flex-col gap-8 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 hover:shadow-[inset_0_0_40px_rgba(203,163,88,0.05)]">
            <div class="absolute -right-8 -top-8 p-8 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110">
                <span class="material-symbols-outlined text-[180px] text-secondary-fixed" data-icon="gavel">gavel</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-secondary-container/10 flex items-center justify-center border border-secondary-fixed/30 backdrop-blur-md shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                <span class="material-symbols-outlined text-secondary-fixed" data-icon="payments" data-weight="fill" style="font-variation-settings: 'FILL' 1;">payments</span>
            </div>
            <div class="z-10 mt-auto">
                <h3 class="font-headline-md text-white text-[28px] mb-4 group-hover:text-secondary-fixed transition-colors">Bidding Engine</h3>
                <p class="font-body-md text-on-surface-variant leading-relaxed">Transparent pricing mechanisms. Participate in live auctions for bulk harvests, ensuring market-driven, equitable value for all.</p>
            </div>
        </div>
        
        <!-- Feature 3 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-10 flex flex-col gap-8 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-500 hover:shadow-[inset_0_0_40px_rgba(203,163,88,0.05)]">
            <div class="absolute -right-8 -top-8 p-8 opacity-5 group-hover:opacity-10 transition-opacity duration-500 transform group-hover:scale-110">
                <span class="material-symbols-outlined text-[180px] text-tertiary" data-icon="verified">verified</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-tertiary-container/30 flex items-center justify-center border border-tertiary/30 backdrop-blur-md shadow-[0_0_15px_rgba(255,181,150,0.15)]">
                <span class="material-symbols-outlined text-tertiary" data-icon="shield" data-weight="fill" style="font-variation-settings: 'FILL' 1;">shield</span>
            </div>
            <div class="z-10 mt-auto">
                <h3 class="font-headline-md text-white text-[28px] mb-4 group-hover:text-tertiary transition-colors">Verified Roots</h3>
                <p class="font-body-md text-on-surface-variant leading-relaxed">Every farm on our platform undergoes rigorous admin approval, ensuring adherence to strict sustainable and organic practices.</p>
            </div>
        </div>
    </div>
</section>

<!-- Live Auctions Horizontal Scroll -->
<section class="py-section-gap overflow-hidden bg-surface-container-lowest relative border-y border-white/5 w-screen -ml-[calc(50vw-50%)]">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,var(--tw-gradient-stops))] from-secondary-fixed/5 via-transparent to-transparent opacity-30 pointer-events-none"></div>
    <div class="px-container-margin max-w-[1440px] mx-auto mb-10 flex justify-between items-end relative z-10">
        <div>
            <div class="inline-flex items-center gap-3 text-secondary-fixed font-label-caps text-label-caps mb-4 bg-secondary-fixed/10 px-4 py-1.5 rounded-full border border-secondary-fixed/20">
                <span class="w-2 h-2 rounded-full bg-secondary-fixed animate-pulse shadow-[0_0_8px_rgba(203,163,88,0.8)]"></span>
                Live Now
            </div>
            <h2 class="font-headline-md text-headline-md text-white">Active Harvest Auctions</h2>
        </div>
        <a class="hidden sm:flex items-center gap-2 text-on-surface-variant hover:text-secondary-fixed transition-colors font-label-caps text-label-caps tracking-widest border border-white/10 px-6 py-2.5 rounded-full hover:border-secondary-fixed/50 bg-surface/50 backdrop-blur-sm" href="{{ route('marketplace', ['listing_type' => 'bid']) }}">
            View All <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
        </a>
    </div>
    
    <div class="flex overflow-x-auto gap-8 px-container-margin pb-12 snap-x snap-mandatory hide-scrollbar max-w-[1440px] mx-auto relative z-10" style="-ms-overflow-style: none; scrollbar-width: none;">
        <style>.hide-scrollbar::-webkit-scrollbar { display: none; }</style>
        
        <!-- Auction Card 1 -->
        <a href="{{ route('marketplace', ['listing_type' => 'bid']) }}" class="min-w-[340px] md:min-w-[420px] bg-surface-container/30 backdrop-blur-xl border border-secondary-fixed/40 rounded-DEFAULT overflow-hidden group hover:border-secondary-fixed hover:-translate-y-1 transition-all duration-300 flex flex-col relative shadow-[0_0_25px_rgba(203,163,88,0.08)] hover:shadow-[0_0_30px_rgba(203,163,88,0.15)] snap-start">
            <div class="absolute inset-0 bg-secondary-fixed/5 pointer-events-none group-hover:bg-secondary-fixed/10 transition-colors"></div>
            <div class="relative h-48 overflow-hidden">
                <img alt="Organic Heirloom Tomatoes" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxSLueUs8bIkyTYhT_15n_zNv_475T5B04hz58W0uvakrPl1c4F8SpzMvcGfETrI369gzygh3Ryq9ZqzHcuFp5z14b2Ai2Y8vdUInCD3mHOGyaPKfx6GIkz7cQTdm0NmsdWQ9KWzwNBRPJphxR3LBU8Oo93lYrh6i9i_jkG85i4bvVBy86wWSJtSDTIUY0s6mpJkNkMmNqTaLY16AYUUOvt0xw5ZTVvKNkgWiuWt3aycUfPN_NjgngvaOvcbKq3t4cl63CM2kEEoc"/>
                <div class="absolute inset-0 bg-linear-to-t from-surface-container/90 via-surface-container/20 to-transparent"></div>
                
                <div class="absolute top-4 left-4 bg-tertiary-container/80 backdrop-blur-md text-on-tertiary-container px-3 py-1 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-tertiary-container/50 shadow-[0_0_10px_rgba(251,159,117,0.3)]">
                    <span class="material-symbols-outlined text-[14px]">gavel</span> LIVE AUCTION
                </div>
                <div class="absolute top-4 right-4 bg-surface/80 backdrop-blur-md text-secondary-fixed px-3 py-1 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-secondary-fixed/40 shadow-[0_0_10px_rgba(203,163,88,0.2)]">
                    <span class="material-symbols-outlined text-[14px] animate-pulse">timer</span> Ends Today
                </div>
            </div>
            <div class="p-card-padding flex flex-col grow z-10 -mt-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-headline-md text-[20px] leading-tight font-semibold text-on-surface group-hover:text-secondary-fixed transition-colors">Heirloom Tomatoes</h3>
                </div>
                <div class="flex items-center gap-1 text-on-surface-variant mb-4">
                    <span class="material-symbols-outlined text-[16px] text-secondary-fixed">verified</span>
                    <span class="text-sm line-clamp-1">Valley Grove Farms • 50kg Lot</span>
                </div>
                <div class="mt-auto flex items-end justify-between">
                    <div>
                        <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Current Bid</p>
                        <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹145.00</p>
                    </div>
                    <div class="bg-secondary-fixed text-on-secondary-fixed px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all transform group-hover:scale-105">
                        Bid Now
                    </div>
                </div>
            </div>
        </a>

        <!-- Auction Card 2 -->
        <a href="{{ route('marketplace', ['listing_type' => 'bid']) }}" class="min-w-[340px] md:min-w-[420px] bg-surface-container/30 backdrop-blur-xl border border-secondary-fixed/40 rounded-DEFAULT overflow-hidden group hover:border-secondary-fixed hover:-translate-y-1 transition-all duration-300 flex flex-col relative shadow-[0_0_25px_rgba(203,163,88,0.08)] hover:shadow-[0_0_30px_rgba(203,163,88,0.15)] snap-start">
            <div class="absolute inset-0 bg-secondary-fixed/5 pointer-events-none group-hover:bg-secondary-fixed/10 transition-colors"></div>
            <div class="relative h-48 overflow-hidden">
                <img alt="Hydroponic Microgreens" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMxs70-KkvPQUQece3WIE1fo3QI7EiniUo-zij8mHTvakeTKnM6Gmxv8gdUGo7uKo9-1owhnAxvwHenFet-1sHv7Z6wQN22WzbwQ5YXANwcvkbF8SL-f96lea8wzaM5Tq3HkHg64n4mjY22Cgp3UFWea1cvX2oGvHQ1thv5BVhzLuU4SCvPS-Hg1UpZoOUwQYKpNDjAxYy4yPwbHKalcInRGEgr5_2ci8yIFNlCuTV7kp9e3jqwZpKWCCW_tjp1a-2ftiRS8cs_9I"/>
                <div class="absolute inset-0 bg-linear-to-t from-surface-container/90 via-surface-container/20 to-transparent"></div>
                
                <div class="absolute top-4 left-4 bg-tertiary-container/80 backdrop-blur-md text-on-tertiary-container px-3 py-1 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-tertiary-container/50 shadow-[0_0_10px_rgba(251,159,117,0.3)]">
                    <span class="material-symbols-outlined text-[14px]">gavel</span> LIVE AUCTION
                </div>
                <div class="absolute top-4 right-4 bg-surface/80 backdrop-blur-md text-secondary-fixed px-3 py-1 rounded-full font-label-caps text-label-caps flex items-center gap-1 border border-secondary-fixed/40 shadow-[0_0_10px_rgba(203,163,88,0.2)]">
                    <span class="material-symbols-outlined text-[14px] animate-pulse">timer</span> Ends Tomorrow
                </div>
            </div>
            <div class="p-card-padding flex flex-col grow z-10 -mt-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-headline-md text-[20px] leading-tight font-semibold text-on-surface group-hover:text-secondary-fixed transition-colors">Premium Microgreens</h3>
                </div>
                <div class="flex items-center gap-1 text-on-surface-variant mb-4">
                    <span class="material-symbols-outlined text-[16px] text-secondary-fixed">verified</span>
                    <span class="text-sm line-clamp-1">AeroFarms Tech • 10kg Lot</span>
                </div>
                <div class="mt-auto flex items-end justify-between">
                    <div>
                        <p class="text-[10px] text-on-surface-variant mb-1 uppercase tracking-widest font-semibold">Current Bid</p>
                        <p class="font-price-display text-price-display text-secondary-fixed drop-shadow-[0_0_8px_rgba(203,163,88,0.3)]">₹85.50</p>
                    </div>
                    <div class="bg-secondary-fixed text-on-secondary-fixed px-5 py-2.5 rounded-full font-label-caps text-label-caps transition-all transform group-hover:scale-105">
                        Bid Now
                    </div>
                </div>
            </div>
        </a>
    </div>
</section>

<!-- How It Works Section -->
<section class="px-container-margin py-section-gap max-w-[1440px] mx-auto w-full relative">
    <div class="mb-16 text-center max-w-2xl mx-auto">
        <h2 class="font-headline-md text-headline-md text-white mb-6">How Farmora Works</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">A seamless journey from seed to market, designed for transparency and fairness.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
        <!-- Step 1 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 flex flex-col items-center text-center relative overflow-hidden group">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center border border-white/10 mb-6 text-2xl font-display-lg text-white group-hover:bg-primary-fixed/20 group-hover:text-primary-fixed group-hover:border-primary-fixed/50 transition-all duration-300">1</div>
            <h3 class="font-headline-md text-white text-[20px] mb-3">Farmers List Produce</h3>
            <p class="font-body-md text-sm text-on-surface-variant">Verified farmers upload their freshly harvested or upcoming batches to the marketplace.</p>
        </div>
        <!-- Step 2 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 flex flex-col items-center text-center relative overflow-hidden group">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center border border-white/10 mb-6 text-2xl font-display-lg text-white group-hover:bg-secondary-fixed/20 group-hover:text-secondary-fixed group-hover:border-secondary-fixed/50 transition-all duration-300">2</div>
            <h3 class="font-headline-md text-white text-[20px] mb-3">Buyers Browse & Bid</h3>
            <p class="font-body-md text-sm text-on-surface-variant">Consumers and businesses browse organic produce and place bids in live transparent auctions.</p>
        </div>
        <!-- Step 3 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 flex flex-col items-center text-center relative overflow-hidden group">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center border border-white/10 mb-6 text-2xl font-display-lg text-white group-hover:bg-tertiary/20 group-hover:text-tertiary group-hover:border-tertiary/50 transition-all duration-300">3</div>
            <h3 class="font-headline-md text-white text-[20px] mb-3">Auctions Conclude</h3>
            <p class="font-body-md text-sm text-on-surface-variant">The highest bidder wins the lot, ensuring the farmer gets the best, market-driven price.</p>
        </div>
        <!-- Step 4 -->
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 flex flex-col items-center text-center relative overflow-hidden group">
            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center border border-white/10 mb-6 text-2xl font-display-lg text-white group-hover:bg-primary-fixed/20 group-hover:text-primary-fixed group-hover:border-primary-fixed/50 transition-all duration-300">4</div>
            <h3 class="font-headline-md text-white text-[20px] mb-3">Direct Delivery</h3>
            <p class="font-body-md text-sm text-on-surface-variant">Produce is shipped directly from the farm to the buyer, eliminating storage delays.</p>
        </div>
    </div>
</section>

@endsection

