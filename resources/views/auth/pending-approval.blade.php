@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="grow flex items-center justify-center px-container-margin py-section-gap relative z-10 w-full min-h-[70vh]">
    <!-- Background Accents -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,var(--tw-gradient-stops))] from-tertiary/10 via-transparent to-transparent opacity-50 pointer-events-none"></div>

    <div class="w-full max-w-md bg-surface-container/30 backdrop-blur-2xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-10 flex flex-col items-center text-center relative overflow-hidden group hover:border-white/10 transition-colors duration-500">
        <!-- Decorative Glow -->
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-tertiary/20 rounded-full blur-[50px] pointer-events-none group-hover:bg-tertiary/30 transition-colors duration-700"></div>

        <div class="w-20 h-20 bg-tertiary-container/30 rounded-full flex items-center justify-center border border-tertiary-container/50 shadow-[0_0_15px_rgba(251,159,117,0.15)] mb-8 relative">
            <span class="material-symbols-outlined text-tertiary text-4xl animate-pulse">hourglass_empty</span>
        </div>
        
        <h1 class="text-[28px] font-headline-md font-bold text-white mb-4 tracking-tight">Account Pending</h1>
        <p class="text-on-surface-variant font-body-md leading-relaxed mb-8">
            Your profile is currently under review by our ecosystem administrators. You'll receive an email once your access is verified.
            <br><br>
            <span class="text-sm">Need help? Contact us at <a href="mailto:support@farmora.com" class="text-secondary-fixed hover:underline transition-colors">support@farmora.com</a></span>
        </p>

        <div class="w-full bg-surface/50 border border-white/5 rounded-2xl p-6 mb-8 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <span class="font-label-caps text-label-caps text-on-surface-variant tracking-widest">STATUS</span>
                <span class="bg-tertiary-container/30 border border-tertiary-container/50 text-tertiary px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider uppercase shadow-[0_0_10px_rgba(251,159,117,0.2)]">
                    {{ auth()->user()->status ?? 'Pending' }}
                </span>
            </div>
            <div class="w-full h-px bg-white/5"></div>
            <div class="flex items-center justify-between">
                <span class="font-label-caps text-label-caps text-on-surface-variant tracking-widest">ROLE</span>
                <span class="bg-primary-container/30 border border-primary-container/50 text-primary-fixed px-3 py-1 rounded-full font-label-caps text-[10px] tracking-wider uppercase shadow-[0_0_10px_rgba(6,205,172,0.2)]">
                    {{ auth()->user()->role ?? 'User' }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-full border border-white/10 text-on-surface hover:text-white hover:border-white/30 hover:bg-white/5 transition-all font-label-caps text-label-caps tracking-widest uppercase">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Logout & Come Back Later
            </button>
        </form>
    </div>
</div>
@endsection
