@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('content')
<div class="flex flex-col gap-6">
    {{-- Filters --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Search</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[18px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full pl-10 pr-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50" placeholder="Name, email, location...">
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Role</label>
                <select name="role" class="w-full sm:w-40 bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors appearance-none">
                    <option value="" class="bg-surface">All Roles</option>
                    <option value="farmer" class="bg-surface" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                    <option value="consumer" class="bg-surface" {{ request('role') == 'consumer' ? 'selected' : '' }}>Consumer</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Status</label>
                <select name="status" class="w-full sm:w-40 bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors appearance-none">
                    <option value="" class="bg-surface">All Status</option>
                    <option value="pending" class="bg-surface" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" class="bg-surface" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" class="bg-surface" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="suspended" class="bg-surface" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                <button type="submit" class="flex-1 sm:flex-none bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)]">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="flex-1 sm:flex-none bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all text-center flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-white/10 bg-surface/40">
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">User</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Role</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Status</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Location</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">Joined</th>
                        <th class="px-6 py-4 font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary-fixed font-bold border border-secondary-fixed/30 shadow-[0_0_10px_rgba(203,163,88,0.15)] group-hover:scale-105 transition-transform">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-body-md text-base font-medium text-white mb-0.5">
                                        <a href="{{ route('admin.users.show', $user) }}" class="hover:text-secondary-fixed transition-colors hover:underline">{{ $user->name }}</a>
                                    </div>
                                    <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-surface-container-high border border-white/10 text-on-surface px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase flex items-center gap-1 w-max">
                                <span class="material-symbols-outlined text-[14px]">{{ $user->role == 'farmer' ? 'agriculture' : 'shopping_cart' }}</span>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'approved' => 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30',
                                    'pending' => 'text-tertiary bg-tertiary/10 border-tertiary/30',
                                    'rejected' => 'text-error bg-error/10 border-error/30',
                                    'suspended' => 'text-on-surface-variant bg-surface-container-high border-white/10'
                                ];
                                $colors = $statusColors[$user->status] ?? $statusColors['suspended'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase border {{ $colors }} inline-flex items-center gap-1 shadow-[0_0_10px_currentColor]">
                                @if($user->status === 'approved') <span class="w-1.5 h-1.5 rounded-full bg-primary-fixed"></span> @endif
                                @if($user->status === 'pending') <span class="w-1.5 h-1.5 rounded-full bg-tertiary animate-pulse"></span> @endif
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-body-md text-sm text-on-surface-variant">{{ $user->location ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-body-md text-sm text-on-surface-variant">{{ $user->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex gap-2 justify-end">
                                @if($user->role === 'farmer' && $user->farmerProfile && $user->farmerProfile->govt_id_path)
                                    <a href="{{ asset('storage/' . $user->farmerProfile->govt_id_path) }}" target="_blank" class="bg-secondary-container/20 border border-secondary-fixed/30 text-secondary-fixed hover:bg-secondary-fixed hover:text-surface-container-lowest px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all shadow-[0_0_10px_rgba(203,163,88,0.1)] hover:shadow-[0_0_20px_rgba(203,163,88,0.3)] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">badge</span> View ID
                                    </a>
                                @endif
                                @if($user->status === 'pending')
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">@csrf<button class="bg-primary-container/30 border border-primary-container text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all shadow-[0_0_10px_rgba(6,205,172,0.1)] hover:shadow-[0_0_20px_rgba(6,205,172,0.3)] flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">check</span> Approve</button></form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}">@csrf<button class="bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">close</span> Reject</button></form>
                                @elseif($user->status === 'approved')
                                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">@csrf<button class="bg-surface-container-high border border-white/10 text-on-surface-variant hover:text-error hover:border-error/50 hover:bg-error/10 px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">block</span> Suspend</button></form>
                                @elseif($user->status === 'suspended' || $user->status === 'rejected')
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">@csrf<button class="bg-primary-container/30 border border-primary-container text-primary-fixed hover:bg-primary-fixed hover:text-on-primary-fixed px-4 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all shadow-[0_0_10px_rgba(6,205,172,0.1)] hover:shadow-[0_0_20px_rgba(6,205,172,0.3)] flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">settings_backup_restore</span> Re-Activate</button></form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-4 opacity-50">group_off</span>
                                <p class="font-body-md">No users found matching your filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
