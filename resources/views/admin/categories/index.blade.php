@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Category Management')
@section('content')
<div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Create Category --}}
        <div class="bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[24px] p-6 lg:p-8 h-fit">
            <h2 class="font-headline-md text-xl font-semibold text-secondary-fixed mb-6 flex items-center gap-2"><span class="material-symbols-outlined">add_circle</span> Add Category</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-5">
                    <label for="name" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Category Name</label>
                    <input type="text" id="name" name="name" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50" placeholder="e.g. Vegetables" required>
                    @error('name')<p class="text-error text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-5">
                    <label for="icon" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Icon (Emoji)</label>
                    <input type="text" id="icon" name="icon" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full px-4 py-3 font-body-md text-white focus:outline-none focus:border-secondary-fixed transition-colors placeholder:text-on-surface-variant/50" placeholder="🥬" maxlength="5">
                </div>
                <div class="mb-6">
                    <label for="color" class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase mb-2 block">Color Indicator</label>
                    <input type="color" id="color" name="color" value="#c3f400" class="w-full bg-surface/80 backdrop-blur-sm border border-white/10 rounded-full h-12 px-2 py-1 cursor-pointer">
                </div>
                <button type="submit" class="w-full bg-primary-fixed text-on-primary-fixed hover:bg-white hover:text-black px-6 py-3 rounded-full font-label-caps text-label-caps tracking-widest uppercase transition-all shadow-[0_0_15px_rgba(6,205,172,0.2)] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span> Save Category
                </button>
            </form>
        </div>

        {{-- Category List --}}
        <div class="lg:col-span-2 bg-surface-container/30 backdrop-blur-xl border border-white/5 shadow-[0_8px_32px_rgba(0,0,0,0.37)] rounded-[32px] overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-white/5 bg-surface/40">
                 <h2 class="font-headline-md text-xl font-semibold text-white flex items-center gap-2"><span class="material-symbols-outlined text-secondary-fixed">category</span> All Categories</h2>
            </div>
            
            <div class="divide-y divide-white/5">
                @forelse($categories as $category)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 hover:bg-white/5 transition-colors gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-[0_4px_10px_rgba(0,0,0,0.2)]" style="background: {{ $category->color }}20; border: 1px solid {{ $category->color }}50;">
                            {{ $category->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-body-md text-lg font-medium text-white">{{ $category->name }}</span>
                                <div class="w-2.5 h-2.5 rounded-full shadow-[0_0_8px_currentColor]" style="background: {{ $category->color }}; color: {{ $category->color }}"></div>
                            </div>
                            <div class="font-label-caps text-[10px] text-on-surface-variant tracking-widest uppercase flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">inventory_2</span> {{ $category->products_count }} products
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 self-start sm:self-auto">
                        <span class="px-3 py-1.5 rounded-full font-label-caps text-[10px] tracking-widest uppercase border inline-flex items-center gap-1 shadow-[0_0_10px_currentColor] {{ $category->is_active ? 'text-primary-fixed bg-primary-fixed/10 border-primary-fixed/30' : 'text-on-surface-variant bg-surface-container-high border-white/10' }}">
                            @if($category->is_active) <span class="w-1.5 h-1.5 rounded-full bg-primary-fixed"></span> @endif
                            {{ $category->is_active ? 'Active' : 'Hidden' }}
                        </span>
                        
                        <div class="flex items-center gap-2 ml-2 pl-4 border-l border-white/10">
                            <form method="POST" action="{{ route('admin.categories.toggle', $category) }}">
                                @csrf
                                <button type="submit" class="bg-surface-container-high border border-white/10 text-on-surface-variant hover:text-white hover:border-white/50 px-3 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1" title="{{ $category->is_active ? 'Hide' : 'Show' }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $category->is_active ? 'visibility_off' : 'visibility' }}</span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-error-container/20 border border-error/20 text-error hover:bg-error hover:text-on-error px-3 py-2 rounded-full font-label-caps text-[10px] tracking-widest uppercase transition-all flex items-center gap-1" onclick="return confirm('Delete this category?')" title="Delete">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-4 opacity-50">category</span>
                    <p class="font-body-md">No categories have been created yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
