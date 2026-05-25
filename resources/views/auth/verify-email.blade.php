<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Farmora</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600&family=Literata:opsz,wght@7..72,500;7..72,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-container-lowest text-on-surface font-body-md relative min-h-screen flex flex-col antialiased">
    <div class="noise-bg"></div>

    @if(session('status') == 'verification-link-sent')
        <div class="toast toast-success">✓ A new verification code has been sent to your email address!</div>
    @endif
    @if(session('error'))
        <div class="toast toast-error">✕ {{ session('error') }}</div>
    @endif

    <main class="grow flex items-center justify-center relative z-10 px-container-margin py-section-gap w-full">
        <div class="w-full max-w-xl rounded-xl overflow-hidden shadow-[0_16px_64px_-12px_rgba(203,163,88,0.1)] border border-white/10 backdrop-blur-3xl bg-surface-container/30 p-8 flex flex-col items-center text-center">
            
            <div class="w-16 h-16 rounded-full bg-secondary-container/20 flex items-center justify-center border border-secondary-fixed/20 shadow-[0_0_15px_rgba(203,163,88,0.15)] mb-6">
                <span class="material-symbols-outlined text-secondary-fixed text-3xl">mail</span>
            </div>

            <h2 class="font-headline-md text-headline-md text-on-surface mb-3">Verify Your Email</h2>
            
            <p class="font-body-md text-on-surface-variant mb-6 max-w-md leading-relaxed">
                Thanks for signing up! Please verify your email address by entering the 6-digit verification code we just emailed to you.
            </p>

            {{-- OTP Input Form --}}
            <form method="POST" action="{{ route('verification.verify') }}" class="w-full max-w-xs mb-8">
                @csrf
                <div class="flex flex-col gap-2 mb-5">
                    <label for="otp" class="font-label-caps text-label-caps text-on-surface-variant mb-1">Enter 6-Digit OTP</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">pin</span>
                        <input id="otp" name="otp" required autofocus class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 pl-12 pr-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all text-center tracking-[8px] text-lg font-bold placeholder:tracking-normal placeholder:text-sm placeholder:font-normal placeholder:text-on-surface-variant/50" placeholder="••••••" type="text" maxlength="6" pattern="[0-9]{6}"/>
                    </div>
                    @error('otp')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-secondary-fixed text-surface-container-lowest font-label-caps text-label-caps py-3.5 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex justify-center items-center gap-2 cursor-pointer font-bold">
                    Verify Code
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </button>
            </form>

            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center border-t border-white/5 pt-6">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full bg-surface-container-high/50 border border-white/5 hover:border-white/10 text-on-surface font-label-caps text-label-caps py-3 px-6 rounded-full transition-all flex justify-center items-center gap-2 cursor-pointer">
                        Resend Code
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">send</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full border border-white/10 hover:border-white/30 text-on-surface-variant hover:text-white font-label-caps text-label-caps py-3 px-6 rounded-full transition-all flex justify-center items-center gap-2 cursor-pointer">
                        Sign Out
                        <span class="material-symbols-outlined text-sm">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
