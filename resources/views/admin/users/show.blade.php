@extends('layouts.admin')
@section('title', 'User Details - ' . $user->name)
@section('page-title', 'User Details')
@section('content')
<div class="flex flex-col gap-6">
    {{-- Header Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="bg-surface-container-high border border-white/10 text-white hover:text-secondary-fixed hover:border-secondary-fixed/50 px-5 py-2.5 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Summary Card --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-6 text-center relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-secondary-fixed/5 rounded-full blur-xl"></div>
                
                {{-- Avatar --}}
                <div class="w-24 h-24 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary-fixed text-4xl font-bold border border-secondary-fixed/30 shadow-[0_0_20px_rgba(203,163,88,0.2)] mx-auto mb-4 group-hover:scale-105 transition-transform">
                    {{ substr($user->name, 0, 1) }}
                </div>
                
                <h2 class="font-headline-md text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
                <p class="font-body-md text-on-surface-variant text-sm mb-4">{{ $user->email }}</p>

                {{-- Status & Role badges --}}
                <div class="flex items-center justify-center gap-3 mb-6">
                    <span class="bg-surface-container-high border border-white/10 text-on-surface px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">{{ $user->role == 'farmer' ? 'agriculture' : 'shopping_cart' }}</span>
                        {{ ucfirst($user->role) }}
                    </span>

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
                </div>

                {{-- User Info Details --}}
                <div class="border-t border-white/5 pt-6 text-left flex flex-col gap-4">
                    <div>
                        <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Phone Number</span>
                        <span class="font-body-md text-white text-base">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Location</span>
                        <span class="font-body-md text-white text-base">{{ $user->location ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Account Created</span>
                        <span class="font-body-md text-white text-base">{{ $user->created_at->format('M d, Y (h:i A)') }}</span>
                    </div>
                    @if($user->approved_at)
                    <div>
                        <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Approved Date</span>
                        <span class="font-body-md text-white text-base">{{ $user->approved_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Management Buttons --}}
                <div class="border-t border-white/5 pt-6 mt-6">
                    <div class="flex flex-col gap-3">
                        @if($user->status === 'pending')
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black py-3 rounded-full font-label-caps text-xs tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)] flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Approve Account
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error py-3 rounded-full font-label-caps text-xs tracking-widest uppercase transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">close</span> Reject Account
                                </button>
                            </form>
                        @elseif($user->status === 'approved')
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-surface-container-high border border-white/10 text-on-surface-variant hover:text-error hover:border-error/50 hover:bg-error/10 py-3 rounded-full font-label-caps text-xs tracking-widest uppercase transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">block</span> Suspend Account
                                </button>
                            </form>
                        @elseif($user->status === 'suspended' || $user->status === 'rejected')
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black py-3 rounded-full font-label-caps text-xs tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)] flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">settings_backup_restore</span> Re-Activate Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Role Specific Profile Card --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            @if($user->isFarmer())
                {{-- Farmer Profile Card --}}
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8">
                    <h3 class="font-headline-md text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-fixed">agriculture</span> Farm Profile Details
                    </h3>
                    
                    @if($user->farmerProfile)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Farm Name</span>
                                <span class="font-body-md text-white text-base font-semibold">{{ $user->farmerProfile->farm_name }}</span>
                            </div>
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Farm Size</span>
                                <span class="font-body-md text-white text-base">{{ $user->farmerProfile->farm_size ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Primary Produce</span>
                                <span class="font-body-md text-white text-base">{{ $user->farmerProfile->primary_produce ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Farm Location</span>
                                <span class="font-body-md text-white text-base">{{ $user->farmerProfile->location ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Government ID</span>
                                @if($user->farmerProfile->govt_id_path)
                                    <a href="{{ asset('storage/' . $user->farmerProfile->govt_id_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-secondary-fixed/10 border border-secondary-fixed/30 text-secondary-fixed rounded-full hover:bg-secondary-fixed hover:text-surface-container-lowest transition-colors text-xs font-label-caps uppercase tracking-widest mt-1">
                                        <span class="material-symbols-outlined text-[14px]">badge</span> View Document
                                    </a>
                                @else
                                    <span class="font-body-md text-on-surface-variant text-base">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="border-t border-white/5 pt-6">
                            <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-2">Farm Bio & Description</span>
                            <p class="font-body-md text-on-surface-variant text-base leading-relaxed">{{ $user->farmerProfile->bio ?? 'No bio provided' }}</p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">warning</span>
                            <p class="font-body-md">Farmer profile not created yet.</p>
                        </div>
                    @endif
                </div>

                {{-- Farmer Listings Table --}}
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-6">
                    <h3 class="font-headline-md text-xl font-bold text-white mb-6 flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-secondary-fixed">inventory_2</span> Active Listings</span>
                        <span class="bg-surface-container-high text-white text-xs px-3 py-1 rounded-full font-label-caps tracking-widest">{{ $user->products->count() }} Listings</span>
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-white/10 bg-surface/40">
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Product</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Type</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Price</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($user->products as $product)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('marketplace.show', $product) }}" target="_blank" class="font-body-md text-base font-medium text-white hover:text-secondary-fixed transition-colors hover:underline">
                                            {{ Str::limit($product->title, 35) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-label-caps text-[10px] tracking-widest uppercase px-2.5 py-1 rounded-full border {{ $product->listing_type == 'bid' ? 'text-tertiary bg-tertiary/10 border-tertiary/30' : 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30' }}">
                                            {{ ucfirst($product->listing_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->price)
                                            <span class="font-price-display text-base font-semibold text-secondary-fixed">₹{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="font-label-caps text-[10px] tracking-widest uppercase text-on-surface-variant">Bid Only</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $prodColors = [
                                                'active' => 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30',
                                                'sold' => 'text-on-surface-variant bg-surface-container-high border-white/10',
                                                'inactive' => 'text-error bg-error/10 border-error/30',
                                                'pending' => 'text-warning bg-warning/10 border-warning/30'
                                            ];
                                            $cColors = $prodColors[$product->status] ?? $prodColors['inactive'];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full font-label-caps text-[9px] tracking-widest uppercase border {{ $cColors }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-on-surface-variant">No listings found for this farmer.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- Consumer Profile Card --}}
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-8">
                    <h3 class="font-headline-md text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-fixed">shopping_cart</span> Consumer Profile Details
                    </h3>
                    
                    @if($user->consumerProfile)
                        <div class="flex flex-col gap-6">
                            <div>
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-1">Delivery Address</span>
                                <span class="font-body-md text-white text-base">{{ $user->consumerProfile->delivery_address ?? 'Not specified' }}</span>
                            </div>
                            <div class="border-t border-white/5 pt-6">
                                <span class="font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase block mb-3">Preferred Categories</span>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->consumerProfile->preferred_categories ?? [] as $cat)
                                        <span class="bg-surface-container-high border border-white/10 px-3 py-1.5 rounded-full font-label-caps text-[10px] text-white tracking-widest uppercase">{{ $cat }}</span>
                                    @empty
                                        <span class="font-body-md text-on-surface-variant text-sm">No preferences specified</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">warning</span>
                            <p class="font-body-md">Consumer profile not created yet.</p>
                        </div>
                    @endif
                </div>

                {{-- Consumer Order History --}}
                <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] p-6">
                    <h3 class="font-headline-md text-xl font-bold text-white mb-6 flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="material-symbols-outlined text-secondary-fixed">receipt_long</span> Order History</span>
                        <span class="bg-surface-container-high text-white text-xs px-3 py-1 rounded-full font-label-caps tracking-widest">{{ $user->orders->count() }} Orders</span>
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-white/10 bg-surface/40">
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Order ID</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Date</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Total Price</th>
                                    <th class="px-6 py-4 font-label-caps text-[9px] text-on-surface-variant tracking-widest uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($user->orders as $order)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 font-body-md text-base font-semibold text-white">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 font-body-md text-sm text-on-surface-variant">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-price-display text-base font-semibold text-secondary-fixed">₹{{ number_format($order->total_price, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $orderColors = [
                                                'completed' => 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30',
                                                'pending' => 'text-warning bg-warning/10 border-warning/30',
                                                'cancelled' => 'text-error bg-error/10 border-error/30'
                                            ];
                                            $oColors = $orderColors[$order->status] ?? $orderColors['pending'];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full font-label-caps text-[9px] tracking-widest uppercase border {{ $oColors }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-on-surface-variant">No orders placed yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
