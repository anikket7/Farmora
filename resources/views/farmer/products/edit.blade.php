@extends('layouts.farmer')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product Listing')

@section('content')
<div class="fade-in max-w-5xl mx-auto w-full">
    
    <div class="mb-4">
        <h2 class="font-headline-md text-[26px] text-white leading-tight">Edit Listing</h2>
        <p class="font-body-md text-on-surface-variant text-xs mt-1">Update your produce parameters for the marketplace.</p>
    </div>

    <div class="bg-surface-container/40 backdrop-blur-2xl border border-white/10 rounded-[24px] p-6 shadow-[0_8px_32px_rgba(0,0,0,0.37)] relative overflow-hidden">
        
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-fixed/5 rounded-full blur-[80px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-fixed/5 rounded-full blur-[80px] pointer-events-none"></div>

        <form id="product-form" method="POST" action="{{ route('farmer.products.update', $product) }}" enctype="multipart/form-data" class="relative z-10 flex flex-col h-full">
            @csrf @method('PUT')
            <div id="deleted-images-container"></div>

            <!-- Row 1: Title & Category -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="md:col-span-3">
                    <label for="title" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Product Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $product->title) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" required>
                    @error('title')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="category_id" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Category</label>
                    <div class="relative">
                        <select id="category_id" name="category_id" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all appearance-none scheme-dark" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" style="color:#ffffff; background:#121c24;" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
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
                        <input type="text" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white/50 text-sm cursor-not-allowed" value="{{ ucfirst($product->listing_type) }}" disabled>
                    </div>
                </div>
                <div>
                    <label for="quantity" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ (int) old('quantity', $product->quantity) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="1" min="1" required>
                    @error('quantity')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="unit" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Unit</label>
                    <div class="relative">
                        <select id="unit" name="unit" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all appearance-none scheme-dark" required>
                            @foreach(['kg', 'gram', 'dozen', 'piece', 'liter'] as $u)
                                <option value="{{ $u }}" style="color:#ffffff; background:#121c24;" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-[18px]">expand_more</span>
                    </div>
                </div>
                @if($product->isBuyable())
                    <div id="price-field">
                        <label for="price" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Price/Unit (₹)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-fixed font-bold text-sm">₹</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 pl-10 pr-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500" step="0.01" min="1">
                        </div>
                        @error('price')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                @endif
            </div>

            <!-- Row 4: Description & Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div class="flex flex-col">
                    <label for="description" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1 shrink-0">Description</label>
                    <textarea id="description" name="description" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500 flex-1 resize-none" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="text-error text-[10px] mt-1 ml-1 shrink-0">{{ $message }}</p>@enderror
                </div>
                
                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="harvest_date" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Harvest Date</label>
                            <input type="date" id="harvest_date" name="harvest_date" value="{{ old('harvest_date', $product->harvest_date?->format('Y-m-d')) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all scheme-dark">
                        </div>
                        <div>
                            <label for="origin_location" class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Origin</label>
                            <input type="text" id="origin_location" name="origin_location" value="{{ old('origin_location', $product->origin_location) }}" class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 font-body-md text-white text-sm focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed focus:bg-white/10 transition-all placeholder:text-slate-500">
                        </div>
                    </div>
                    
                    <div>
                        <!-- Current Images Display -->
                        @if($product->images->count() > 0)
                            <label class="block font-label-caps text-[11px] tracking-widest text-slate-300 uppercase mb-1.5 ml-1">Current Images</label>
                            <div class="flex gap-2 flex-wrap mb-4 bg-white/5 border border-white/10 rounded-xl p-3">
                                @foreach($product->images as $img)
                                    <div class="relative w-14 h-14 rounded-md overflow-hidden shrink-0 border border-white/20 shadow-sm group" id="current-img-{{ $img->id }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="">
                                        <button type="button" onclick="markImageForDeletion({{ $img->id }})" class="absolute inset-0 bg-red-500/80 hover:bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer border-none outline-none z-10">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full border border-dashed border-white/20 hover:border-secondary-fixed/50 bg-white/5 hover:bg-white/10 rounded-xl p-3 flex flex-col gap-3 transition-all cursor-pointer min-h-[60px] relative overflow-visible" onclick="document.getElementById('images_input').click()">
                            <div class="flex items-center gap-3 w-full">
                                <span class="material-symbols-outlined text-[20px] text-slate-400">add_photo_alternate</span>
                                <div>
                                    <p class="font-body-md text-white text-sm leading-tight">Add More Images</p>
                                    <p class="font-body-md text-slate-400 text-[10px] leading-tight mt-0.5">Max 5 JPG/PNG files</p>
                                </div>
                            </div>
                            <div id="edit-preview" class="flex gap-2 flex-wrap empty:hidden bg-surface-container/40 rounded-lg p-2 w-full z-30"></div>
                        </div>
                        <input type="file" id="images_input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImages(this, 'edit-preview')">
                        @error('images')<p class="text-error text-[10px] mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="mt-auto flex gap-4">
                <button type="submit" class="w-full flex-2 bg-secondary-fixed text-surface-container-lowest font-label-caps text-[14px] tracking-widest py-3.5 rounded-full hover:shadow-[0_0_20px_rgba(203,163,88,0.4)] transition-all flex items-center justify-center gap-3 hover:-translate-y-0.5 group">
                    <span class="material-symbols-outlined group-hover:scale-110 transition-transform">save</span> SAVE CHANGES
                </button>
                <a href="{{ route('farmer.products.index') }}" class="w-full flex-1 bg-surface-container/50 border border-white/10 text-white font-label-caps text-[14px] tracking-widest py-3.5 rounded-full hover:bg-white/10 transition-all flex items-center justify-center gap-3 hover:-translate-y-0.5">
                    CANCEL
                </a>
            </div>
        </form>
    </div>
</div>

<script>
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
                removeBtn.innerHTML = '<span class="material-symbols-outlined text-[14px] font-bold">close</span>';
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
    previewImages(input, previewContainerId);
}

function markImageForDeletion(imageId) {
    if(!confirm('Are you sure you want to remove this uploaded image? It will be permanently deleted upon saving.')) return;
    
    // Hide the image visually
    const imgContainer = document.getElementById('current-img-' + imageId);
    if(imgContainer) imgContainer.style.display = 'none';
    
    // Add a hidden input to the form to signal backend deletion
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_images[]';
    input.value = imageId;
    document.getElementById('deleted-images-container').appendChild(input);
}
</script>
@endsection