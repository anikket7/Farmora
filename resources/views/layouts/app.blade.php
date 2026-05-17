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
                            <a href="{{ route('profile.edit') }}"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors flex items-center gap-1 group">
                                <span
                                    class="material-symbols-outlined text-[16px] text-secondary-fixed group-hover:scale-110 transition-transform">person</span>
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
    <footer
        class="bg-surface-container-lowest w-full border-t border-white/10 py-4 relative z-10 mt-auto overflow-hidden">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_center,var(--tw-gradient-stops))] from-primary-fixed/5 via-transparent to-transparent pointer-events-none">
        </div>
        <div class="px-container-margin max-w-[1440px] mx-auto relative">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-4">
                {{-- Brand Column --}}
                <div class="md:col-span-4 lg:col-span-4 flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/30 backdrop-blur-md shadow-[0_0_15px_rgba(203,163,88,0.15)]">
                            <span class="material-symbols-outlined text-secondary-fixed text-[18px]" data-icon="eco"
                                data-weight="fill" style="font-variation-settings: 'FILL' 1;">eco</span>
                        </div>
                        <span class="font-headline-md text-[20px] font-bold text-white tracking-tight">Farmora</span>
                    </a>
                    <p class="font-body-md text-sm text-on-surface-variant max-w-sm leading-relaxed">
                        The transparent, high-tech marketplace regenerating the earth through precision agriculture.
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        <a href="https://github.com/anikket7" target="_blank"
                            class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="https://linkedin.com//in/aniket712/" target="_blank"
                            class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <a href="https://instagram.com/anikket_7/" target="_blank"
                            class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="mailto:aniketkumarsingh4321@gmail.com"
                            class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant border border-white/5 hover:border-secondary-fixed/50 hover:text-secondary-fixed hover:bg-secondary-fixed/10 transition-all hover:-translate-y-1 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                                <path
                                    d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="md:col-span-2 lg:col-span-2 lg:col-start-6">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-2">EXPLORE</h3>
                    <ul class="flex flex-col gap-1">
                        <li><a href="{{ route('marketplace') }}"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Marketplace</a>
                        </li>
                        <li><a href="{{ route('marketplace', ['listing_type' => 'bid']) }}"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Live
                                Auctions</a></li>
                        <li><a href="{{ route('about') }}"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">About
                                Us</a></li>
                        <li><a href="{{ route('contact') }}"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Contact</a>
                        </li>
                    </ul>
                </div>

                {{-- Legal & Portal --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-2">LEGAL & HELP</h3>
                    <ul class="flex flex-col gap-1">
                        <li><a href="#"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">FAQ</a>
                        </li>
                        @guest
                            <li><a href="{{ route('register', ['role' => 'farmer']) }}"
                                    class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Farmer
                                    Portal</a></li>
                        @endguest
                        <li><a href="#"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Privacy
                                Policy</a></li>
                        <li><a href="#"
                                class="font-body-md text-sm text-on-surface-variant hover:text-secondary-fixed transition-colors">Terms
                                of Service</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="md:col-span-4 lg:col-span-3">
                    <h3 class="font-label-caps text-[10px] text-white tracking-[0.2em] mb-2">STAY UPDATED</h3>
                    <form class="flex flex-col gap-2">
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">mail</span>
                            <input type="email" placeholder="Enter your email"
                                class="w-full bg-surface-container border border-white/10 rounded-full pl-10 pr-3 py-2 text-sm text-white placeholder:text-on-surface-variant/50 focus:outline-none focus:border-secondary-fixed focus:bg-surface-container/80 transition-all">
                        </div>
                        <button type="button"
                            class="w-full bg-secondary-fixed text-on-secondary-fixed font-label-caps text-[11px] py-2 rounded-full hover:shadow-[0_0_15px_rgba(203,163,88,0.4)] transition-all flex items-center justify-center gap-1 group">
                            SUBSCRIBE <span
                                class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="pt-3 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-2">
                <p class="font-body-md text-xs text-on-surface-variant">
                    © {{ date('Y') }} Farmora Ecosystems. All rights reserved.
                </p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#"
                        class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Privacy</a>
                    <a href="#"
                        class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Terms</a>
                    <a href="#"
                        class="font-body-md text-xs text-on-surface-variant hover:text-secondary-fixed transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>