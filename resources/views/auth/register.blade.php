<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Join the Harvest - Farmora</title>
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
        <div class="w-full max-w-5xl grid md:grid-cols-2 gap-0 rounded-xl overflow-hidden shadow-[0_16px_64px_-12px_rgba(203,163,88,0.1)] border border-white/10 backdrop-blur-3xl bg-surface-container/30">
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
            <div class="p-card-padding md:p-8 flex flex-col h-full bg-surface-container/20 max-h-[90vh] overflow-y-auto">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md text-on-surface mb-2">Join the Harvest</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Select your path in the Farmora ecosystem.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="flex flex-col gap-6 grow">
                    @csrf

                    <!-- Role Toggle using Radio buttons under the hood -->
                    <div class="flex bg-surface-container-high rounded-full p-1 mb-2 border border-white/5 relative">
                        <label class="flex-1 text-center cursor-pointer relative z-10">
                            <input type="radio" name="role" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }} class="peer hidden" onchange="toggleFarmerFields()">
                            <div class="py-2 px-4 rounded-full font-label-caps text-label-caps transition-all peer-checked:bg-secondary-fixed peer-checked:text-surface-container-lowest peer-checked:shadow-[0_0_12px_rgba(203,163,88,0.3)] text-on-surface-variant peer-checked:font-bold">
                                Farmer
                            </div>
                        </label>
                        <label class="flex-1 text-center cursor-pointer relative z-10">
                            <input type="radio" name="role" value="consumer" {{ old('role', 'consumer') == 'consumer' ? 'checked' : '' }} class="peer hidden" onchange="toggleFarmerFields()">
                            <div class="py-2 px-4 rounded-full font-label-caps text-label-caps transition-all peer-checked:bg-secondary-fixed peer-checked:text-surface-container-lowest peer-checked:shadow-[0_0_12px_rgba(203,163,88,0.3)] text-on-surface-variant peer-checked:font-bold">
                                Consumer
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-error text-sm pl-4 -mt-4">{{ $message }}</p>
                    @enderror

                    <!-- Basic Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Full Name</label>
                            <input id="name" name="name" value="{{ old('name') }}" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="Your full name" type="text"/>
                            @error('name')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Email Address</label>
                            <input id="email" name="email" value="{{ old('email') }}" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="you@example.com" type="email"/>
                            @error('email')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Phone Number</label>
                            <input id="phone" name="phone" value="{{ old('phone') }}" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="9876543210" type="text"/>
                            @error('phone')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Location / City</label>
                            <input id="location" name="location" value="{{ old('location') }}" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="Your city" type="text"/>
                            @error('location')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Password</label>
                            <input id="password" name="password" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="Min 8 characters" type="password"/>
                            @error('password')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" required class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="Confirm password" type="password"/>
                        </div>
                    </div>

                    <!-- Farmer-specific fields -->
                    <div id="farmer-fields" class="mt-2 space-y-6" style="{{ old('role') == 'farmer' ? '' : 'display: none' }}">
                        <div class="border-t border-white/10 pt-6">
                            <h3 class="font-label-caps text-label-caps text-secondary-fixed mb-4">🧑‍🌾 Farm Details</h3>
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Farm Name</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">agriculture</span>
                                <input id="farm_name" name="farm_name" value="{{ old('farm_name') }}" class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 pl-12 pr-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50" placeholder="Enter your farm's name" type="text"/>
                            </div>
                            @error('farm_name')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Farm Size</label>
                                <input id="farm_size" name="farm_size" value="{{ old('farm_size') }}" class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all placeholder:text-on-surface-variant/50 text-center" placeholder="e.g. 10 acres" type="text"/>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Primary Produce</label>
                                <select id="primary_produce" name="primary_produce" class="w-full bg-surface-container-low border border-white/10 rounded-full py-3 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed transition-all appearance-none cursor-pointer">
                                    <option disabled selected value="">Select...</option>
                                    <option value="vegetables" {{ old('primary_produce') == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                                    <option value="fruits" {{ old('primary_produce') == 'fruits' ? 'selected' : '' }}>Fruits</option>
                                    <option value="grains" {{ old('primary_produce') == 'grains' ? 'selected' : '' }}>Grains</option>
                                    <option value="livestock" {{ old('primary_produce') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-label-caps text-on-surface-variant pl-4">Government ID Upload</label>
                            <label for="govt_id" id="govt_id_label" class="border border-dashed border-white/20 rounded-xl p-6 text-center hover:bg-white/5 transition-colors cursor-pointer group block">
                                <span class="material-symbols-outlined text-3xl text-secondary-fixed/50 mb-2 group-hover:text-secondary-fixed transition-colors" style="font-variation-settings: 'FILL' 0;">upload_file</span>
                                <p class="font-body-md text-body-md text-on-surface-variant" id="govt_id_text">Click to browse file</p>
                                <p class="font-label-caps text-label-caps text-on-surface-variant/50 mt-1">PDF, JPG, PNG (Max 20MB)</p>
                                <input type="file" id="govt_id" name="govt_id" class="hidden" accept=".jpg,.png,.pdf" onchange="document.getElementById('govt_id_text').textContent = this.files[0] ? this.files[0].name : 'Click to browse file'; if(this.files[0]) { document.getElementById('govt_id_label').classList.add('border-secondary-fixed', 'bg-secondary-fixed/5'); } else { document.getElementById('govt_id_label').classList.remove('border-secondary-fixed', 'bg-secondary-fixed/5'); }">
                            </label>
                            @error('govt_id')<p class="text-error text-xs pl-4">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Consumer-specific fields have been removed from registration -->

                    <div class="mt-auto pt-8">
                        <button type="submit" class="w-full bg-secondary-fixed text-surface-container-lowest font-label-caps text-label-caps py-4 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex justify-center items-center gap-2">
                            Begin <span id="role-label-btn">Harvest</span>
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">arrow_forward</span>
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center border-t border-white/10 pt-6">
                    <p class="font-body-md text-sm text-on-surface-variant">Already have an account? <a href="{{ route('login') }}" class="text-secondary-fixed hover:text-white transition-colors">Sign in</a></p>
                </div>
            </div>
        </div>
    </main>

<script>
function toggleFarmerFields() {
    const role = document.querySelector('input[name="role"]:checked')?.value;
    document.getElementById('farmer-fields').style.display = role === 'farmer' ? '' : 'none';
    document.getElementById('role-label-btn').textContent = role === 'farmer' ? 'Verification' : 'Shopping';
}
// Initialize on load
document.addEventListener('DOMContentLoaded', toggleFarmerFields);
</script>

</body>
</html>
