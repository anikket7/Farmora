<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Farmora — The Digital Farmer's Market. Fresh produce directly from local farms through live bidding and direct purchases.">
    <title>@yield('title', 'Farmora') — Digital Farmer's Market</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&family=Literata:opsz,wght@7..72,500;7..72,600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface text-on-surface font-body-md min-h-screen antialiased flex flex-col relative overflow-x-hidden">
    <!-- Cinematic Background Orbs -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-primary-fixed/5 rounded-full blur-[120px]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] bg-secondary-fixed/5 rounded-full blur-[120px]">
        </div>
        <div
            class="absolute top-[40%] left-[50%] w-[30vw] h-[30vw] bg-tertiary/5 rounded-full blur-[100px] transform -translate-x-1/2">
        </div>
    </div>
    <div class="noise-bg z-0"></div>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <div class="toast toast-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast toast-error">✕ {{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="toast toast-warning" style="background: var(--color-tertiary)">⚠ {{ session('warning') }}</div>
    @endif

    {{-- Navbar --}}
    <nav
        class="fixed top-0 w-full z-50 bg-background/80 backdrop-blur-2xl border-b border-white/5 transition-all duration-300">
        <div class="flex justify-between items-center px-container-margin py-4 w-full max-w-[1440px] mx-auto">
            {{-- Brand --}}
            <a href="{{ route('home') }}"
                class="font-headline-md text-headline-md font-bold text-secondary-fixed tracking-tight hover:opacity-80 transition-opacity">
                Farmora
            </a>

            {{-- Navigation Links (Desktop) --}}
            <div class="hidden md:flex items-center gap-10">
                <a class="text-on-surface-variant font-label-caps text-label-caps tracking-widest hover:text-secondary-fixed transition-colors duration-300"
                    href="{{ route('marketplace') }}">Marketplace</a>
                <a class="text-on-surface-variant font-label-caps text-label-caps tracking-widest hover:text-secondary-fixed transition-colors duration-300"
                    href="{{ route('marketplace', ['listing_type' => 'bid']) }}">Auctions</a>

                <a class="text-on-surface-variant font-label-caps text-label-caps tracking-widest hover:text-secondary-fixed transition-colors duration-300"
                    href="/about">About</a>
                <a class="text-on-surface-variant font-label-caps text-label-caps tracking-widest hover:text-secondary-fixed transition-colors duration-300"
                    href="/contact">Contact</a>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-6">
                @auth
                    <div class="hidden md:flex gap-5 items-center">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-on-surface-variant hover:text-secondary-fixed transition-colors font-label-caps text-label-caps">Dashboard</a>
                        @elseif(auth()->user()->isFarmer())
                            <a href="{{ route('farmer.dashboard') }}"
                                class="text-on-surface-variant hover:text-secondary-fixed transition-colors font-label-caps text-label-caps">Farm
                                Panel</a>
                        @else
                            <a href="{{ route('cart.index') }}"
                                class="relative text-on-surface-variant hover:text-secondary-fixed transition-colors"
                                title="Cart">
                                <span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 0;">shopping_cart</span>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-secondary-fixed text-surface-container-lowest text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">{{ count(session('cart')) }}</span>
                                @endif
                            </a>
                            <a href="{{ route('consumer.bids') }}"
                                class="text-on-surface-variant hover:text-secondary-fixed transition-colors"
                                title="My Bids"><span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 0;">gavel</span></a>
                            <a href="{{ route('consumer.orders') }}"
                                class="text-on-surface-variant hover:text-secondary-fixed transition-colors"
                                title="My Orders"><span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 0;">receipt_long</span></a>
                        @endif

                        <div class="flex items-center gap-3 ml-2 pl-4 border-l border-white/10">
                            <a href="{{ route('profile.edit') }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors flex items-center gap-1 group">
                                <span class="material-symbols-outlined text-[16px] text-secondary-fixed group-hover:scale-110 transition-transform">person</span>
                                {{ auth()->user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-on-surface-variant hover:text-error transition-colors"
                                    title="Logout">
                                    <span class="material-symbols-outlined"
                                        style="font-variation-settings: 'FILL' 0;">logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex gap-4">
                        <a href="{{ route('login') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest hover:text-secondary-fixed transition-colors duration-300 flex items-center">Log
                            In</a>
                        <a href="{{ route('register') }}"
                            class="bg-secondary-fixed text-surface-container-lowest font-label-caps text-label-caps px-8 py-3.5 rounded-full hover:bg-white transition-all hover:shadow-[0_0_20px_rgba(203,163,88,0.4)]">
                            Join Harvest
                        </a>
                    </div>
                @endauth

                {{-- Mobile menu button --}}
                <button class="md:hidden text-on-surface hover:text-secondary-fixed transition-colors"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu"
            class="hidden md:hidden border-t border-white/5 bg-surface-container-low/95 backdrop-blur-xl absolute w-full">
            <div class="px-container-margin py-4 flex flex-col gap-4">
                <a href="{{ route('marketplace') }}"
                    class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Marketplace</a>
                <a href="{{ route('marketplace', ['listing_type' => 'bid']) }}"
                    class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Auctions</a>

                <a href="/about"
                    class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">About</a>
                <a href="/contact"
                    class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Contact</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Dashboard</a>
                    @elseif(auth()->user()->isFarmer())
                        <a href="{{ route('farmer.dashboard') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Farm Panel</a>
                    @else
                        <a href="{{ route('cart.index') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Cart</a>
                        <a href="{{ route('consumer.bids') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">My Bids</a>
                        <a href="{{ route('consumer.orders') }}"
                            class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">My Orders</a>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Profile Settings</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                            class="text-error font-label-caps text-label-caps tracking-widest">Logout</button></form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-on-surface-variant font-label-caps text-label-caps tracking-widest">Log In</a>
                    <a href="{{ route('register') }}"
                        class="text-secondary-fixed font-label-caps text-label-caps tracking-widest">Join Harvest</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="pt-24 grow relative z-10 w-full max-w-[1440px] mx-auto flex flex-col min-h-screen">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="bg-surface-container-lowest w-full border-t border-white/10 py-10 relative z-10 mt-auto overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_center,var(--tw-gradient-stops))] from-primary-fixed/5 via-transparent to-transparent pointer-events-none"></div>
        <div class="px-container-margin max-w-[1440px] mx-auto relative">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8">
                {{-- Brand Column --}}
                <div class="md:col-span-4 lg:col-span-4 flex flex-col gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/30 backdrop-blur-md shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                            <span class="material-symbols-outlined text-secondary-fixed text-[18px]" data-icon="eco" data-weight="fill" style="font-variation-settings: 'FILL' 1;">eco</span>
                        </div>
                        <span class="font-headline-md text-[20px] font-bold text-white tracking-tight">Farmora</span>
                    </a>
                    <p class="font-body-md text-sm text-on-surface-variant max-w-sm leading-relaxed">
                        The transparent, high-tech marketplace regenerating the earth through precision agriculture.
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        <a href="#" class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <span class="material-symbols-outlined text-[16px]">share</span>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <span class="material-symbols-outlined text-[16px]">forum</span>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <span class="material-symbols-outlined text-[16px]">public</span>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="md:col-span-2 lg:col-span-2 lg:col-start-6">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-4">EXPLORE</h3>
                    <ul class="flex flex-col gap-2">
                        <li><a href="{{ route('marketplace') }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Marketplace</a></li>
                        <li><a href="{{ route('marketplace', ['listing_type' => 'bid']) }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Live Auctions</a></li>

                        <li><a href="/about" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">About Us</a></li>
                    </ul>
                </div>

                {{-- Legal & Portal --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-4">ECOSYSTEM</h3>
                    <ul class="flex flex-col gap-2">
                        <li><a href="#" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Impact Stats</a></li>
                        @guest
                            <li><a href="{{ route('register', ['role' => 'farmer']) }}" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Farmer Portal</a></li>
                        @endguest
                        <li><a href="#" class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Help Center</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="md:col-span-4 lg:col-span-3">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-4">STAY UPDATED</h3>
                    <form class="flex flex-col gap-2">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">mail</span>
                            <input type="email" placeholder="Enter your email" class="w-full bg-surface-container border border-white/10 rounded-full pl-10 pr-3 py-2 text-sm text-white placeholder:text-on-surface-variant/50 focus:outline-none focus:border-secondary-fixed focus:bg-surface-container/80 transition-all">
                        </div>
                        <button type="button" class="w-full bg-secondary-fixed text-on-secondary-fixed font-label-caps text-[11px] py-2 rounded-full hover:shadow-[0_0_15px_rgba(203,163,88,0.4)] transition-all flex items-center justify-center gap-1 group">
                            SUBSCRIBE <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-body-md text-xs text-on-surface-variant">
                    © {{ date('Y') }} Farmora Ecosystems. All rights reserved.
                </p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#" class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Privacy</a>
                    <a href="#" class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Terms</a>
                    <a href="#" class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>