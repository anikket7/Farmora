<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Farmora Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&family=Literata:opsz,wght@7..72,500;7..72,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-on-surface font-body-md min-h-screen antialiased flex flex-col relative overflow-x-hidden">
    <!-- Cinematic Background Orbs -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-primary-fixed/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] bg-secondary-fixed/5 rounded-full blur-[120px]"></div>
        <div class="absolute top-[40%] left-[50%] w-[30vw] h-[30vw] bg-tertiary/5 rounded-full blur-[100px] transform -translate-x-1/2"></div>
    </div>
    <div class="noise-bg z-0"></div>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <div class="toast toast-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast toast-error">✕ {{ session('error') }}</div>
    @endif

    <div class="flex min-h-screen relative z-10 w-full">
        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-surface-container-low/95 backdrop-blur-2xl border-r border-white/5 fixed left-0 top-0 bottom-0 z-40 transition-transform lg:translate-x-0 -translate-x-full overflow-y-auto">
            <div class="p-6 h-full flex flex-col">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-8 group">
                    <span class="font-headline-md text-[24px] font-bold text-secondary-fixed tracking-tight group-hover:opacity-80 transition-opacity">Farmora</span>
                    <span class="bg-primary-container/30 border border-primary-container/50 text-primary-fixed px-2 py-0.5 rounded-full font-label-caps text-[10px] tracking-wider uppercase ml-auto">Admin</span>
                </a>

                <nav class="flex flex-col gap-2 grow">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-full {{ request()->routeIs('admin.dashboard') ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.1)]' : 'text-on-surface-variant hover:text-secondary-fixed hover:bg-white/5 border border-transparent hover:border-white/10' }} transition-all font-label-caps text-label-caps tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-full {{ request()->routeIs('admin.users.*') ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.1)]' : 'text-on-surface-variant hover:text-secondary-fixed hover:bg-white/5 border border-transparent hover:border-white/10' }} transition-all font-label-caps text-label-caps tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[18px]">group</span> Users
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-full {{ request()->routeIs('admin.categories.*') ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.1)]' : 'text-on-surface-variant hover:text-secondary-fixed hover:bg-white/5 border border-transparent hover:border-white/10' }} transition-all font-label-caps text-label-caps tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[18px]">category</span> Categories
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-full {{ request()->routeIs('admin.products.*') ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.1)]' : 'text-on-surface-variant hover:text-secondary-fixed hover:bg-white/5 border border-transparent hover:border-white/10' }} transition-all font-label-caps text-label-caps tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span> Products
                    </a>
                    <a href="{{ route('admin.bids.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-full {{ request()->routeIs('admin.bids.*') ? 'bg-secondary-fixed/10 text-secondary-fixed border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.1)]' : 'text-on-surface-variant hover:text-secondary-fixed hover:bg-white/5 border border-transparent hover:border-white/10' }} transition-all font-label-caps text-label-caps tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[18px]">gavel</span> Bid Sessions
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-white/5">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 mb-4 group">
                        <div class="w-10 h-10 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary-fixed font-bold border border-secondary-fixed/30 shadow-[0_0_10px_rgba(203,163,88,0.15)] group-hover:border-secondary-fixed transition-colors">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-body-md text-white group-hover:text-secondary-fixed transition-colors">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-on-surface-variant">Profile Settings</div>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-full text-error hover:text-error-container hover:bg-error/10 border border-transparent transition-all font-label-caps text-label-caps tracking-widest uppercase w-full">
                            <span class="material-symbols-outlined text-[18px]">logout</span> Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            {{-- Top bar --}}
            <header class="bg-surface/80 backdrop-blur-2xl sticky top-0 z-30 px-6 py-4 flex items-center justify-between border-b border-white/5">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="lg:hidden text-on-surface-variant hover:text-secondary-fixed transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 class="font-headline-md text-[20px] font-semibold text-white tracking-tight">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="font-label-caps text-[10px] tracking-widest uppercase text-on-surface-variant">{{ now()->format('D, M j, Y') }}</div>
            </header>

            <div class="p-6 md:p-8 grow">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
