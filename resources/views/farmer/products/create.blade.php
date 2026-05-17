    @extends('layouts.farmer')
    @section('title', 'Add Product')
    @section('page-title', 'Create New Product Listing')

    @section('content')
    <div class="fade-in max-w-5xl mx-auto w-full">
        
        <div class="mb-4">
            <h2 class="font-headline-md text-[26px] text-white leading-tight">Initialize Listing</h2>
            <p class="font-body-md text-on-surface-variant text-xs mt-1">Configure your produce parameters for the marketplace.</p>
        </div>

        <div class="bg-surface-container/40 backdrop-blur-2xl border border-white/10 rounded-[24px] p-6 shadow-[0_8px_32px_rgba(0,0,0,0.37)] relative overflow-hidden">
            
            <!-- Background Accents -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-fixed/5 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-fixed/5 rounded-full blur-[80px] pointer-events-none"></div>

            <form id="product-form" method="POST" action="{{ route('farmer.products.store') }}" enctype="multipart/form-data" class="relative z-10 flex flex-col h-full">
                @csrf

                {{-- Global Validation Errors --}}
                @if($errors->any())
                    <div class="bg-error/10 border border-error/30 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-2 text-error font-label-caps text-[11px] tracking-widest uppercase mb-2">
                            <span class="material-symbols-outlined text-[18px]">error</span> Please fix the following errors
                        </div>
                        <ul class="list-disc list-inside text-error/90 text-sm font-body-md space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Row 1: Title & Category -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="md:col-span-3">
                        <label for="title" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Product Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" placeholder="e.g. Fresh Organic Tomatoes" required>
                        @error('title')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="category_id" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Category</label>
                        <div class="relative">
                            <select id="category_id" name="category_id" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all appearance-none" required>
                                <option value="" style="color:#ffffff; background:#121c24;">Select category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" style="color:#ffffff; background:#121c24;" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-[18px]">expand_more</span>
                        </div>
                        @error('category_id')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Row 2: Listing Type, Quantity, Unit, Price -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="listing_type" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Listing Type</label>
                        <div class="relative">
                            <select id="listing_type" name="listing_type" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all appearance-none" required onchange="toggleBidFields()">
                                <option value="buy" style="color:#ffffff; background:#121c24;" {{ old('listing_type') == 'buy' ? 'selected' : '' }}>Direct Buy Only</option>
                                <option value="bid" style="color:#ffffff; background:#121c24;" {{ old('listing_type') == 'bid' ? 'selected' : '' }}>Auction (Bidding Only)</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-[18px]">expand_more</span>
                        </div>
                    </div>
                    <div>
                        <label for="quantity" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Quantity</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="1" min="1" placeholder="10" required>
                        @error('quantity')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="unit" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Unit</label>
                        <div class="relative">
                            <select id="unit" name="unit" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all appearance-none" required>
                                <option value="kg" style="color:#ffffff; background:#121c24;" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" style="color:#ffffff; background:#121c24;" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                                <option value="dozen" style="color:#ffffff; background:#121c24;" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                                <option value="piece" style="color:#ffffff; background:#121c24;" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                <option value="liter" style="color:#ffffff; background:#121c24;" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-[18px]">expand_more</span>
                        </div>
                    </div>
                    <div id="price-field">
                        <label for="price" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Price/Unit (₹)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-fixed font-bold text-sm">₹</span>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 pl-10 pr-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="0.01" min="1" placeholder="45.00">
                        </div>
                        @error('price')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Bidding Fields (The lined section from your screenshot) --}}
                <div id="bid-fields" class="mb-4 py-4 border-y border-white/20" style="{{ old('listing_type') === 'bid' ? '' : 'display:none' }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_bid_price" class="block font-label-caps text-[11px] tracking-widest text-secondary-fixed uppercase mb-1.5 ml-1">Starting Bid (₹)</label>
                            <input type="number" id="start_bid_price" name="start_bid_price" value="{{ old('start_bid_price') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="0.01" min="1" placeholder="0.00">
                            @error('start_bid_price')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="min_increment" class="block font-label-caps text-[11px] tracking-widest text-secondary-fixed uppercase mb-1.5 ml-1">Min Increment (₹)</label>
                            <input type="number" id="min_increment" name="min_increment" value="{{ old('min_increment', 10) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="0.01" min="1">
                            @error('min_increment')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="bid_end_time" class="block font-label-caps text-[11px] tracking-widest text-secondary-fixed uppercase mb-1.5 ml-1">Bid Ends At</label>
                            <input type="datetime-local" id="bid_end_time" name="bid_end_time" value="{{ old('bid_end_time') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all scheme-dark">
                            @error('bid_end_time')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Row 4: Description & Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div class="flex flex-col">
                        <label for="description" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1 shrink-0">Description</label>
                        <textarea id="description" name="description" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500 flex-1 resize-none" placeholder="Elaborate on the quality, farming method, and any special characteristics..." required>{{ old('description') }}</textarea>
                        @error('description')<p class="text-error text-[10px] mt-1 ml-1 shrink-0">{{ $message }}</p>@enderror
                    </div>
                    
                    <div class="flex flex-col gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="harvest_date" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Harvest Date</label>
                                <input type="date" id="harvest_date" name="harvest_date" value="{{ old('harvest_date') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all scheme-dark">
                            </div>
                            <div>
                                <label for="origin_location" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Origin</label>
                                <input type="text" id="origin_location" name="origin_location" value="{{ old('origin_location') }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" placeholder="e.g. Punjab, India">
                            </div>
                        </div>
                        
                        <div>
                            <div class="w-full border border-dashed border-white/20 hover:border-secondary-fixed/50 bg-white/5 hover:bg-white/10 rounded-xl p-3 flex items-center gap-3 transition-all cursor-pointer h-[46px] relative overflow-hidden" onclick="document.getElementById('images_input').click()">
                                <span class="material-symbols-outlined text-[20px] text-slate-400">add_photo_alternate</span>
                                <div>
                                    <p class="font-body-md text-white text-sm leading-tight">Upload Images</p>
                                    <p class="font-body-md text-slate-400 text-[10px] leading-tight mt-0.5">Max 5 JPG/PNG files</p>
                                </div>
                                <input type="file" id="images_input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImages(this, 'image-preview')" required>
                                <div id="image-preview" class="flex gap-1.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none empty:hidden bg-surface-container/90 rounded-lg p-1"></div>
                            </div>
                            @error('images')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-auto">
                    <button type="submit" class="w-full bg-secondary-fixed text-surface-container-lowest font-label-caps text-[14px] tracking-widest py-3.5 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex items-center justify-center gap-3 hover:-translate-y-0.5 group">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">publish</span> PUBLISH LISTING
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleBidFields() {
        const type = document.getElementById('listing_type').value;
        document.getElementById('bid-fields').style.display = type === 'bid' ? '' : 'none';
        document.getElementById('price-field').style.display = type !== 'bid' ? '' : 'none';
    }

    function previewImages(input, previewContainerId) {
        const previewContainer = document.getElementById(previewContainerId);
        previewContainer.innerHTML = '';
        
        if (input.files) {
            Array.from(input.files).slice(0, 5).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'relative w-14 h-14 rounded-md overflow-hidden shrink-0 border border-white/20 shadow-sm group pointer-events-auto';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '<span class="material-symbols-outlined text-[12px] font-bold">close</span>';
                    removeBtn.className = 'absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white w-5 h-5 flex items-center justify-center transition-all cursor-pointer border-none outline-none rounded-bl-md shadow-md z-10';
                    removeBtn.type = 'button';
                    removeBtn.onclick = function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        removeImage(index, input.id, previewContainerId);
                    };
                    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    previewContainer.appendChild(imgContainer);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    function removeImage(indexToRemove, inputId, previewContainerId) {
        const input = document.getElementById(inputId);
        const dt = new DataTransfer();
        
        Array.from(input.files).forEach((file, index) => {
            if (index !== indexToRemove) {
                dt.items.add(file);
            }
        });
        
        input.files = dt.files;
        previewImages(input, previewContainerId); // Re-render previews
    }
    </script>
    @endsection