@extends($layout)

@section('title', 'Profile Settings')
@if(view()->exists('layouts.farmer') && $layout === 'layouts.farmer')
    @section('page-title', 'Profile Management')
@elseif(view()->exists('layouts.admin') && $layout === 'layouts.admin')
    @section('page-title', 'Profile Settings')
@endif

@section('content')
<div class="{{ $layout === 'layouts.app' ? 'px-container-margin py-4 max-w-6xl mx-auto relative z-10' : 'max-w-6xl' }}">
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-3">
            <a href="javascript:history.back()" class="flex items-center justify-center w-9 h-9 rounded-full border border-white/10 text-on-surface-variant hover:text-white hover:border-white/30 transition-all" title="Go Back">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            @if($layout === 'layouts.app')
                <h1 class="font-headline-md text-2xl font-bold text-white">Profile Settings</h1>
            @endif
        </div>
        
        @if(session('success'))
            <div class="bg-primary-fixed/10 border border-primary-fixed/30 text-primary-fixed px-5 py-2 rounded-full font-body-md text-sm shadow-[0_0_15px_rgba(6,205,172,0.1)]">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {{-- Left Side: Account & Profile Details --}}
                    <div class="lg:col-span-7 flex flex-col gap-5">
                        {{-- Basic Details Section --}}
                        <div>
                            <h3 class="font-headline-md text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-white/5 pb-2">
                                <span class="material-symbols-outlined text-secondary-fixed text-xl">person</span> Basic Account Details
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Full Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors" required>
                                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="email" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors" required>
                                    @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="phone" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Phone Number</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors">
                                    @error('phone') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="location" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Location / City</label>
                                    <input type="text" id="location" name="location" value="{{ old('location', $user->location) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors">
                                    @error('location') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Farmer Specific Details Section --}}
                        @if($user->isFarmer())
                        <div>
                            <h3 class="font-headline-md text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-white/5 pb-2">
                                <span class="material-symbols-outlined text-secondary-fixed text-xl">agriculture</span> Farm Profile Details
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="farm_name" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Farm Name</label>
                                    <input type="text" id="farm_name" name="farm_name" value="{{ old('farm_name', $user->farmerProfile?->farm_name) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors" required>
                                    @error('farm_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="farm_size" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Farm Size</label>
                                    <input type="text" id="farm_size" name="farm_size" value="{{ old('farm_size', $user->farmerProfile?->farm_size) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors" placeholder="e.g. 10 acres">
                                    @error('farm_size') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="primary_produce" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Primary Produce</label>
                                    <input type="text" id="primary_produce" name="primary_produce" value="{{ old('primary_produce', $user->farmerProfile?->primary_produce) }}" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors" placeholder="e.g. Organic Fruits, Wheat">
                                    @error('primary_produce') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="bio" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Farm Bio</label>
                                    <textarea id="bio" name="bio" rows="2" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-[16px] px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors resize-none">{{ old('bio', $user->farmerProfile?->bio) }}</textarea>
                                    @error('bio') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Consumer Specific Details Section --}}
                        @if($user->isConsumer())
                        <div>
                            <h3 class="font-headline-md text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-white/5 pb-2">
                                <span class="material-symbols-outlined text-secondary-fixed text-xl">shopping_cart</span> Consumer Profile Details
                            </h3>
                            
                            <div>
                                <label for="delivery_address" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Default Delivery Address</label>
                                <textarea id="delivery_address" name="delivery_address" rows="2" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-[16px] px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors resize-none">{{ old('delivery_address', $user->consumerProfile?->delivery_address) }}</textarea>
                                @error('delivery_address') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Right Side: Security & Form Submission --}}
                    <div class="lg:col-span-5 flex flex-col justify-between gap-6">
                        {{-- Security & Password Section --}}
                        <div>
                            <h3 class="font-headline-md text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-white/5 pb-2">
                                <span class="material-symbols-outlined text-secondary-fixed text-xl">key</span> Security & Password
                            </h3>
                            
                            <div class="flex flex-col gap-4">
                                <p class="text-xs text-on-surface-variant leading-relaxed">Leave these fields blank if you do not wish to update your current account password.</p>
                                
                                <div>
                                    <label for="password" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">New Password</label>
                                    <input type="password" id="password" name="password" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors">
                                    @error('password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-1 block">Confirm New Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2.5 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed transition-colors">
                                </div>
                            </div>
                        </div>

                        {{-- Form Submission Actions --}}
                        <div class="border-t border-white/5 pt-6 flex flex-col gap-3">
                            <button type="submit" class="w-full bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)] text-center text-sm font-semibold">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
