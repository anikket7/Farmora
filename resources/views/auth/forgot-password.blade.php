<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Farmora</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&family=Literata:opsz,wght@7..72,500;7..72,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-lowest text-on-surface font-body-md relative min-h-screen flex flex-col antialiased">
    <div class="noise-bg"></div>

    @if(session('success'))
        <div class="toast toast-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast toast-error">✕ {{ session('error') }}</div>
    @endif

    <main class="grow flex items-center justify-center relative z-10 px-container-margin py-section-gap w-full">
        <div class="w-full max-w-4xl grid md:grid-cols-2 gap-0 rounded-xl overflow-hidden shadow-[0_16px_64px_-12px_rgba(203,163,88,0.1)] border border-white/10 backdrop-blur-3xl bg-surface-container/30">
            <!-- Left Side: Visual / Brand Narrative -->
            <div class="hidden md:flex flex-col justify-between p-card-padding bg-cover bg-center relative" data-alt="A sweeping aerial view of a meticulously organized, high-tech organic farm at dawn." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAD1l8Z5fSNWYN7mupFj5uj3WFmCEtNmX7bAgKNBguagPeL7dITjUitwWnAS8GN-wqqPo-dMOvEsNfTyna4VgPgW4xVo9Zx8gqMGP_0SbMPFB36vRaUq5Kkch8uZkWuXtRUli7elbN_eheCel4XW4CM2OVdhCFKK2ojB2kvT4NCq-wZHS3rsyShOgCVLSUyNiWPCSYt0hJ3jcYBN7MJMEMos5dyBupO-ZPoxZ1TqNkEE01rrqwQf1lQVNc7jUFlDndsDBPKdoP7fMM');">
                <div class="absolute inset-0 bg-linear-to-t from-surface-container-lowest/90 via-surface-container-lowest/40 to-transparent"></div>
                <div class="relative z-10">
                    <h1 class="font-headline-md text-headline-md font-bold text-secondary-fixed tracking-tight">Farmora</h1>
                </div>
                <div class="relative z-10 mt-auto">
                    <p class="font-display-lg text-display-lg text-white mb-4">Precision<br/>Nature.</p>
                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-sm">Join the ecosystem that regenerates the earth through advanced agricultural technology.</p>
                </div>
            </div>

            <!-- Right Side: Form Canvas -->
            <div class="p-card-padding md:p-8 flex flex-col h-full bg-surface-container/20">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Request OTP Code</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Enter your email to receive a 5-digit verification OTP.</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6 grow">
                    @csrf
                    
                    <div class="flex flex-col gap-2">
                        <label for="email" class="font-label-caps text-label-caps text-on-surface-variant pl-4">Email Address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">mail</span>
                            <input id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 pl-12 pr-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="you@example.com" type="email"/>
                        </div>
                        @error('email')
                            <p class="text-error text-sm mt-1 pl-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-secondary-fixed text-surface-container-lowest font-label-caps text-label-caps py-4 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex justify-center items-center gap-2">
                            Send OTP Code
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="font-body-md text-sm text-on-surface-variant">
                        <a href="{{ route('login') }}" class="text-secondary-fixed hover:text-white transition-colors flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Back to login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
